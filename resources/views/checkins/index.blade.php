@extends('layouts.index')

@section('title', 'Lokasi Check-In Armada')

@section('content')
<section class="section">
    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-header">
                    <h4>Lokasi Armada (Check-In)</h4>
                    <button class="btn btn-primary float-end" data-toggle="modal" data-target="#addCheckinModal">
                        <i class="fas fa-plus"></i> Tambah Check-In
                    </button>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div id="map" style="height: 400px;"></div>
                    <br>

                    <table class="table table-bordered">
                        <tr>
                            <th>No.</th>
                            <th>Armada</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <th>Waktu</th>
                            <th>Aksi</th>
                        </tr>
                        @foreach ($checkins as $i => $checkin)
                        <tr>
                            <td>{{ $i + $checkins->firstItem() }}</td>
                            <td>{{ $checkin->armada->nomor_armada }}</td>
                            <td>{{ $checkin->latitude }}</td>
                            <td>{{ $checkin->longitude }}</td>
                            <td>{{ $checkin->waktu_checkin }}</td>
                            <td>
                                <form action="{{ route('checkins.destroy', $checkin->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                    {{ $checkins->links() }}
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Tambah Checkin -->
<div class="modal fade" id="addCheckinModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('checkins.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Lokasi Check-In</h5>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Pilih Armada</label>
                        <select name="armada_id" class="form-control" required>
                            <option value="">-- Pilih Armada --</option>
                            @foreach($armadas as $armada)
                                <option value="{{ $armada->id }}">{{ $armada->nomor_armada }} ({{ $armada->jenis_kendaraan }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Latitude</label>
                        <input type="text" name="latitude" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Longitude</label>
                        <input type="text" name="longitude" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Leaflet JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    var map = L.map('map').setView([-6.200000, 106.816666], 6); // Default Jakarta

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    @foreach($checkins as $checkin)
        L.marker([{{ $checkin->latitude }}, {{ $checkin->longitude }}])
            .addTo(map)
            .bindPopup("{{ $checkin->armada->nomor_armada }} - {{ $checkin->waktu_checkin }}");
    @endforeach
</script>
@endsection
