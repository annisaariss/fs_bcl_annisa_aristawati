<?php

namespace App\Http\Controllers;

use App\Models\Armada;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with('armada')->latest()->paginate(10);
        $armadas = Armada::where('status_ketersediaan', 'tersedia')->get();
        return view('bookings.index', compact('bookings', 'armadas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'armada_id' => 'required|exists:armadas,id',
            'tanggal_pemesanan' => 'required|date|after_or_equal:today',
            'detail_barang' => 'required|string',
        ]);

        $armada = Armada::findOrFail($validated['armada_id']);

        if ($armada->status_ketersediaan !== 'tersedia') {
            return redirect()->back()->with('error', 'Armada tidak tersedia untuk dipesan.');
        }

        Booking::create($validated);

        // update status armada jadi tidak tersedia
        $armada->update(['status_ketersediaan' => 'tidak tersedia']);

        Alert::toast('Booking berhasil dibuat', 'success');
        return redirect()->route('bookings.index');
    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $armada = $booking->armada;

        // hapus booking
        $booking->delete();

        // armada kembali tersedia
        if ($armada) {
            $armada->update(['status_ketersediaan' => 'tersedia']);
        }

        Alert::toast('Booking berhasil dihapus', 'success');
        return redirect()->route('bookings.index');
    }
}