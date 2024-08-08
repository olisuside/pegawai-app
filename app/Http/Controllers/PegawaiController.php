<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PegawaiController extends Controller
{
    public function index()
    {
        $pegawais = Pegawai::all();
        return view('app', compact('pegawais'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'posisi' => 'required|string',
            'tanggalMasuk' => 'required|date',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Maksimal 2MB
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $pegawai = new Pegawai();
        $pegawai->nama = $request->input('nama');
        $pegawai->posisi = $request->input('posisi');
        $pegawai->tanggal_masuk = $request->input('tanggalMasuk');

        // Handle foto upload
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('public/fotos');
            $pegawai->foto = Storage::url($fotoPath);
        }

        $pegawai->save();

        return response()->json(['success' => 'Pegawai berhasil ditambahkan!']);
    }
}
