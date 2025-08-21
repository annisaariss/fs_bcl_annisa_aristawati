@extends('layouts.index')

@section('title', 'Data Pengiriman')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Tabel Daftar Pengiriman</h4>
                        {{-- <div class="card-header-form">
                            <button class="btn btn-primary float-end" data-toggle="modal" data-target="#addShipmentModal">
                                <i class="fas fa-plus"></i> <span>Tambah Pengiriman</span>
                            </button>
                        </div> --}}

                        <div class="card-header-form d-flex justify-content-end">
                            <a href="{{ route('shipments.laporan') }}" class="btn btn-info me-2">
                                <i class="fas fa-chart-bar"></i> Laporan Pengiriman
                            </a>

                            <button class="btn btn-primary" data-toggle="modal" data-target="#addShipmentModal">
                                <i class="fas fa-plus"></i> Tambah Pengiriman
                            </button>
                        </div>

                    </div>
                    <div class="card-body p-0">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <div class="row mb-3 px-3">
                            <div class="col-md-6">
                                <form action="{{ route('shipments.index') }}" method="GET" class="d-flex">
                                    <input class="form-control me-2" type="search" name="keyword"
                                        placeholder="Cari No. / Tujuan" value="{{ request('keyword') }}">
                                    <button class="btn btn-primary" type="submit">Cari</button>
                                </form>
                            </div>

                            <div class="col-md-6">
                                <form action="{{ route('shipments.index') }}" method="GET" class="d-flex">
                                    <select name="jenis_kendaraan" class="form-control me-2">
                                        <option value="">-- Jenis Kendaraan --</option>
                                        @foreach ($jenisKendaraan as $jenis)
                                            <option value="{{ $jenis }}"
                                                {{ request('jenis_kendaraan') == $jenis ? 'selected' : '' }}>
                                                {{ ucfirst($jenis) }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <select name="status_ketersediaan" class="form-control me-2">
                                        <option value="">-- Status Armada --</option>
                                        @foreach ($statusKetersediaan as $status)
                                            <option value="{{ $status }}"
                                                {{ request('status_ketersediaan') == $status ? 'selected' : '' }}>
                                                {{ ucfirst($status) }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <button class="btn btn-secondary" type="submit">Filter</button>
                                </form>
                            </div>
                        </div>
                        <br>

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <tr>
                                    <th>No.</th>
                                    <th>Nomor Pengiriman</th>
                                    <th>Tanggal</th>
                                    <th>Asal</th>
                                    <th>Tujuan</th>
                                    <th>Status</th>
                                    <th>Armada</th>
                                    <th>Barang</th>
                                    <th>Aksi</th>
                                </tr>
                                <tbody>
                                    @forelse ($shipments as $index => $shipment)
                                        <tr>
                                            <td>{{ $index + $shipments->firstItem() }}</td>
                                            <td>{{ $shipment->nomor_pengiriman }}</td>
                                            <td>{{ \Carbon\Carbon::parse($shipment->tanggal_pengiriman)->format('d-m-Y') }}
                                            </td>
                                            <td>{{ $shipment->lokasi_asal }}</td>
                                            <td>{{ $shipment->lokasi_tujuan }}</td>
                                            <td>
                                                <span
                                                    class="badge 
                                                    @if ($shipment->status == 'tertunda') badge-warning 
                                                    @elseif($shipment->status == 'dalam perjalanan') badge-primary 
                                                    @else badge-success @endif">
                                                    {{ ucfirst($shipment->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $shipment->armada->nomor_armada ?? '-' }}</td>
                                            <td>{{ $shipment->detail_barang }}</td>
                                            <td>
                                                <button class="btn btn-warning btn-sm action-btn" data-toggle="modal"
                                                    data-target="#editShipmentModal{{ $shipment->id }}">
                                                    <i class="fas fa-pen"></i>
                                                </button>

                                                <button class="btn btn-danger btn-sm" data-id="{{ $shipment->id }}"
                                                    onclick="confirmDelete({{ $shipment->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center">Data Tidak Ditemukan</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <div class="card-footer clearfix">
                                {{ $shipments->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Tambah Pengiriman -->
    <div class="modal fade" id="addShipmentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('shipments.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Pengiriman</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nomor Pengiriman</label>
                            <input type="text" name="nomor_pengiriman" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Pengiriman</label>
                            <input type="date" name="tanggal_pengiriman" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Lokasi Asal</label>
                            <input type="text" name="lokasi_asal" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Lokasi Tujuan</label>
                            <input type="text" name="lokasi_tujuan" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control" required>
                                <option value="tertunda">Tertunda</option>
                                <option value="dalam perjalanan">Dalam Perjalanan</option>
                                <option value="telah tiba">Telah Tiba</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Detail Barang</label>
                            <textarea name="detail_barang" class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Armada</label>
                            <select name="armada_id" class="form-control">
                                <option value="">Pilih Armada</option>
                                @foreach ($armadas as $armada)
                                    <option value="{{ $armada->id }}">{{ $armada->nomor_armada }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Pengiriman -->
    @foreach ($shipments as $shipment)
        <div class="modal fade" id="editShipmentModal{{ $shipment->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" action="{{ route('shipments.update', $shipment->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Pengiriman</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Nomor Pengiriman</label>
                                <input type="text" name="nomor_pengiriman" class="form-control"
                                    value="{{ $shipment->nomor_pengiriman }}" required>
                            </div>
                            <div class="form-group">
                                <label>Tanggal Pengiriman</label>
                                <input type="date" name="tanggal_pengiriman" class="form-control"
                                    value="{{ $shipment->tanggal_pengiriman }}" required>
                            </div>
                            <div class="form-group">
                                <label>Lokasi Asal</label>
                                <input type="text" name="lokasi_asal" class="form-control"
                                    value="{{ $shipment->lokasi_asal }}" required>
                            </div>
                            <div class="form-group">
                                <label>Lokasi Tujuan</label>
                                <input type="text" name="lokasi_tujuan" class="form-control"
                                    value="{{ $shipment->lokasi_tujuan }}" required>
                            </div>
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control" required>
                                    <option value="tertunda" {{ $shipment->status == 'tertunda' ? 'selected' : '' }}>
                                        Tertunda
                                    </option>
                                    <option value="dalam perjalanan"
                                        {{ $shipment->status == 'dalam perjalanan' ? 'selected' : '' }}>
                                        Dalam Perjalanan
                                    </option>
                                    <option value="telah tiba" {{ $shipment->status == 'telah tiba' ? 'selected' : '' }}>
                                        Telah Tiba
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Detail Barang</label>
                                <textarea name="detail_barang" class="form-control" required>{{ $shipment->detail_barang }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Armada</label>
                                <select name="armada_id" class="form-control">
                                    <option value="">Pilih Armada</option>
                                    @foreach ($armadas as $armada)
                                        <option value="{{ $armada->id }}"
                                            {{ $shipment->armada_id == $armada->id ? 'selected' : '' }}>
                                            {{ $armada->nomor_armada }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data pengiriman akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    var action = '{{ route('shipments.destroy', ':id') }}';
                    action = action.replace(':id', id);

                    var form = $('<form>', {
                        action: action,
                        method: 'POST'
                    }).append($('<input>', {
                        type: 'hidden',
                        name: '_token',
                        value: '{{ csrf_token() }}'
                    })).append($('<input>', {
                        type: 'hidden',
                        name: '_method',
                        value: 'DELETE'
                    }));

                    $('body').append(form);
                    form.submit();
                }
            })
        }
    </script>
@endsection
