@extends('layouts.master', ['title' => 'Persetujuan Keranjang'])

@section('content')
    <x-container>
        <div class="col-12">
            <x-card title="DAFTAR PERMINTAAN BARANG DARI CUSTOMER" class="card-body p-0">
                @if($carts->count() > 0)
                    <x-table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Customer</th>
                                <th>Produk</th>
                                <th>Jumlah</th>
                                <th>Stok Tersedia</th>
                                <th>Status</th>
                                <th>Catatan Admin</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($carts as $cart)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $cart->user->name }}</td>
                                    <td>{{ $cart->product->name }}</td>
                                    <td>{{ $cart->quantity }}</td>
                                    <td>
                                        <span class="badge {{ $cart->product->quantity >= $cart->quantity ? 'badge-success' : 'badge-danger' }}">
                                            {{ $cart->product->quantity }}
                                        </span>
                                    </td>
                                    <td>
                                        @switch($cart->status->value)
                                            @case('Menunggu Persetujuan')
                                                <span class="badge badge-warning">{{ $cart->status->value }}</span>
                                                @break
                                            @case('Disetujui')
                                                <span class="badge badge-success">{{ $cart->status->value }}</span>
                                                @break
                                            @case('Ditolak')
                                                <span class="badge badge-danger">{{ $cart->status->value }}</span>
                                                @break
                                            @case('Stok Kosong')
                                                <span class="badge badge-danger">{{ $cart->status->value }}</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        @if($cart->admin_note)
                                            <small class="text-muted">{{ $cart->admin_note }}</small>
                                        @else
                                            <small class="text-muted">-</small>
                                        @endif
                                    </td>
                                    <td>{{ $cart->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @if($cart->status->value === 'Menunggu Persetujuan' || $cart->status->value === 'Stok Kosong')
                                            <div class="btn-group" role="group">
                                                <form action="{{ route('admin.cart-approval.approve', $cart) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm" 
                                                            onclick="return confirm('Setujui permintaan ini?')">
                                                        <i class="fas fa-check"></i> Setujui
                                                    </button>
                                                </form>
                                                
                                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" 
                                                        data-target="#rejectModal{{ $cart->id }}">
                                                    <i class="fas fa-times"></i> Tolak
                                                </button>
                                                
                                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal" 
                                                        data-target="#noteModal{{ $cart->id }}">
                                                    <i class="fas fa-edit"></i> Catatan
                                                </button>
                                            </div>
                                        @else
                                            <span class="text-muted">Sudah diproses</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </x-table>
                @else
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle"></i> Tidak ada permintaan barang yang menunggu persetujuan
                    </div>
                @endif
            </x-card>
        </div>
    </x-container>

<!-- Modal Reject -->
@foreach($carts as $cart)
<div class="modal fade" id="rejectModal{{ $cart->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.cart-approval.reject', $cart) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tolak Permintaan</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Alasan penolakan untuk <strong>{{ $cart->product->name }}</strong> dari <strong>{{ $cart->user->name }}</strong>:</p>
                    <div class="form-group">
                        <label for="admin_note{{ $cart->id }}">Catatan Admin:</label>
                        <textarea class="form-control" id="admin_note{{ $cart->id }}" name="admin_note" 
                                  rows="3" required placeholder="Masukkan alasan penolakan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak Permintaan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Note -->
<div class="modal fade" id="noteModal{{ $cart->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.cart-approval.update-note', $cart) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Update Catatan Admin</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Catatan untuk <strong>{{ $cart->product->name }}</strong> dari <strong>{{ $cart->user->name }}</strong>:</p>
                    <div class="form-group">
                        <label for="admin_note_update{{ $cart->id }}">Catatan Admin:</label>
                        <textarea class="form-control" id="admin_note_update{{ $cart->id }}" name="admin_note" 
                                  rows="3" required>{{ $cart->admin_note }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update Catatan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection
