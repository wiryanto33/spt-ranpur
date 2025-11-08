<?php

namespace App\Http\Controllers;


use App\Http\Requests\StoreLaporanKerusakanRequest;
use App\Http\Requests\UpdateLaporanKerusakanRequest;
use App\Models\LaporanKerusakan;
use App\Models\Ranpur;
use App\Models\User;
use App\Notifications\DamageReportCreated;
use App\Notifications\DamageReportStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Notification;

class LaporanKerusakanController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('verified'),
            new Middleware('permission:laporan-kerusakan.index', only: ['index']),
            new Middleware('permission:laporan-kerusakan.create', only: ['create', 'store']),
            new Middleware('permission:laporan-kerusakan.show', only: ['show']),
            new Middleware('permission:laporan-kerusakan.edit', only: ['edit', 'update']),
            new Middleware('permission:laporan-kerusakan.destroy', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $query = LaporanKerusakan::with(['vehicle', 'reporter'])->orderByDesc('tanggal');

        // Crew: only see own reports
        $user = $request->user();
        if ($user && !($user->hasRole('super-admin') || $user->hasRole('mechanic'))) {
            $query->where('reported_by', $user->id);
        }

        if ($vehicleId = $request->input('ranpur_id')) {
            $query->where('ranpur_id', $vehicleId);
        }

        if ($q = $request->input('q')) {
            $query->where(function ($qBuilder) use ($q) {
                $qBuilder->where('judul', 'like', "%{$q}%")
                    ->orWhereHas('vehicle', function ($sub) use ($q) {
                        $sub->where('nomor_lambung', 'like', "%{$q}%")
                            ->orWhere('tipe', 'like', "%{$q}%")
                            ->orWhere('satuan', 'like', "%{$q}%");
                    });
            });
        }

        $reports = $query->paginate(10)->withQueryString();
        $vehicles = Ranpur::orderBy('nomor_lambung')
            ->orderBy('tipe')
            ->get(['id', 'nomor_lambung', 'tipe']);
        return view('laporan_kerusakan.index', compact('reports', 'vehicles'));
    }

    public function show(LaporanKerusakan $laporan_kerusakan)
    {
        $laporan_kerusakan->load(['vehicle', 'reporter']);
        return view('laporan_kerusakan.show', [
            'report' => $laporan_kerusakan,
        ]);
    }

    public function create()
    {
        $vehicles = Ranpur::orderBy('nomor_lambung')
            ->orderBy('tipe')
            ->get(['id', 'nomor_lambung', 'tipe']);
        return view('laporan_kerusakan.create', compact('vehicles'));
    }

    public function store(StoreLaporanKerusakanRequest $request)
    {
        $data = $request->validated();
        if (!auth()->user()->hasRole('super-admin') && !auth()->user()->hasRole('mechanic')) {
            if (!auth()->user()->ranpur_id) {
                return back()->withErrors(['ranpur_id' => 'Anda belum ditugaskan ke ranpur. Hubungi admin.'])->withInput();
            }
            $data['ranpur_id'] = auth()->user()->ranpur_id;
        }
        if (!auth()->user()->hasRole('super-admin') && !auth()->user()->hasRole('mechanic')) {
            $data['status'] = 'DILAPORKAN';
        }
        // Map vehicle_id from form to ranpur_id column for privileged users
        if (isset($data['vehicle_id'])) {
            $data['ranpur_id'] = $data['vehicle_id'];
            unset($data['vehicle_id']);
        }
        $data['reported_by'] = auth()->id();
        $report = LaporanKerusakan::create($data);

        // Notify mechanic, staff, and super-admin when a crew creates a report
        if (auth()->user()->hasRole('crew')) {
            $recipients = User::role(['mechanic', 'staff', 'super-admin'])->get();
            if ($recipients->isNotEmpty()) {
                $report->load('vehicle');
                Notification::send($recipients, new DamageReportCreated($report));
            }
        }
        return redirect()->route('laporan-kerusakan.index')->with('success', 'Laporan kerusakan berhasil ditambahkan');
    }

    public function edit(LaporanKerusakan $laporan_kerusakan)
    {
        $vehicles = Ranpur::orderBy('nomor_lambung')
            ->orderBy('tipe')
            ->get(['id', 'nomor_lambung', 'tipe']);
        return view('laporan_kerusakan.edit', [
            'report' => $laporan_kerusakan,
            'vehicles' => $vehicles,
        ]);
    }

    public function update(UpdateLaporanKerusakanRequest $request, LaporanKerusakan $laporan_kerusakan)
    {
        $data = $request->validated();
        $oldStatus = $laporan_kerusakan->status;
        if (!auth()->user()->hasRole('super-admin') && !auth()->user()->hasRole('mechanic')) {
            // non privileged users cannot change status
            $data['status'] = $laporan_kerusakan->status;
            // lock vehicle to original too
            $data['ranpur_id'] = $laporan_kerusakan->ranpur_id;
        }
        // Map vehicle_id from form to ranpur_id column for privileged users
        if (isset($data['vehicle_id'])) {
            $data['ranpur_id'] = $data['vehicle_id'];
            unset($data['vehicle_id']);
        }
        $data['reported_by'] = $laporan_kerusakan->reported_by; // keep original reporter
        $laporan_kerusakan->update($data);

        // Notify reporter (crew) when status changed
        if (isset($data['status']) && $data['status'] !== $oldStatus) {
            $laporan_kerusakan->loadMissing('reporter');
            if ($laporan_kerusakan->reporter) {
                $laporan_kerusakan->reporter->notify(new DamageReportStatusUpdated($laporan_kerusakan, $oldStatus, $data['status']));
            }
        }
        return redirect()->route('laporan-kerusakan.index')->with('success', 'Laporan kerusakan berhasil diperbarui');
    }

    public function destroy(LaporanKerusakan $laporan_kerusakan)
    {
        $laporan_kerusakan->delete();
        return redirect()->route('laporan-kerusakan.index')->with('success', 'Laporan kerusakan berhasil dihapus');
    }
}
