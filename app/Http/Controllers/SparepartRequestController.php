<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSparepartRequestRequest;
use App\Http\Requests\UpdateSparepartRequestRequest;
use App\Models\DiagnosisReport;
use App\Models\Sparepart;
use App\Models\SparepartRequest;
use App\Models\SparepartRequestItem;
use App\Models\User;
use App\Notifications\SparepartRequestCreated;
use App\Notifications\SparepartRequestStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class SparepartRequestController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('verified'),
            new Middleware('permission:sparepart-request.index', only: ['index']),
            new Middleware('permission:sparepart-request.create', only: ['create', 'store']),
            new Middleware('permission:sparepart-request.show', only: ['show']),
            new Middleware('permission:sparepart-request.edit', only: ['edit', 'update']),
            new Middleware('permission:sparepart-request.destroy', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $query = SparepartRequest::with(['diagnosis.damageReport.vehicle', 'requester', 'approver'])
            ->orderByDesc('tanggal');

        if ($q = $request->input('q')) {
            $query->where(function ($qb) use ($q) {
                $qb->where('catatan', 'like', "%{$q}%")
                    ->orWhere('status', 'like', "%{$q}%");
            });
        }

        $requests = $query->paginate(10)->withQueryString();
        return view('sparepart_requests.index', compact('requests'));
    }

    public function show(SparepartRequest $sparepart_request)
    {
        $sparepart_request->load(['diagnosis.damageReport.vehicle', 'items.sparepart', 'requester', 'approver']);
        return view('sparepart_requests.show', [
            'requestHeader' => $sparepart_request,
        ]);
    }

    public function create()
    {
        $diagnoses = DiagnosisReport::with('damageReport.vehicle')->orderByDesc('tanggal')->get();
        $spareparts = Sparepart::orderBy('kode')->get();
        return view('sparepart_requests.create', compact('diagnoses', 'spareparts'));
    }

    public function store(StoreSparepartRequestRequest $request)
    {
        $data = $request->validated();
        // Only staff and super-admin may set custom status. Others default to DIAJUKAN
        $user = $request->user();
        $canSetStatus = $user && ($user->hasRole('super-admin') || $user->hasRole('staff'));
        if (!$canSetStatus) {
            $data['status'] = 'DIAJUKAN';
        }
        $data['requested_by'] = auth()->id();

        $header = DB::transaction(function () use ($data) {
            $items = $data['items'] ?? [];
            unset($data['items']);

            $header = SparepartRequest::create($data);
            foreach ($items as $item) {
                SparepartRequestItem::create([
                    'sparepart_request_id' => $header->id,
                    'sparepart_id' => $item['sparepart_id'],
                    'qty_diminta' => $item['qty_diminta'],
                    'qty_disetujui' => 0,
                    'status_item' => 'DIAJUKAN',
                ]);
            }
            return $header;
        });

        // Notify super-admin and staff when a mechanic creates a request
        if (auth()->user()->hasRole('mechanic')) {
            $recipients = User::role(['super-admin', 'staff'])->get();
            if ($recipients->isNotEmpty()) {
                try{
                    $header->load(['diagnosis.damageReport.vehicle', 'requester']);
                    Notification::send($recipients, new SparepartRequestCreated($header));
                } catch (\Throwable $e) {
                    // Prevent notification failures (e.g., mail transport) from breaking request
                    report($e);
                }

            }
        }

        return redirect()->route('sparepart-request.index')->with('success', 'Permintaan sparepart berhasil diajukan');
    }

    public function edit(SparepartRequest $sparepart_request)
    {
        $sparepart_request->load(['items.sparepart', 'diagnosis.damageReport.vehicle']);
        $diagnoses = DiagnosisReport::with('damageReport.vehicle')->orderByDesc('tanggal')->get();
        $spareparts = Sparepart::orderBy('kode')->get();
        return view('sparepart_requests.edit', [
            'requestHeader' => $sparepart_request,
            'diagnoses' => $diagnoses,
            'spareparts' => $spareparts,
        ]);
    }

    public function update(UpdateSparepartRequestRequest $request, SparepartRequest $sparepart_request)
    {
        $data = $request->validated();
        $oldStatus = $sparepart_request->status;
        // Only staff and super-admin may change status
        $user = $request->user();
        $canSetStatus = $user && ($user->hasRole('super-admin') || $user->hasRole('staff'));
        if (!$canSetStatus) {
            $data['status'] = $sparepart_request->status;
        }
        $newStatus = $data['status'] ?? $sparepart_request->status;

        // auto set/unset approver based on status
        if (($data['status'] ?? $sparepart_request->status) === 'DISETUJUI' && empty($data['approved_by'])) {
            $data['approved_by'] = auth()->id();
        }
        if (($data['status'] ?? null) === 'DIAJUKAN') {
            $data['approved_by'] = null;
        }

        DB::transaction(function () use ($sparepart_request, $data) {
            $items = $data['items'] ?? [];
            unset($data['items']);

            $sparepart_request->update($data);

            // replace items for simplicity
            $sparepart_request->items()->delete();
            foreach ($items as $item) {
                SparepartRequestItem::create([
                    'sparepart_request_id' => $sparepart_request->id,
                    'sparepart_id' => $item['sparepart_id'],
                    'qty_diminta' => $item['qty_diminta'],
                    'qty_disetujui' => (int)($item['qty_disetujui'] ?? 0),
                    'status_item' => $item['status_item'] ?? 'DIAJUKAN',
                ]);
            }
        });

        // Notify mechanic (requester) when admin/staff updates the status
        if ($canSetStatus && isset($newStatus) && $newStatus !== $oldStatus) {
            $sparepart_request->loadMissing('requester');
            if ($sparepart_request->requester && $sparepart_request->requester->hasRole('mechanic')) {
                try {

                    $sparepart_request->requester->notify(new SparepartRequestStatusUpdated($sparepart_request, $oldStatus, $newStatus));
                } catch (\Throwable $e) {
                    // Prevent notification failures (e.g., mail transport) from breaking request
                    report($e);
                }
            }
        }

        return redirect()->route('sparepart-request.index')->with('success', 'Permintaan sparepart berhasil diperbarui');
    }

    public function destroy(SparepartRequest $sparepart_request)
    {
        $sparepart_request->items()->delete();
        $sparepart_request->delete();
        return redirect()->route('sparepart-request.index')->with('success', 'Permintaan sparepart berhasil dihapus');
    }
}
