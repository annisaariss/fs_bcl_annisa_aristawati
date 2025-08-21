@extends('layouts.index')

@section('title', 'Laporan Pengiriman')

@section('content')
<section class="section">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Laporan Pengiriman Armada</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <tr>
                                <th>No.</th>
                                <th>Nomor Armada</th>
                                <th>Total Pengiriman Dalam Perjalanan</th>
                            </tr>
                            @forelse($laporan as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->nomor_armada }}</td>
                                    <td>{{ $item->total_pengiriman }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada data pengiriman dalam perjalanan</td>
                                </tr>
                            @endforelse
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
