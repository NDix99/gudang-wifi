@extends('layouts.landing.master', ['title' => 'Detail Pesanan'])

@section('content')
    <div class="w-full py-6 px-4">
        <div class="container mx-auto max-w-4xl">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">Detail Pengajuan</h1>
                        <p class="text-gray-600">Informasi lengkap pesanan Anda</p>
                    </div>
                    <a href="{{ route('order-tracking.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>

            <!-- Order Details -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Informasi Pengajuan</h2>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Order Info -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-4">Detail Pengajuan</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Nama Barang</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $cart->product->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Jumlah</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $cart->quantity }} qty</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tanggal Pengajuan</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $cart->created_at->format('d M Y H:i') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Terakhir Diupdate</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $cart->updated_at->format('d M Y H:i') }}</dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Status Info -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-4">Status Pengajuan</h3>
                            <div class="space-y-4">
                                <!-- Current Status -->
                                <div class="text-center">
                                    @switch($cart->status->value)
                                        @case('Draft')
                                            <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                <i class="fas fa-edit mr-2"></i>
                                                Draft
                                            </div>
                                            <p class="mt-2 text-sm text-gray-600">Item masih bisa diedit</p>
                                            @break
                                        @case('Menunggu Persetujuan')
                                            <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-clock mr-2"></i>
                                                Menunggu Persetujuan
                                            </div>
                                            <p class="mt-2 text-sm text-gray-600">Pengajuan Anda menunggu persetujuan admin</p>
                                            @break
                                        @case('Disetujui')
                                            <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                                <i class="fas fa-check-circle mr-2"></i>
                                                Disetujui
                                            </div>
                                            <p class="mt-2 text-sm text-gray-600">Admin telah menyetujui pengajuan</p>
                                            @break
                                        @case('Ditolak')
                                            <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-times mr-2"></i>
                                                Ditolak
                                            </div>
                                            <p class="mt-2 text-sm text-gray-600">Pengajuan ditolak. Cek catatan admin di halaman keranjang.</p>
                                            @break
                                        @case('Stok Kosong')
                                            <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-exclamation mr-2"></i>
                                                Stok Kosong
                                            </div>
                                            <p class="mt-2 text-sm text-gray-600">Stok barang sedang kosong</p>
                                            @break
                                    @endswitch
                                </div>

                                <!-- Progress Steps -->
                                <div class="mt-6">
                                    <h4 class="text-sm font-medium text-gray-700 mb-3">Progress Pesanan</h4>
                                    <div class="space-y-3">
                                        <!-- Step 1: Draft -->
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                @if(in_array($cart->status->value, ['Draft','Menunggu Persetujuan','Disetujui','Ditolak','Stok Kosong']))
                                                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                                        <i class="fas fa-edit text-white text-sm"></i>
                                                    </div>
                                                @else
                                                    <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                                        <i class="fas fa-edit text-gray-500 text-sm"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">Draft</p>
                                                <p class="text-xs text-gray-500">Item masih bisa diedit</p>
                                            </div>
                                        </div>

                                        <!-- Step 2: Pending -->
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                @if(in_array($cart->status->value, ['Menunggu Persetujuan','Disetujui','Ditolak','Stok Kosong']))
                                                    <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                                                        <i class="fas fa-clock text-white text-sm"></i>
                                                    </div>
                                                @else
                                                    <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                                        <i class="fas fa-clock text-gray-500 text-sm"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">Menunggu Persetujuan</p>
                                                <p class="text-xs text-gray-500">Menunggu persetujuan admin</p>
                                            </div>
                                        </div>

                                        <!-- Step 3: Approved -->
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                @if(in_array($cart->status->value, ['Disetujui']))
                                                    <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                                                        <i class="fas fa-check-circle text-white text-sm"></i>
                                                    </div>
                                                @else
                                                    <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                                        <i class="fas fa-check-circle text-gray-500 text-sm"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">Disetujui</p>
                                                <p class="text-xs text-gray-500">Admin menyetujui pengajuan</p>
                                            </div>
                                        </div>

                                        <!-- Step 4: Rejected/Out of Stock -->
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                @if(in_array($cart->status->value, ['Ditolak','Stok Kosong']))
                                                    <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                                                        <i class="fas fa-times text-white text-sm"></i>
                                                    </div>
                                                @else
                                                    <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                                        <i class="fas fa-times text-gray-500 text-sm"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">Ditolak/Stok Kosong</p>
                                                <p class="text-xs text-gray-500">Tindak lanjuti sesuai catatan admin</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Info -->
            @isset($cart->admin_note)
                <div class="mt-6 bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Catatan Admin</h2>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-gray-700">{{ $cart->admin_note }}</p>
                    </div>
                </div>
            @endisset
        </div>
    </div>
@endsection

@push('js')
@endpush


