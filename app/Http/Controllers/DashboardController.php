<?php

namespace App\Http\Controllers;

use App\Models\DiagnosisReport;
use App\Models\LaporanKerusakan;
use App\Models\LaporanRutin;
use App\Models\RepairRecord;
use App\Models\Sparepart;
use App\Models\SparepartRequest;
use App\Models\StockMovement;
use App\Models\Ranpur;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalVehicles = Ranpur::count();
        $readyVehicles = Ranpur::whereIn('status_kesiapan', ['SIAP LAUT', 'SIAP DARAT'])->count();
        $notReadyVehicles = Ranpur::where('status_kesiapan', 'TIDAK SIAP')->count();

        $drCounts = LaporanKerusakan::selectRaw('status, COUNT(*) as c')->groupBy('status')->pluck('c', 'status');
        $openDamage = ($drCounts['DILAPORKAN'] ?? 0) + ($drCounts['DIPERIKSA'] ?? 0) + ($drCounts['PROSES_PERBAIKAN'] ?? 0);

        $lowStockCount = Sparepart::whereColumn('stok', '<=', 'stok_minimum')->where('stok_minimum', '>', 0)->count();
        $totalSparepart = Sparepart::count();

        $pendingRequests = SparepartRequest::whereIn('status', ['DIAJUKAN', 'SEBAGIAN'])->count();
        $ongoingRepairs = RepairRecord::whereNull('selesai')->orWhere('hasil', 'TIDAK_SIAP')->count();
        $temuan30 = LaporanRutin::where('ada_temuan_kerusakan', true)
            ->where('tanggal', '>=', now()->subDays(30)->toDateString())->count();

        $recentDamage = LaporanKerusakan::with(['vehicle', 'reporter'])->latest('tanggal')->limit(5)->get();
        $recentMaint = LaporanRutin::with('vehicle')->latest('tanggal')->limit(5)->get();
        $recentMoves = StockMovement::with('sparepart')->latest('tanggal')->limit(5)->get();
        $lowStockList = Sparepart::with('location')
            ->whereColumn('stok', '<=', 'stok_minimum')
            ->orderByRaw('(stok - stok_minimum) asc')
            ->limit(5)->get();
        $recentRequests = SparepartRequest::with(['diagnosis.damageReport.vehicle', 'requester'])
            ->latest('tanggal')->limit(5)->get();

        // Charts data
        $damageStatusChart = [
            'labels' => ['DILAPORKAN', 'DIPERIKSA', 'PROSES_PERBAIKAN', 'SELESAI'],
            'data' => [
                (int) ($drCounts['DILAPORKAN'] ?? 0),
                (int) ($drCounts['DIPERIKSA'] ?? 0),
                (int) ($drCounts['PROSES_PERBAIKAN'] ?? 0),
                (int) ($drCounts['SELESAI'] ?? 0),
            ],
        ];

        $vehicleReadyChart = [
            'labels' => ['SIAP', 'TIDAK SIAP'],
            'data' => [(int) $readyVehicles, (int) $notReadyVehicles],
        ];

        // Maintenance per month (last 6 months)
        $maintenanceMonthly = ['labels' => [], 'data' => []];
        for ($i = 5; $i >= 0; $i--) {
            $start = Carbon::now()->subMonths($i)->startOfMonth();
            $end = (clone $start)->endOfMonth();
            $label = $start->format('M Y');
            $count = LaporanRutin::whereBetween('tanggal', [$start->toDateString(), $end->toDateString()])->count();
            $maintenanceMonthly['labels'][] = $label;
            $maintenanceMonthly['data'][] = (int) $count;
        }

        // Stock movement IN/OUT last 14 days
        $stockMovementsDaily = ['labels' => [], 'in' => [], 'out' => []];
        for ($i = 13; $i >= 0; $i--) {
            $day = Carbon::now()->subDays($i)->toDateString();
            $label = Carbon::now()->subDays($i)->format('d M');
            $in = (int) StockMovement::whereDate('tanggal', $day)->where('jenis', 'IN')->sum('qty');
            $out = (int) StockMovement::whereDate('tanggal', $day)->where('jenis', 'OUT')->sum('qty');
            $stockMovementsDaily['labels'][] = $label;
            $stockMovementsDaily['in'][] = $in;
            $stockMovementsDaily['out'][] = $out;
        }

        // Sparepart request status distribution
        $rqStatuses = ['DIAJUKAN', 'SEBAGIAN', 'DISETUJUI', 'DITOLAK', 'SELESAI'];
        $rqCounts = SparepartRequest::selectRaw('status, COUNT(*) as c')->groupBy('status')->pluck('c', 'status');
        $requestStatusChart = [
            'labels' => $rqStatuses,
            'data' => array_map(fn($s) => (int) ($rqCounts[$s] ?? 0), $rqStatuses),
        ];

        return view('dashboard.overview', compact(
            'totalVehicles',
            'readyVehicles',
            'notReadyVehicles',
            'drCounts',
            'openDamage',
            'lowStockCount',
            'totalSparepart',
            'pendingRequests',
            'ongoingRepairs',
            'temuan30',
            'recentDamage',
            'recentMaint',
            'recentMoves',
            'lowStockList',
            'recentRequests',
            'damageStatusChart',
            'vehicleReadyChart',
            'maintenanceMonthly',
            'stockMovementsDaily',
            'requestStatusChart'
        ));
    }
}
