<?php

namespace App\Http\Controllers;

use App\Models\Armada;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ShipmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Shipment::with('armada');

        // 🔍 Pencarian berdasarkan nomor / tujuan
        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('nomor_pengiriman', 'like', "%{$request->keyword}%")
                ->orWhere('lokasi_tujuan', 'like', "%{$request->keyword}%");
            });
        }

        // 🔍 Filter berdasarkan jenis kendaraan
        if ($request->filled('jenis_kendaraan')) {
            $query->whereHas('armada', function ($q) use ($request) {
                $q->where('jenis_kendaraan', $request->jenis_kendaraan);
            });
        }

        // 🔍 Filter berdasarkan status armada
        if ($request->filled('status_ketersediaan')) {
            $query->whereHas('armada', function ($q) use ($request) {
                $q->where('status_ketersediaan', $request->status_ketersediaan);
            });
        }

        $shipments = $query->paginate(10);

        // 🔹 Ambil filter dinamis dari DB
        $jenisKendaraan = \App\Models\Armada::select('jenis_kendaraan')
                            ->distinct()->pluck('jenis_kendaraan');
        $statusKetersediaan = \App\Models\Armada::select('status_ketersediaan')
                            ->distinct()->pluck('status_ketersediaan');

        $armadas = \App\Models\Armada::all();

        return view('shipments.index', compact(
            'shipments',
            'armadas',
            'jenisKendaraan',
            'statusKetersediaan'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_pengiriman' => 'required|unique:shipments',
            'tanggal_pengiriman' => 'required|date',
            'lokasi_asal' => 'required',
            'lokasi_tujuan' => 'required',
            'status' => 'required',
            'detail_barang' => 'required',
            'armada_id' => 'nullable|exists:armadas,id',
        ]);

        Shipment::create($request->all());
        return redirect()->route('shipments.index')->with('success', 'Pengiriman berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $shipment = Shipment::findOrFail($id);

        $request->validate([
            'nomor_pengiriman' => 'required|unique:shipments,nomor_pengiriman,' . $id,
            'tanggal_pengiriman' => 'required|date',
            'lokasi_asal' => 'required',
            'lokasi_tujuan' => 'required',
            'status' => 'required',
            'detail_barang' => 'required',
            'armada_id' => 'nullable|exists:armadas,id',
        ]);

        $shipment->update($request->all());
        return redirect()->route('shipments.index')->with('success', 'Pengiriman berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $shipment = Shipment::findOrFail($id);
        $shipment->delete();
        return redirect()->route('shipments.index')->with('success', 'Pengiriman berhasil dihapus.');
    }

    public function laporanPengiriman()
    {
        $laporan = DB::table('shipments')
            ->join('armadas', 'shipments.armada_id', '=', 'armadas.id')
            ->select(
                'armadas.nomor_armada',
                DB::raw('COUNT(shipments.id) as total_pengiriman')
            )
            ->where('shipments.status', 'dalam perjalanan')
            ->groupBy('armadas.id', 'armadas.nomor_armada')
            ->get();

        return view('shipments.laporan', compact('laporan'));
    }

}