<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRepairRecordRequest;
use App\Http\Requests\UpdateRepairRecordRequest;
use App\Models\LaporanKerusakan;
use App\Models\RepairRecord;
use App\Models\Ranpur;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RepairRecordController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('verified'),
            new Middleware('permission:repair-record.index', only: ['index']),
            new Middleware('permission:repair-record.create', only: ['create', 'store']),
            new Middleware('permission:repair-record.show', only: ['show']),
            new Middleware('permission:repair-record.edit', only: ['edit', 'update']),
            new Middleware('permission:repair-record.destroy', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $query = RepairRecord::with(['vehicle', 'damageReport', 'mechanic'])->orderByDesc('mulai');

        if ($vehicleId = $request->input('ranpur_id')) {
            $query->where('ranpur_id', $vehicleId);
        }
        if ($q = $request->input('q')) {
            $query->where(function ($qb) use ($q) {
                $qb->where('uraian_pekerjaan', 'like', "%{$q}%")
                   ->orWhere('hasil', 'like', "%{$q}%");
            });
        }

        $records = $query->paginate(10)->withQueryString();
        $vehicles = Ranpur::orderBy('nomor_lambung')->get(['id', 'nomor_lambung']);
        return view('repair_records.index', compact('records', 'vehicles'));
    }

    public function create()
    {
        $vehicles = Ranpur::orderBy('nomor_lambung')->get(['id', 'nomor_lambung']);
        $damages = LaporanKerusakan::with('vehicle')->orderByDesc('tanggal')->get(['id', 'ranpur_id', 'judul', 'tanggal']);
        return view('repair_records.create', compact('vehicles', 'damages'));
    }

    public function store(StoreRepairRecordRequest $request)
    {
        $data = $request->validated();
        $data['mechanic_id'] = auth()->id();
        if (isset($data['vehicle_id'])) {
            $data['ranpur_id'] = $data['vehicle_id'];
            unset($data['vehicle_id']);
        }
        RepairRecord::create($data);
        return redirect()->route('repair-record.index')->with('success', 'Catatan perbaikan berhasil ditambahkan');
    }

    public function edit(RepairRecord $repair_record)
    {
        $vehicles = Ranpur::orderBy('nomor_lambung')->get(['id', 'nomor_lambung']);
        $damages = LaporanKerusakan::with('vehicle')->orderByDesc('tanggal')->get(['id', 'ranpur_id', 'judul', 'tanggal']);
        return view('repair_records.edit', [
            'record' => $repair_record,
            'vehicles' => $vehicles,
            'damages' => $damages,
        ]);
    }

    public function update(UpdateRepairRecordRequest $request, RepairRecord $repair_record)
    {
        $data = $request->validated();
        // Keep original mechanic
        $data['mechanic_id'] = $repair_record->mechanic_id;
        if (isset($data['vehicle_id'])) {
            $data['ranpur_id'] = $data['vehicle_id'];
            unset($data['vehicle_id']);
        }
        $repair_record->update($data);
        return redirect()->route('repair-record.index')->with('success', 'Catatan perbaikan berhasil diperbarui');
    }

    public function destroy(RepairRecord $repair_record)
    {
        $repair_record->delete();
        return redirect()->route('repair-record.index')->with('success', 'Catatan perbaikan berhasil dihapus');
    }

    public function show(RepairRecord $repair_record)
    {
        $repair_record->load(['vehicle', 'damageReport', 'mechanic']);
        return view('repair_records.show', [
            'record' => $repair_record,
        ]);
    }
}
