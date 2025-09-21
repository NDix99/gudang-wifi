@extends('layouts.landing.master', ['title' => 'Detail Pesanan'])

@section('content')
    <div class="w-full py-6 px-4">
        <div class="container mx-auto max-w-4xl">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">Detail Pesanan</h1>
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
                    <h2 class="text-lg font-semibold text-gray-900">Informasi Pesanan</h2>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Order Info -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-4">Detail Pesanan</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Nama Barang</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $order->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Jumlah</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $order->quantity }} {{ $order->unit }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tanggal Pesanan</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $order->created_at->format('d M Y H:i') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Terakhir Diupdate</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $order->updated_at->format('d M Y H:i') }}</dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Status Info -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-4">Status Pesanan</h3>
                            <div class="space-y-4">
                                <!-- Current Status -->
                                <div class="text-center">
                                    @switch($order->status->value)
                                        @case('Menunggu Konfirmasi')
                                            <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-clock mr-2"></i>
                                                Menunggu Konfirmasi
                                            </div>
                                            <p class="mt-2 text-sm text-gray-600">Pesanan Anda sedang menunggu konfirmasi dari admin</p>
                                            @break
                                        @case('Permintaan Diterima')
                                            <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                                <i class="fas fa-check-circle mr-2"></i>
                                                Permintaan Diterima
                                            </div>
                                            <p class="mt-2 text-sm text-gray-600">Admin telah menerima permintaan Anda</p>
                                            @break
                                        @case('Barang Telah Tersedia')
                                            <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-box mr-2"></i>
                                                Barang Telah Tersedia
                                            </div>
                                            <p class="mt-2 text-sm text-gray-600">Barang sudah tersedia dan siap diambil</p>
                                            @break
                                        @case('Permintaan Selesai')
                                            <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                                <i class="fas fa-check-double mr-2"></i>
                                                Permintaan Selesai
                                            </div>
                                            <p class="mt-2 text-sm text-gray-600">Pesanan telah selesai diproses</p>
                                            @break
                                    @endswitch
                                </div>

                                <!-- Progress Steps -->
                                <div class="mt-6">
                                    <h4 class="text-sm font-medium text-gray-700 mb-3">Progress Pesanan</h4>
                                    <div class="space-y-3">
                                        <!-- Step 1: Pending -->
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                @if(in_array($order->status->value, ['Menunggu Konfirmasi', 'Permintaan Diterima', 'Barang Telah Tersedia', 'Permintaan Selesai']))
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
                                                <p class="text-sm font-medium text-gray-900">Menunggu Konfirmasi</p>
                                                <p class="text-xs text-gray-500">Pesanan dikirim ke admin</p>
                                            </div>
                                        </div>

                                        <!-- Step 2: Verified -->
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                @if(in_array($order->status->value, ['Permintaan Diterima', 'Barang Telah Tersedia', 'Permintaan Selesai']))
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
                                                <p class="text-sm font-medium text-gray-900">Permintaan Diterima</p>
                                                <p class="text-xs text-gray-500">Admin menerima permintaan</p>
                                            </div>
                                        </div>

                                        <!-- Step 3: Success -->
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                @if(in_array($order->status->value, ['Barang Telah Tersedia', 'Permintaan Selesai']))
                                                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                                        <i class="fas fa-box text-white text-sm"></i>
                                                    </div>
                                                @else
                                                    <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                                        <i class="fas fa-box text-gray-500 text-sm"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">Barang Tersedia</p>
                                                <p class="text-xs text-gray-500">Barang siap diambil</p>
                                            </div>
                                        </div>

                                        <!-- Step 4: Done -->
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                @if($order->status->value === 'Permintaan Selesai')
                                                    <div class="w-8 h-8 bg-gray-500 rounded-full flex items-center justify-center">
                                                        <i class="fas fa-check-double text-white text-sm"></i>
                                                    </div>
                                                @else
                                                    <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                                        <i class="fas fa-check-double text-gray-500 text-sm"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">Selesai</p>
                                                <p class="text-xs text-gray-500">Pesanan selesai diproses</p>
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
            @if($order->image)
                <div class="mt-6 bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Gambar Pesanan</h2>
                    </div>
                    <div class="p-6">
                        <img src="{{ $order->image }}" alt="Order Image" class="max-w-md mx-auto rounded-lg">
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('js')
@endpush


