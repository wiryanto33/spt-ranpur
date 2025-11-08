<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLaporanRutinRequest;
use App\Http\Requests\UpdateLaporanRutinRequest;
use App\Models\LaporanRutin;
use App\Models\Ranpur;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class LaporanRutinController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('verified'),
            new Middleware('permission:laporan-rutin.index', only: ['index']),
            new Middleware('permission:laporan-rutin.create', only: ['create', 'store']),
            new Middleware('permission:laporan-rutin.show', only: ['show']),
            new Middleware('permission:laporan-rutin.edit', only: ['edit', 'update']),
            new Middleware('permission:laporan-rutin.destroy', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $query = LaporanRutin::with(['vehicle', 'reporter'])->orderByDesc('tanggal');

        // Crew: only see own reports
        $user = $request->user();
        if ($user && !($user->hasRole('super-admin') || $user->hasRole('mechanic'))) {
            $query->where('reported_by', $user->id);
        }

        if ($vehicleId = $request->input('ranpur_id')) {
            $query->where('ranpur_id', $vehicleId);
        }

        if ($q = $request->input('q')) {
            $query->whereHas('vehicle', function ($sub) use ($q) {
                $sub->where('nomor_lambung', 'like', "%{$q}%")
                    ->orWhere('tipe', 'like', "%{$q}%")
                    ->orWhere('satuan', 'like', "%{$q}%");
            });
        }

        $reports = $query->paginate(10)->withQueryString();
        $vehicles = Ranpur::orderBy('nomor_lambung')->get(['id', 'nomor_lambung']);
        return view('laporan_rutin.index', compact('reports', 'vehicles'));
    }

    public function show(LaporanRutin $laporan_rutin)
    {
        $laporan_rutin->load(['vehicle', 'reporter']);
        return view('laporan_rutin.show', [
            'report' => $laporan_rutin,
        ]);
    }

    public function create()
    {
        $vehicles = Ranpur::orderBy('nomor_lambung')
            ->orderBy('tipe')
            ->get(['id', 'nomor_lambung', 'tipe']);
        return view('laporan_rutin.create', compact('vehicles'));
    }

    public function store(StoreLaporanRutinRequest $request)
    {
        $data = $request->validated();
        if (!auth()->user()->hasRole('super-admin') && !auth()->user()->hasRole('mechanic')) {
            if (!auth()->user()->ranpur_id) {
                return back()->withErrors(['ranpur_id' => 'Anda belum ditugaskan ke ranpur. Hubungi admin.'])->withInput();
            }
            $data['ranpur_id'] = auth()->user()->ranpur_id;
        }
        // Map vehicle_id from form to ranpur_id column
        if (isset($data['vehicle_id'])) {
            $data['ranpur_id'] = $data['vehicle_id'];
            unset($data['vehicle_id']);
        }
        $data['reported_by'] = auth()->id();
        $data['ada_temuan_kerusakan'] = (bool) ($data['ada_temuan_kerusakan'] ?? false);
        LaporanRutin::create($data);
        return redirect()->route('laporan-rutin.index')->with('success', 'Laporan perawatan berhasil ditambahkan');
    }

    public function edit(LaporanRutin $laporan_rutin)
    {
        $vehicles = Ranpur::orderBy('nomor_lambung')
            ->orderBy('tipe')
            ->get(['id', 'nomor_lambung', 'tipe']);
        return view('laporan_rutin
        .edit', [
            'report' => $laporan_rutin,
            'vehicles' => $vehicles,
        ]);
    }

    public function update(UpdateLaporanRutinRequest $request, LaporanRutin $laporan_rutin)
    {
        $data = $request->validated();
        if (!auth()->user()->hasRole('super-admin') && !auth()->user()->hasRole('mechanic')) {
            // lock vehicle to original for non-privileged
            $data['ranpur_id'] = $laporan_rutin->ranpur_id;
        }
        // Map vehicle_id from form to ranpur_id column
        if (isset($data['vehicle_id'])) {
            $data['ranpur_id'] = $data['vehicle_id'];
            unset($data['vehicle_id']);
        }
        $data['reported_by'] = $laporan_rutin->reported_by; // keep original reporter
        $data['ada_temuan_kerusakan'] = (bool) ($data['ada_temuan_kerusakan'] ?? false);
        $laporan_rutin->update($data);
        return redirect()->route('laporan-rutin.index')->with('success', 'Laporan perawatan berhasil diperbarui');
    }

    public function destroy(LaporanRutin $laporan_rutin)
    {
        $laporan_rutin->delete();
        return redirect()->route('laporan-rutin.index')->with('success', 'Laporan perawatan berhasil dihapus');
    }
}
