@extends('layouts.landing.master', ['title' => 'Detail Pesanan'])

@section('content')
    <div class="w-full py-6 px-4">
        <div class="container mx-auto max-w-4xl">
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

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Informasi Pesanan</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-4">Status Pesanan</h3>
                            <div class="space-y-4">
                                <div class="text-center">
                                    @switch($order->status->value)
                                        @case('Menunggu Konfirmasi')
                                            <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-clock mr-2"></i>
                                                Menunggu Konfirmasi
                                            </div>
                                            @break
                                        @case('Permintaan Diterima')
                                            <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                                <i class="fas fa-check-circle mr-2"></i>
                                                Permintaan Diterima
                                            </div>
                                            @break
                                        @case('Barang Telah Tersedia')
                                            <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-box mr-2"></i>
                                                Barang Telah Tersedia
                                            </div>
                                            @break
                                        @case('Permintaan Selesai')
                                            <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                                <i class="fas fa-check-double mr-2"></i>
                                                Permintaan Selesai
                                            </div>
                                            @break
                                    @endswitch
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
@endpush


