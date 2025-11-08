<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStockMovementRequest;
use App\Http\Requests\UpdateStockMovementRequest;
use App\Models\Sparepart;
use App\Models\SparepartRequestItem;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;

class StockMovementController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('verified'),
            new Middleware('permission:stock-movement.index', only: ['index']),
            new Middleware('permission:stock-movement.create', only: ['create', 'store']),
            new Middleware('permission:stock-movement.edit', only: ['edit', 'update']),
            new Middleware('permission:stock-movement.destroy', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $query = StockMovement::with(['sparepart', 'performer'])
            ->orderByDesc('tanggal');

        if ($q = $request->input('q')) {
            $query->where(function ($qb) use ($q) {
                $qb->where('keterangan', 'like', "%{$q}%")
                    ->orWhere('jenis', 'like', "%{$q}%");
            });
        }
        if ($spId = $request->input('sparepart_id')) {
            $query->where('sparepart_id', $spId);
        }

        $movements = $query->paginate(10)->withQueryString();
        $spareparts = Sparepart::orderBy('kode')->get(['id', 'kode', 'nama']);
        return view('stock_movements.index', compact('movements', 'spareparts'));
    }

    public function create()
    {
        $spareparts = Sparepart::orderBy('kode')->get(['id', 'kode', 'nama']);
        $requestItems = SparepartRequestItem::with(['request.diagnosis.damageReport.vehicle', 'sparepart'])
            ->orderByDesc('id')
            ->limit(100)
            ->get();
        return view('stock_movements.create', compact('spareparts', 'requestItems'));
    }

    public function store(StoreStockMovementRequest $request)
    {
        $data = $request->validated();
        $data['performed_by'] = auth()->id();

        DB::transaction(function () use ($data) {
            if (empty($data['reference_type']) || empty($data['reference_id'])) {
                $data['reference_type'] = \App\Models\Sparepart::class;
                $data['reference_id'] = $data['sparepart_id'];
            }
            // Persist
            $movement = StockMovement::create($data);

            // Adjust stock
            $sp = Sparepart::lockForUpdate()->findOrFail($movement->sparepart_id);
            if ($movement->jenis === 'IN') {
                $sp->stok += $movement->qty;
            } else {
                $sp->stok = max(0, $sp->stok - $movement->qty);
            }
            $sp->save();
        });

        return redirect()->route('stock-movement.index')->with('success', 'Pergerakan stok ditambahkan');
    }

    public function edit(StockMovement $stock_movement)
    {
        $spareparts = Sparepart::orderBy('kode')->get(['id', 'kode', 'nama']);
        $requestItems = SparepartRequestItem::with(['request.diagnosis.damageReport.vehicle', 'sparepart'])
            ->orderByDesc('id')
            ->limit(100)
            ->get();
        return view('stock_movements.edit', [
            'movement' => $stock_movement,
            'spareparts' => $spareparts,
            'requestItems' => $requestItems,
        ]);
    }

    public function update(UpdateStockMovementRequest $request, StockMovement $stock_movement)
    {
        $data = $request->validated();

        DB::transaction(function () use ($stock_movement, $data) {
            if (empty($data['reference_type']) || empty($data['reference_id'])) {
                $data['reference_type'] = \App\Models\Sparepart::class;
                $data['reference_id'] = $data['sparepart_id'];
            }
            // Revert previous stock impact
            $spPrev = Sparepart::lockForUpdate()->findOrFail($stock_movement->sparepart_id);
            if ($stock_movement->jenis === 'IN') {
                $spPrev->stok = max(0, $spPrev->stok - $stock_movement->qty);
            } else {
                $spPrev->stok += $stock_movement->qty;
            }
            $spPrev->save();

            // Update movement
            $stock_movement->update($data);

            // Apply new stock impact
            $spNew = Sparepart::lockForUpdate()->findOrFail($stock_movement->sparepart_id);
            if ($stock_movement->jenis === 'IN') {
                $spNew->stok += $stock_movement->qty;
            } else {
                $spNew->stok = max(0, $spNew->stok - $stock_movement->qty);
            }
            $spNew->save();
        });

        return redirect()->route('stock-movement.index')->with('success', 'Pergerakan stok diperbarui');
    }

    public function destroy(StockMovement $stock_movement)
    {
        DB::transaction(function () use ($stock_movement) {
            // Revert stock impact then delete
            $sp = Sparepart::lockForUpdate()->findOrFail($stock_movement->sparepart_id);
            if ($stock_movement->jenis === 'IN') {
                $sp->stok = max(0, $sp->stok - $stock_movement->qty);
            } else {
                $sp->stok += $stock_movement->qty;
            }
            $sp->save();

            $stock_movement->delete();
        });

        return redirect()->route('stock-movement.index')->with('success', 'Pergerakan stok dihapus');
    }
}
