<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDiagnosisReportRequest;
use App\Http\Requests\UpdateDiagnosisReportRequest;
use App\Models\LaporanKerusakan;
use App\Models\DiagnosisReport;
use App\Models\Ranpur;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class DiagnosisReportController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('verified'),
            new Middleware('permission:diagnosis-report.index', only: ['index']),
            new Middleware('permission:diagnosis-report.create', only: ['create', 'store']),
            new Middleware('permission:diagnosis-report.show', only: ['show']),
            new Middleware('permission:diagnosis-report.edit', only: ['edit', 'update']),
            new Middleware('permission:diagnosis-report.destroy', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $query = DiagnosisReport::with(['damageReport.vehicle', 'mechanic'])->orderByDesc('tanggal');

        if ($vehicleId = $request->input('ranpur_id')) {
            $query->whereHas('damageReport', function ($q) use ($vehicleId) {
                $q->where('ranpur_id', $vehicleId);
            });
        }
        if ($q = $request->input('q')) {
            $query->where(function ($qb) use ($q) {
                $qb->where('temuan', 'like', "%{$q}%")
                    ->orWhere('rencana_tindakan', 'like', "%{$q}%");
            });
        }

        $reports = $query->paginate(10)->withQueryString();
        $vehicles = Ranpur::orderBy('nomor_lambung')->get(['id', 'nomor_lambung']);
        return view('diagnosis_reports.index', compact('reports', 'vehicles'));
    }

    public function show(DiagnosisReport $diagnosis_report)
    {
        $diagnosis_report->load(['damageReport.vehicle', 'mechanic']);
        return view('diagnosis_reports.show', [
            'report' => $diagnosis_report,
        ]);
    }

    public function create()
    {
        $openDamages = LaporanKerusakan::with('vehicle')->orderByDesc('tanggal')->get();
        return view('diagnosis_reports.create', ['damageReports' => $openDamages]);
    }

    public function store(StoreDiagnosisReportRequest $request)
    {
        $data = $request->validated();
        $data['mechanic_id'] = auth()->id();
        DiagnosisReport::create($data);
        return redirect()->route('diagnosis-report.index')->with('success', 'Laporan diagnosa berhasil ditambahkan');
    }

    public function edit(DiagnosisReport $diagnosis_report)
    {
        $openDamages = LaporanKerusakan::with('vehicle')->orderByDesc('tanggal')->get();
        return view('diagnosis_reports.edit', [
            'report' => $diagnosis_report,
            'damageReports' => $openDamages,
        ]);
    }

    public function update(UpdateDiagnosisReportRequest $request, DiagnosisReport $diagnosis_report)
    {
        $data = $request->validated();
        // keep mechanic_id original
        $data['mechanic_id'] = $diagnosis_report->mechanic_id;
        $diagnosis_report->update($data);
        return redirect()->route('diagnosis-report.index')->with('success', 'Laporan diagnosa berhasil diperbarui');
    }

    public function destroy(DiagnosisReport $diagnosis_report)
    {
        $diagnosis_report->delete();
        return redirect()->route('diagnosis-report.index')->with('success', 'Laporan diagnosa berhasil dihapus');
    }
}
