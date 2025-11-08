<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRanpurRequest;
use App\Http\Requests\UpdateRanpurRequest;
use App\Models\Ranpur;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RanpurController extends Controller implements HasMiddleware
{
    /**
     * Controller middleware.
     */
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('verified'),
            new Middleware('permission:ranpur.index', only: ['index']),
            new Middleware('permission:ranpur.create', only: ['create', 'store']),
            new Middleware('permission:ranpur.edit', only: ['edit', 'update']),
            new Middleware('permission:ranpur.destroy', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Ranpur::query();

        // Simple search by nomor_lambung, tipe, satuan, status
        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('nomor_lambung', 'like', "%{$search}%")
                    ->orWhere('tipe', 'like', "%{$search}%")
                    ->orWhere('satuan', 'like', "%{$search}%")
                    ->orWhere('status_kesiapan', 'like', "%{$search}%");
            });
        }

        $vehicles = $query->orderBy('nomor_lambung')->paginate(10)->withQueryString();

        return view('ranpur.index', compact('vehicles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ranpur.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRanpurRequest $request)
    {
        Ranpur::create($request->validated());
        return redirect()->route('ranpur.index')->with('success', 'Kendaraan berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ranpur $ranpur)
    {
        $vehicle = $ranpur;
        return view('ranpur.edit', compact('vehicle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRanpurRequest $request, Ranpur $ranpur)
    {
        $ranpur->update($request->validated());
        return redirect()->route('ranpur.index')->with('success', 'Kendaraan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ranpur $ranpur)
    {
        // Andalkan CASCADE FK; jika gagal (constraint belum diubah), lakukan fallback manual
        try {
            $ranpur->delete();
            return redirect()->route('ranpur.index')->with('success', 'Kendaraan berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            // Fallback manual cascade (untuk lingkungan yang belum update FK)
            try {
                // Hapus perbaikan (hapus juga stok keluar via event model)
                $ranpur->repairRecords()->each(function ($rec) { $rec->delete(); });

                // Hapus rangkaian kerusakan -> diagnosa -> permintaan -> item
                $damageIds = $ranpur->damageReports()->pluck('id');
                if ($damageIds->isNotEmpty()) {
                    $diagnoses = \App\Models\DiagnosisReport::whereIn('damage_report_id', $damageIds)->get();
                    foreach ($diagnoses as $d) {
                        $rqIds = \App\Models\SparepartRequest::where('diagnosis_report_id', $d->id)->pluck('id');
                        if ($rqIds->isNotEmpty()) {
                            \App\Models\SparepartRequestItem::whereIn('sparepart_request_id', $rqIds)->delete();
                            \App\Models\SparepartRequest::whereIn('id', $rqIds)->delete();
                        }
                        $d->delete();
                    }
                    \App\Models\LaporanKerusakan::whereIn('id', $damageIds)->delete();
                }

                // Hapus perawatan
                $ranpur->laporanRutins()->delete();

                // Lepas assign user
                \App\Models\User::where('ranpur_id', $ranpur->id)->update(['ranpur_id' => null]);

                // Coba delete lagi
                $ranpur->delete();
                return redirect()->route('ranpur.index')->with('success', 'Kendaraan beserta data terkait berhasil dihapus');
            } catch (\Throwable $t) {
                return redirect()->route('ranpur.index')->withErrors('Gagal menghapus ranpur beserta relasinya: ' . $t->getMessage());
            }
        }
    }
}
