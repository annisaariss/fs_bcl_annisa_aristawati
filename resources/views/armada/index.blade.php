@extends('layouts.index')

@section('title', 'Data Armada')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Tabel Daftar Armada</h4>
                        <div class="card-header-form">
                            <button class="btn btn-primary float-end" data-toggle="modal" data-target="#addArmadaModal">
                                <i class="fas fa-plus"></i> <span>Tambah Armada</span>
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

                        <div class="card-tools d-flex justify-content-end" style="margin-right: 20px;">
                            <form action="{{ route('armada.index') }}" method="GET" class="d-flex">
                                <input class="form-control me-2" type="search" name="keyword" placeholder="Cari Armada"
                                    value="{{ request('keyword') }}">
                                <button class="btn btn-primary" type="submit">Cari</button>
                            </form>
                        </div>
                        <br>

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <tr>
                                    <th>No.</th>
                                    <th>Nomor Armada</th>
                                    <th>Jenis Kendaraan</th>
                                    <th>Kapasitas Muatan</th>
                                    <th>Status Ketersediaan</th>
                                    <th>Tanggal dibuat</th>
                                    <th>Aksi</th>
                                </tr>
                                <tbody>
                                    @forelse ($armadas as $index => $armada)
                                        <tr>
                                            <td>{{ $index + $armadas->firstItem() }}</td>
                                            <td>{{ $armada->nomor_armada }}</td>
                                            <td>{{ $armada->jenis_kendaraan }}</td>
                                            <td>{{ $armada->kapasitas_muatan }} kg</td>
                                            <td>
                                                <span
                                                    class="badge {{ $armada->status_ketersediaan == 'tersedia' ? 'badge-success' : 'badge-danger' }}">
                                                    {{ ucfirst($armada->status_ketersediaan) }}
                                                </span>
                                            </td>
                                            <td>{{ $armada->created_at->format('d-m-Y') }}</td>
                                            <td>
                                                <button class="btn btn-warning btn-sm action-btn" data-toggle="modal"
                                                    data-target="#editArmadaModal{{ $armada->id }}">
                                                    <i class="fas fa-pen"></i>
                                                </button>

                                                <button class="btn btn-danger btn-sm" data-id="{{ $armada->id }}"
                                                    onclick="confirmDelete({{ $armada->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Data Tidak Ditemukan</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <div class="card-footer clearfix">
                                {{ $armadas->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Tambah Armada -->
    <div class="modal fade" id="addArmadaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('armada.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Armada</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nomor Armada</label>
                            <input type="text" name="nomor_armada" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Jenis Kendaraan</label>
                            <input type="text" name="jenis_kendaraan" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Kapasitas Muatan (kg)</label>
                            <input type="number" name="kapasitas_muatan" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Status Ketersediaan</label>
                            <select name="status_ketersediaan" class="form-control" required>
                                <option value="tersedia">Tersedia</option>
                                <option value="tidak tersedia">Tidak Tersedia</option>
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

    <!-- Modal Edit Armada -->
    @foreach ($armadas as $armada)
        <div class="modal fade" id="editArmadaModal{{ $armada->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" action="{{ route('armada.update', $armada->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Armada</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Nomor Armada</label>
                                <input type="text" name="nomor_armada" class="form-control"
                                    value="{{ $armada->nomor_armada }}" required>
                            </div>
                            <div class="form-group">
                                <label>Jenis Kendaraan</label>
                                <input type="text" name="jenis_kendaraan" class="form-control"
                                    value="{{ $armada->jenis_kendaraan }}" required>
                            </div>
                            <div class="form-group">
                                <label>Kapasitas Muatan (kg)</label>
                                <input type="number" name="kapasitas_muatan" class="form-control"
                                    value="{{ $armada->kapasitas_muatan }}" required>
                            </div>
                            <div class="form-group">
                                <label>Status Ketersediaan</label>
                                <select name="status_ketersediaan" class="form-control" required>
                                    <option value="tersedia"
                                        {{ $armada->status_ketersediaan == 'tersedia' ? 'selected' : '' }}>
                                        Tersedia
                                    </option>
                                    <option value="tidak tersedia"
                                        {{ $armada->status_ketersediaan == 'tidak tersedia' ? 'selected' : '' }}>
                                        Tidak Tersedia
                                    </option>
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
                text: "Data armada akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    var action = '{{ route('armada.destroy', ':id') }}';
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
