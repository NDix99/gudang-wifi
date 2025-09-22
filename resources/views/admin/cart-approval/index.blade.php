@extends('layouts.master', ['title' => 'Purchase Orders'])

@section('content')
    <x-container>
        <div class="col-12">
            <!-- Header Section -->
            <div class="mb-4">
                <h1 class="h2 text-dark mb-1">Purchase Orders</h1>
                <p class="text-muted mb-0">Kelola permintaan barang dari teknisi</p>
            </div>

            <!-- Tab Navigation -->
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="orderTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="all-tab" data-toggle="tab" href="#all" role="tab" aria-controls="all" aria-selected="true">
                                Semua
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="pending-tab" data-toggle="tab" href="#pending" role="tab" aria-controls="pending" aria-selected="false">
                                Menunggu Persetujuan
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="approved-tab" data-toggle="tab" href="#approved" role="tab" aria-controls="approved" aria-selected="false">
                                Disetujui
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="rejected-tab" data-toggle="tab" href="#rejected" role="tab" aria-controls="rejected" aria-selected="false">
                                Ditolak
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-0">
                    <div class="tab-content" id="orderTabsContent">
                        <!-- Tab Semua -->
                        <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                            <div class="table-responsive">
                                @if($carts->count() > 0)
                                    <table class="table table-hover mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th class="border-0">No</th>
                                                <th class="border-0">Customer</th>
                                                <th class="border-0">Produk</th>
                                                <th class="border-0">Jumlah</th>
                                                <th class="border-0">Stok Tersedia</th>
                                                <th class="border-0">Status</th>
                                                <th class="border-0">Catatan Admin</th>
                                                <th class="border-0">Tanggal</th>
                                                <th class="border-0">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($carts as $cart)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar avatar-sm bg-primary text-white rounded-circle mr-2">
                                                                {{ substr($cart->user->name, 0, 1) }}
                                                            </div>
                                                            <div>
                                                                <div class="font-weight-medium">{{ $cart->user->name }}</div>
                                                                <small class="text-muted">{{ $cart->user->email }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="{{ $cart->product->image }}" alt="{{ $cart->product->name }}" class="avatar avatar-sm mr-2">
                                                            <div>
                                                                <div class="font-weight-medium">{{ $cart->product->name }}</div>
                                                                <small class="text-muted">{{ $cart->product->category->name }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-outline">{{ $cart->quantity }} {{ $cart->product->unit }}</span>
                                                    </td>
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
                                                            <small class="text-muted">{{ Str::limit($cart->admin_note, 30) }}</small>
                                                        @else
                                                            <small class="text-muted">-</small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <small class="text-muted">{{ $cart->created_at->format('d/m/Y H:i') }}</small>
                                                    </td>
                                                    <td>
                                                        @if($cart->status->value === 'Menunggu Persetujuan' || $cart->status->value === 'Stok Kosong')
                                                            <div class="btn-group" role="group">
                                                                <form action="{{ route('admin.cart-approval.approve', $cart) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-success btn-sm" 
                                                                            onclick="return confirm('Setujui permintaan ini?')">
                                                                        <i class="fas fa-check"></i>
                                                                    </button>
                                                                </form>
                                                                
                                                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" 
                                                                        data-target="#rejectModal{{ $cart->id }}">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                                
                                                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal" 
                                                                        data-target="#noteModal{{ $cart->id }}">
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                            </div>
                                                        @else
                                                            <span class="text-muted">Sudah diproses</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div class="text-center py-5">
                                        <div class="empty-state">
                                            <div class="empty-state-icon">
                                                <i class="fas fa-shopping-cart text-muted" style="font-size: 3rem;"></i>
                                            </div>
                                            <h3 class="empty-state-title">Tidak ada permintaan</h3>
                                            <p class="empty-state-text text-muted">
                                                Belum ada permintaan barang yang perlu diproses.
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Tab Menunggu Persetujuan -->
                        <div class="tab-pane fade" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                            <div class="table-responsive">
                                @php
                                    $pendingCarts = $carts->where('status.value', 'Menunggu Persetujuan');
                                @endphp
                                @if($pendingCarts->count() > 0)
                                    <table class="table table-hover mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th class="border-0">No</th>
                                                <th class="border-0">Customer</th>
                                                <th class="border-0">Produk</th>
                                                <th class="border-0">Jumlah</th>
                                                <th class="border-0">Stok Tersedia</th>
                                                <th class="border-0">Tanggal</th>
                                                <th class="border-0">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pendingCarts as $cart)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar avatar-sm bg-warning text-white rounded-circle mr-2">
                                                                {{ substr($cart->user->name, 0, 1) }}
                                                            </div>
                                                            <div>
                                                                <div class="font-weight-medium">{{ $cart->user->name }}</div>
                                                                <small class="text-muted">{{ $cart->user->email }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="{{ $cart->product->image }}" alt="{{ $cart->product->name }}" class="avatar avatar-sm mr-2">
                                                            <div>
                                                                <div class="font-weight-medium">{{ $cart->product->name }}</div>
                                                                <small class="text-muted">{{ $cart->product->category->name }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-warning">{{ $cart->quantity }} {{ $cart->product->unit }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge {{ $cart->product->quantity >= $cart->quantity ? 'badge-success' : 'badge-danger' }}">
                                                            {{ $cart->product->quantity }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <small class="text-muted">{{ $cart->created_at->format('d/m/Y H:i') }}</small>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <form action="{{ route('admin.cart-approval.approve', $cart) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-success btn-sm" 
                                                                        onclick="return confirm('Setujui permintaan ini?')">
                                                                    <i class="fas fa-check"></i>
                                                                </button>
                                                            </form>
                                                            
                                                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" 
                                                                    data-target="#rejectModal{{ $cart->id }}">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                            
                                                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" 
                                                                    data-target="#noteModal{{ $cart->id }}">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div class="text-center py-5">
                                        <div class="empty-state">
                                            <div class="empty-state-icon">
                                                <i class="fas fa-clock text-muted" style="font-size: 3rem;"></i>
                                            </div>
                                            <h3 class="empty-state-title">Tidak ada yang menunggu</h3>
                                            <p class="empty-state-text text-muted">
                                                Tidak ada permintaan yang menunggu persetujuan.
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Tab Disetujui -->
                        <div class="tab-pane fade" id="approved" role="tabpanel" aria-labelledby="approved-tab">
                            <div class="table-responsive">
                                @php
                                    $approvedCarts = $carts->where('status.value', 'Disetujui');
                                @endphp
                                @if($approvedCarts->count() > 0)
                                    <table class="table table-hover mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th class="border-0">No</th>
                                                <th class="border-0">Customer</th>
                                                <th class="border-0">Produk</th>
                                                <th class="border-0">Jumlah</th>
                                                <th class="border-0">Tanggal Disetujui</th>
                                                <th class="border-0">Catatan Admin</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($approvedCarts as $cart)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar avatar-sm bg-success text-white rounded-circle mr-2">
                                                                {{ substr($cart->user->name, 0, 1) }}
                                                            </div>
                                                            <div>
                                                                <div class="font-weight-medium">{{ $cart->user->name }}</div>
                                                                <small class="text-muted">{{ $cart->user->email }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="{{ $cart->product->image }}" alt="{{ $cart->product->name }}" class="avatar avatar-sm mr-2">
                                                            <div>
                                                                <div class="font-weight-medium">{{ $cart->product->name }}</div>
                                                                <small class="text-muted">{{ $cart->product->category->name }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-success">{{ $cart->quantity }} {{ $cart->product->unit }}</span>
                                                    </td>
                                                    <td>
                                                        <small class="text-muted">{{ $cart->updated_at->format('d/m/Y H:i') }}</small>
                                                    </td>
                                                    <td>
                                                        @if($cart->admin_note)
                                                            <small class="text-muted">{{ Str::limit($cart->admin_note, 50) }}</small>
                                                        @else
                                                            <small class="text-muted">-</small>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div class="text-center py-5">
                                        <div class="empty-state">
                                            <div class="empty-state-icon">
                                                <i class="fas fa-check-circle text-muted" style="font-size: 3rem;"></i>
                                            </div>
                                            <h3 class="empty-state-title">Belum ada yang disetujui</h3>
                                            <p class="empty-state-text text-muted">
                                                Belum ada permintaan yang telah disetujui.
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Tab Ditolak -->
                        <div class="tab-pane fade" id="rejected" role="tabpanel" aria-labelledby="rejected-tab">
                            <div class="table-responsive">
                                @php
                                    $rejectedCarts = $carts->where('status.value', 'Ditolak');
                                @endphp
                                @if($rejectedCarts->count() > 0)
                                    <table class="table table-hover mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th class="border-0">No</th>
                                                <th class="border-0">Customer</th>
                                                <th class="border-0">Produk</th>
                                                <th class="border-0">Jumlah</th>
                                                <th class="border-0">Tanggal Ditolak</th>
                                                <th class="border-0">Alasan Penolakan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($rejectedCarts as $cart)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar avatar-sm bg-danger text-white rounded-circle mr-2">
                                                                {{ substr($cart->user->name, 0, 1) }}
                                                            </div>
                                                            <div>
                                                                <div class="font-weight-medium">{{ $cart->user->name }}</div>
                                                                <small class="text-muted">{{ $cart->user->email }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="{{ $cart->product->image }}" alt="{{ $cart->product->name }}" class="avatar avatar-sm mr-2">
                                                            <div>
                                                                <div class="font-weight-medium">{{ $cart->product->name }}</div>
                                                                <small class="text-muted">{{ $cart->product->category->name }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-danger">{{ $cart->quantity }} {{ $cart->product->unit }}</span>
                                                    </td>
                                                    <td>
                                                        <small class="text-muted">{{ $cart->updated_at->format('d/m/Y H:i') }}</small>
                                                    </td>
                                                    <td>
                                                        @if($cart->admin_note)
                                                            <small class="text-danger">{{ Str::limit($cart->admin_note, 50) }}</small>
                                                        @else
                                                            <small class="text-muted">-</small>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div class="text-center py-5">
                                        <div class="empty-state">
                                            <div class="empty-state-icon">
                                                <i class="fas fa-times-circle text-muted" style="font-size: 3rem;"></i>
                                            </div>
                                            <h3 class="empty-state-title">Belum ada yang ditolak</h3>
                                            <p class="empty-state-text text-muted">
                                                Belum ada permintaan yang ditolak.
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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

<style>
.empty-state {
    padding: 2rem;
}

.empty-state-icon {
    margin-bottom: 1rem;
}

.empty-state-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.empty-state-text {
    font-size: 0.875rem;
}

.avatar {
    width: 2rem;
    height: 2rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    font-size: 0.75rem;
    font-weight: 600;
}

.avatar-sm {
    width: 1.5rem;
    height: 1.5rem;
    font-size: 0.625rem;
}

.badge-outline {
    background-color: transparent;
    border: 1px solid #dee2e6;
    color: #6c757d;
}

.nav-tabs .nav-link {
    border: none;
    color: #6c757d;
    font-weight: 500;
}

.nav-tabs .nav-link.active {
    color: #6f42c1;
    background-color: transparent;
    border-bottom: 2px solid #6f42c1;
}

.nav-tabs .nav-link:hover {
    border: none;
    color: #6f42c1;
}
</style>
@endsection