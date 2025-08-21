<?php

namespace App\Http\Controllers;

use App\Models\Armada;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class ArmadaController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->keyword;
        $armadas = Armada::query();

        if ($keyword) {
            $armadas->where('nomor_armada', 'like', "%$keyword%")
                    ->orWhere('jenis_kendaraan', 'like', "%$keyword%");
        }

        if ($request->has('sort') && in_array($request->input('direction'), ['asc', 'desc'])) {
            $armadas->orderBy($request->input('sort'), $request->input('direction'));
        }

        $armadas = $armadas->paginate(10);
        return view('armada.index', compact('armadas'));
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nomor_armada' => 'required|unique:armadas',
                'jenis_kendaraan' => 'required',
                'kapasitas_muatan' => 'required|integer|min:1',
                'status_ketersediaan' => 'required|in:tersedia,tidak tersedia',
            ]);

            Armada::create($validatedData);

            Alert::toast('Data Armada berhasil ditambahkan', 'success');
            return redirect()->route('armada.index');
        } catch (ValidationException $th) {
            Alert::error('Gagal', $th->validator->errors()->first());
            return redirect()->back()->withInput();
        } catch (Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan');
            return redirect()->back()->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'nomor_armada' => 'required|unique:armadas,nomor_armada,' . $id,
                'jenis_kendaraan' => 'required',
                'kapasitas_muatan' => 'required|integer|min:1',
                'status_ketersediaan' => 'required|in:tersedia,tidak tersedia',
            ]);

            $armada = Armada::findOrFail($id);
            $armada->update($validatedData);

            Alert::toast('Data Armada berhasil diperbarui', 'success');
            return redirect()->route('armada.index');
        } catch (ValidationException $th) {
            Alert::error('Gagal', $th->validator->errors()->first());
            return redirect()->back()->withInput();
        } catch (Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan');
            return redirect()->back()->withInput();
        }
    }

    public function destroy($id)
    {
        $armada = Armada::findOrFail($id);
        $armada->delete();

        Alert::toast('Data Armada berhasil dihapus', 'success');
        return redirect()->route('armada.index');
    }
}
