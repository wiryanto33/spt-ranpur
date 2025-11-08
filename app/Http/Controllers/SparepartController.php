<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSparepartRequest;
use App\Http\Requests\UpdateSparepartRequest;
use App\Models\Sparepart;
use App\Models\StorageLocation;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;

class SparepartController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('verified'),
            new Middleware('permission:sparepart.index', only: ['index']),
            new Middleware('permission:sparepart.create', only: ['create', 'store']),
            new Middleware('permission:sparepart.show', only: ['show']),
            new Middleware('permission:sparepart.edit', only: ['edit', 'update']),
            new Middleware('permission:sparepart.destroy', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $query = Sparepart::with('location')->orderBy('kode');

        if ($q = $request->input('q')) {
            $query->where(function ($qb) use ($q) {
                $qb->where('kode', 'like', "%{$q}%")
                    ->orWhere('nama', 'like', "%{$q}%");
            });
        }

        $spareparts = $query->paginate(10)->withQueryString();
        return view('spareparts.index', compact('spareparts'));
    }

    public function show(Sparepart $sparepart)
    {
        $sparepart->load('location');
        return view('spareparts.show', compact('sparepart'));
    }

    public function create()
    {
        $locations = StorageLocation::orderBy('kode')->get(['id', 'kode', 'nama']);
        return view('spareparts.create', compact('locations'));
    }

    public function store(StoreSparepartRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $dir = public_path('uploads/spareparts');
            if (!is_dir($dir)) {
                @mkdir($dir, 0755, true);
            }
            $name = uniqid('sp_', true) . '.' . $file->getClientOriginalExtension();
            $file->move($dir, $name);
            $data['image'] = 'uploads/spareparts/' . $name;
        }
        Sparepart::create($data);
        return redirect()->route('sparepart.index')->with('success', 'Sparepart berhasil ditambahkan');
    }

    public function edit(Sparepart $sparepart)
    {
        $locations = StorageLocation::orderBy('kode')->get(['id', 'kode', 'nama']);
        return view('spareparts.edit', compact('sparepart', 'locations'));
    }

    public function update(UpdateSparepartRequest $request, Sparepart $sparepart)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            if ($sparepart->image) {
                if (preg_match('/^uploads\//', $sparepart->image)) {
                    @unlink(public_path($sparepart->image));
                } else {
                    Storage::disk('public')->delete($sparepart->image);
                }
            }
            $file = $request->file('image');
            $dir = public_path('uploads/spareparts');
            if (!is_dir($dir)) {
                @mkdir($dir, 0755, true);
            }
            $name = uniqid('sp_', true) . '.' . $file->getClientOriginalExtension();
            $file->move($dir, $name);
            $data['image'] = 'uploads/spareparts/' . $name;
        }
        $sparepart->update($data);
        return redirect()->route('sparepart.index')->with('success', 'Sparepart berhasil diperbarui');
    }

    public function destroy(Sparepart $sparepart)
    {
        if ($sparepart->image) {
            if (preg_match('/^uploads\//', $sparepart->image)) {
                @unlink(public_path($sparepart->image));
            } else {
                Storage::disk('public')->delete($sparepart->image);
            }
        }
        $sparepart->delete();
        return redirect()->route('sparepart.index')->with('success', 'Sparepart berhasil dihapus');
    }
}
