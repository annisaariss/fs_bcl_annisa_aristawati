<?php

namespace App\Http\Controllers;

use App\Models\Armada;
use App\Models\Checkin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CheckinController extends Controller
{
    public function index()
    {
        $checkins = Checkin::with('armada')->latest()->paginate(10);
        $armadas = Armada::all();

        return view('checkins.index', compact('checkins', 'armadas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'armada_id' => 'required|exists:armadas,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        Checkin::create($request->all());

        return redirect()->route('checkins.index')->with('success', 'Lokasi check-in berhasil ditambahkan');
    }

    public function destroy($id)
    {
        $checkin = Checkin::findOrFail($id);
        $checkin->delete();

        return redirect()->route('checkins.index')->with('success', 'Check-in berhasil dihapus');
    }
}