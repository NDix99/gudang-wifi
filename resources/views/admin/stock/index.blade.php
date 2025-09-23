@extends('layouts.master', ['title' => 'Stok'])

@section('content')
    <x-container>
        <div class="col-12">
            <form method="GET" action="{{ route('admin.stock.index') }}" class="row g-2 mb-3">
                <div class="col-md-6">
                    <x-input name="search" type="text" title="Cari Produk" placeholder="Cari nama, satuan, supplier, kategori" :value="request('search')" />
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <div>
                        <x-button-save title="Cari" icon="search" class="btn btn-primary me-2" />
                        @if(request('search'))
                            <a href="{{ route('admin.stock.index') }}" class="btn btn-secondary">Reset</a>
                        @endif
                    </div>
                </div>
            </form>
            @can('create-product')
                <x-button-link title="Tambah Produk" icon="plus" class="btn btn-primary mb-3" style="mr-1" :url="route('admin.product.create')" />
            @endcan
            <x-card title="DAFTAR PRODUK" class="card-body p-0">
                <x-table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Foto</th>
                            <th>Nama Produk</th>
                            <th>Nama Supplier</th>
                            <th>Kategori Produk</th>
                            <th>Satuan</th>
                            <th>Stok</th>
                            <th>Min. Stok</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $i => $product)
                            <tr>
                                <td>{{ $i + $products->firstItem() }}</td>
                                <td>
                                    <span class="avatar rounded avatar-md"
                                        style="background-image: url({{ $product->image }})"></span>
                                </td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->supplier->name }}</td>
                                <td>{{ $product->category->name }}</td>
                                <td>{{ $product->unit }}</td>
                                <td>
                                    <span class="badge bg-primary">{{ $product->quantity }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $product->minimum_stock }}</span>
                                </td>
                                <td>
                                    @if($product->quantity <= 0)
                                        <span class="badge bg-danger">Habis</span>
                                    @elseif($product->isStockBelowMinimum())
                                        <span class="badge bg-warning">Rendah</span>
                                    @else
                                        <span class="badge bg-success">Aman</span>
                                    @endif
                                </td>
                                <td>
                                    <x-button-modal :id="$product->id" icon="plus" style="mr-1" title="Stok"
                                        class="btn bg-teal btn-sm text-white" />
                                    <x-modal :id="$product->id" title="Tambah Stok Produk - {{ $product->name }}">
                                        <form action="{{ route('admin.stock.update', $product->id) }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <x-input title="Tambah Stok" name="quantity" type="number"
                                                placeholder="Masukkan jumlah yang ingin ditambahkan" :value="old('quantity', 1)" min="1" />
                                            <x-button-save title="Simpan" icon="save" class="btn btn-primary" />
                                        </form>
                                    </x-modal>
                                    
                                    <x-button-modal :id="'min_' . $product->id" icon="settings" style="mr-1" title="Min. Stok"
                                        class="btn bg-orange btn-sm text-white" />
                                    <x-modal :id="'min_' . $product->id" title="Atur Minimum Stok - {{ $product->name }}">
                                        <form action="{{ route('admin.stock.update-minimum', $product->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <x-input title="Minimum Stok" name="minimum_stock" type="number"
                                                placeholder="Minimum Stok" :value="$product->minimum_stock" min="0" />
                                            <x-button-save title="Simpan" icon="save" class="btn btn-primary" />
                                        </form>
                                    </x-modal>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </x-table>
            </x-card>
        </div>
    </x-container>
@endsection
