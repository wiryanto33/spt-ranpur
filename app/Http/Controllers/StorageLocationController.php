<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStorageLocationRequest;
use App\Http\Requests\UpdateStorageLocationRequest;
use App\Models\StorageLocation;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class StorageLocationController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('verified'),
            new Middleware('permission:storage-location.index', only: ['index']),
            new Middleware('permission:storage-location.create', only: ['create', 'store']),
            new Middleware('permission:storage-location.edit', only: ['edit', 'update']),
            new Middleware('permission:storage-location.destroy', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $query = StorageLocation::with('parent')->orderBy('kode');

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('kode', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%");
            });
        }

        $locations = $query->paginate(10)->withQueryString();
        return view('storage_locations.index', compact('locations'));
    }

    public function create()
    {
        $parents = StorageLocation::orderBy('kode')->get(['id', 'kode', 'nama']);
        return view('storage_locations.create', compact('parents'));
    }

    public function store(StoreStorageLocationRequest $request)
    {
        StorageLocation::create($request->validated());
        return redirect()->route('storage-location.index')->with('success', 'Lokasi penyimpanan berhasil ditambahkan');
    }

    public function edit(StorageLocation $storage_location)
    {
        $parents = StorageLocation::where('id', '!=', $storage_location->id)->orderBy('kode')->get(['id', 'kode', 'nama']);
        return view('storage_locations.edit', ['location' => $storage_location, 'parents' => $parents]);
    }

    public function update(UpdateStorageLocationRequest $request, StorageLocation $storage_location)
    {
        $storage_location->update($request->validated());
        return redirect()->route('storage-location.index')->with('success', 'Lokasi penyimpanan berhasil diperbarui');
    }

    public function destroy(StorageLocation $storage_location)
    {
        $storage_location->delete();
        return redirect()->route('storage-location.index')->with('success', 'Lokasi penyimpanan berhasil dihapus');
    }
}

