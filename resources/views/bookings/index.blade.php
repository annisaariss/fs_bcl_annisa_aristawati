@extends('layouts.index')

@section('title', 'Data Booking Armada')

@section('content')
<section class="section">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Tabel Daftar Booking</h4>
                    <div class="card-header-form">
                        <button class="btn btn-primary float-end" data-toggle="modal" data-target="#addBookingModal">
                            <i class="fas fa-plus"></i> <span>Tambah Booking</span>
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

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <tr>
                                <th>No.</th>
                                <th>Armada</th>
                                <th>Tanggal Pemesanan</th>
                                <th>Detail Barang</th>
                                <th>Aksi</th>
                            </tr>
                            <tbody>
                                @forelse ($bookings as $index => $booking)
                                    <tr>
                                        <td>{{ $index + $bookings->firstItem() }}</td>
                                        <td>{{ $booking->armada->nomor_armada ?? '-' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($booking->tanggal_pemesanan)->format('d-m-Y') }}</td>
                                        <td>{{ $booking->detail_barang }}</td>
                                        <td>
                                            <button class="btn btn-danger btn-sm"
                                                onclick="confirmDelete({{ $booking->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Data Tidak Ditemukan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="card-footer clearfix">
                            {{ $bookings->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Tambah Booking -->
<div class="modal fade" id="addBookingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('bookings.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Booking</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Pilih Armada</label>
                        <select name="armada_id" class="form-control" required>
                            <option value="">-- Pilih Armada --</option>
                            @foreach ($armadas as $armada)
                                <option value="{{ $armada->id }}">{{ $armada->nomor_armada }} - {{ $armada->jenis_kendaraan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Pemesanan</label>
                        <input type="date" name="tanggal_pemesanan" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Detail Barang</label>
                        <textarea name="detail_barang" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Booking akan dibatalkan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                var action = '{{ route('bookings.destroy', ':id') }}';
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
