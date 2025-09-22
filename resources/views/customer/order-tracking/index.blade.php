@extends('layouts.landing.master', ['title' => 'Tracking Pesanan'])

@section('content')
    <div class="w-full py-6 px-4">
        <div class="container mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Tracking Pesanan</h1>
                <p class="text-gray-600">Lacak status pesanan Anda di sini</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-shopping-cart text-blue-500 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Pesanan</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clock text-yellow-500 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Menunggu</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['pending'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-purple-500 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Disetujui</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['approved'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-times-circle text-red-500 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Ditolak</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['rejected'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-gray-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-gray-500 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Stok Kosong</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['out_of_stock'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Orders List -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Daftar Pesanan</h2>
                </div>

                @if($orders->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Barang</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($orders as $index => $order)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $orders->firstItem() + $index }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $order['name'] ?? ($order->name ?? '-') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $order['quantity'] ?? $order->quantity }} {{ $order['unit'] ?? ($order->unit ?? 'qty') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusValue = is_array($order) ? $order['status'] : $order->status->value;
                                            @endphp
                                            @switch($statusValue)
                                                @case('Draft')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        <i class="fas fa-edit mr-1"></i>
                                                        Draft
                                                    </span>
                                                    @break
                                                @case('Menunggu Persetujuan')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        <i class="fas fa-clock mr-1"></i>
                                                        Menunggu
                                                    </span>
                                                    @break
                                                @case('Disetujui')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                        <i class="fas fa-check-circle mr-1"></i>
                                                        Disetujui
                                                    </span>
                                                    @break
                                                @case('Ditolak')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        <i class="fas fa-times mr-1"></i>
                                                        Ditolak
                                                    </span>
                                                    @break
                                                @case('Stok Kosong')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        <i class="fas fa-exclamation mr-1"></i>
                                                        Stok Kosong
                                                    </span>
                                                    @break
                                                @case('Permintaan Diterima')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                        <i class="fas fa-check-circle mr-1"></i>
                                                        Diterima
                                                    </span>
                                                    @break
                                                @case('Barang Telah Tersedia')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <i class="fas fa-box mr-1"></i>
                                                        Tersedia
                                                    </span>
                                                    @break
                                                @case('Permintaan Selesai')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        <i class="fas fa-check-double mr-1"></i>
                                                        Selesai
                                                    </span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ (is_array($order) ? $order['created_at'] : $order->created_at)->format('d M Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            @php
                                                $isCart = is_array($order) ? $order['type'] === 'cart' : isset($order->product);
                                            @endphp
                                            <a href="{{ $isCart ? route('order-tracking.show', $order['model'] ?? $order) : route('order-tracking.show-order', $order['model'] ?? $order) }}" 
                                               class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-eye mr-1"></i>
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $orders->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-shopping-cart text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Pesanan</h3>
                        <p class="text-gray-500 mb-6">Anda belum memiliki pesanan yang perlu dilacak.</p>
                        <a href="{{ route('product.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200">
                            <i class="fas fa-shopping-bag mr-2"></i>
                            Lihat Produk
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('js')
@endpush


