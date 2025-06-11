<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BukuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bukus = Buku::all();
        return view('crud', compact('bukus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string',
            'penulis' => 'required|string',
            'penerbit' => 'required|string',
            'cover' => 'required|file|mimes:png,jpg,jpeg',
            'genre' => 'required|in:fiksi,non_fiksi',
            'status' => 'required|in:tersedia,tidak_tersedia',
            'harga' => 'required|numeric|min:0|max:999999999999.99',
        ]);

        $filePath = $request->file('cover')->store('cover_buku', 'public');

        $buku = Buku::create([
            'judul' => $request->input('judul'),
            'penulis' => $request->input('penulis'),
            'penerbit' => $request->input('penerbit'),
            'genre' => $request->input('genre'),
            'status' => $request->input('status'),
            'harga' => $request->input('harga'),
            'cover' => $filePath,
        ]);

        return redirect()->back()->with('success', 'Buku saved successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'judul' => 'required|string',
            'penulis' => 'required|string',
            'penerbit' => 'required|string',
            'genre' => 'required|in:fiksi,non_fiksi',
            'status' => 'required|in:tersedia,tidak_tersedia',
            'harga' => 'required|numeric|min:0|max:999999999999.99',
            'cover' => 'nullable|file|mimes:png,jpg,jpeg',
        ]);

        $buku = Buku::findOrFail($id);

        if ($request->hasFile('cover')) {
            $filePath = $request->file('cover')->store('cover_buku', 'public');
            $buku->cover = $filePath;
        }

        $buku->judul = $request->judul;
        $buku->penulis = $request->penulis;
        $buku->penerbit = $request->penerbit;
        $buku->genre = $request->genre;
        $buku->status = $request->status;
        $buku->harga = $request->harga;
        $buku->save();

        return redirect()->back()->with('success', 'Buku updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $buku = Buku::findOrFail($id);
        
        if ($buku->cover) {
            Storage::disk('public')->delete($buku->cover);
        }

        $buku->delete();

        return redirect()->back()->with('success', 'Buku deleted successfully!');
    }
}
