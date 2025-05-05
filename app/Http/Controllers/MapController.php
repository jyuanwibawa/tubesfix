<?php

namespace App\Http\Controllers;

use App\Models\CollectionPoint;
use Illuminate\Support\Facades\Validator;

class MapController extends Controller
{
    public function index()
    {
        $collectionPoints = CollectionPoint::all();
        return view('peta.index', compact('collectionPoints'));
    }

    public function create()
    {
        return view('peta.create'); // buat view ini nanti
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:50',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        CollectionPoint::create($validated);

        return redirect()->route('collection-points.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $point = CollectionPoint::findOrFail($id);
        return view('peta.edit', compact('point')); // buat view ini nanti
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:50',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        $point = CollectionPoint::findOrFail($id);
        $point->update($validated);

        return redirect()->route('collection-points.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $point = CollectionPoint::findOrFail($id);
        $point->delete();

        return redirect()->route('collection-points.index')->with('success', 'Data berhasil dihapus.');
    }
}
