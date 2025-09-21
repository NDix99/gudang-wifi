@extends('layouts.landing.master', ['title' => 'Cart'])

@section('content')
    <div class="w-full py-6 px-4">
        <div class="container mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-10">
                <div class="md:col-span-8">
                    <div class="border rounded-lg overflow-hidden">
                        <div class="bg-white border-b px-4 py-2.5 text-gray-700 font-medium flex items-center justify-between">
                            <span>Keranjang anda</span>
                            @php
                                $totalItems = $carts->count();
                                $draftItems = $carts->where('status.value', 'Draft')->count();
                                $pendingItems = $carts->where('status.value', 'Menunggu Persetujuan')->count();
                                $rejectedItems = $carts->where('status.value', 'Ditolak')->count();
                                $outOfStockItems = $carts->where('status.value', 'Stok Kosong')->count();
                            @endphp
                            @if($totalItems > 0)
                                <div class="flex items-center space-x-2 text-sm">
                                    @if($draftItems > 0)
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs">
                                            📝 {{ $draftItems }} Draft
                                        </span>
                                    @endif
                                    @if($pendingItems > 0)
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs">
                                            ⏳ {{ $pendingItems }} Pending
                                        </span>
                                    @endif
                                    @if($rejectedItems > 0)
                                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs">
                                            ❌ {{ $rejectedItems }} Ditolak
                                        </span>
                                    @endif
                                    @if($outOfStockItems > 0)
                                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs">
                                            ⚠️ {{ $outOfStockItems }} Kosong
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                        <div class="overflow-x-auto relative">
                            <table class="w-full text-sm text-left text-gray-500 divide-y divide-gray-200">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-4 py-3 w-0">#</th>
                                        <th scope="col" class="px-4 py-3">Nama Barang</th>
                                        <th scope="col" class="px-4 py-3 text-right">Jumlah</th>
                                        <th scope="col" class="px-4 py-3">Status</th>
                                        <th scope="col" class="px-4 py-3">Catatan Admin</th>
                                        <th scope="col" class="px-4 py-3 w-0">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @forelse ($carts as $i=>$cart)
                                        <tr>
                                            <td class="py-3 px-4 whitespace-nowrap">
                                                <a href="#" class="text-rose-600"
                                                    onclick="deleteData({{ $cart->id }})">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="icon icon-tabler icon-tabler-eraser" width="24"
                                                        height="24" viewBox="0 0 24 24" stroke-width="1.25"
                                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                        </path>
                                                        <path
                                                            d="M19 20h-10.5l-4.21 -4.3a1 1 0 0 1 0 -1.41l10 -10a1 1 0 0 1 1.41 0l5 5a1 1 0 0 1 0 1.41l-9.2 9.3">
                                                        </path>
                                                        <path d="M18 13.3l-6.3 -6.3"></path>
                                                    </svg>
                                                </a>
                                                <form id="delete-form-{{ $cart->id }}"
                                                    action="{{ route('cart.destroy', $cart->id) }}" method="POST"
                                                    style="display:none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </td>
                                            <td class="py-3 px-4 whitespace-nowrap">
                                                {{ $cart->product->name }}</td>
                                            <td class="py-3 px-4 whitespace-nowrap text-right">
                                                {{ $cart->quantity }} (qty)
                                            </td>
                                            <td class="py-3 px-4 whitespace-nowrap">
                                                @switch($cart->status->value)
                                                    @case('Draft')
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            <i class="fas fa-edit mr-1"></i>
                                                            Draft
                                                        </span>
                                                        @break
                                                    @case('Menunggu Persetujuan')
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            <i class="fas fa-clock mr-1"></i>
                                                            Pending
                                                        </span>
                                                        @break
                                                    @case('Disetujui')
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            <i class="fas fa-check mr-1"></i>
                                                            OK
                                                        </span>
                                                        @break
                                                    @case('Ditolak')
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                            <i class="fas fa-times mr-1"></i>
                                                            Ditolak
                                                        </span>
                                                        @break
                                                    @case('Stok Kosong')
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                            <i class="fas fa-exclamation mr-1"></i>
                                                            Kosong
                                                        </span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td class="py-3 px-4 whitespace-nowrap">
                                                @if($cart->admin_note)
                                                    <span class="text-sm text-gray-600">{{ $cart->admin_note }}</span>
                                                @else
                                                    <span class="text-sm text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="py-3 px-4 whitespace-nowrap text-right flex gap-2">
                                                @if($cart->status->value === 'Draft' || $cart->status->value === 'Menunggu Persetujuan' || $cart->status->value === 'Stok Kosong')
                                                    <form action="{{ route('cart.update', $cart->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <input
                                                            class="w-16 border px-2 py-0.5 rounded focus:outline-none focus:ring-2 focus:ring-sky-600"
                                                            value="{{ $cart->quantity }}" type="number" name="quantity" />
                                                    </form>
                                                @else
                                                    <span class="text-sm text-gray-500">Tidak dapat diubah</span>
                                                @endif
                                            </td>
                                        @empty
                                            <td class="py-3 px-4 whitespace-nowrap" colSpan="6">
                                                <div class="flex items-center justify-center h-96">
                                                    <div class="text-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-shopping-cart inline"
                                                            width="32" height="32" viewBox="0 0 24 24"
                                                            stroke-width="2" stroke="currentColor" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                            <circle cx="6" cy="19" r="2">
                                                            </circle>
                                                            <circle cx="17" cy="19" r="2">
                                                            </circle>
                                                            <path d="M17 17h-11v-14h-2"></path>
                                                            <path d="M6 5l14 1l-1 7h-13"></path>
                                                        </svg>
                                                        <div class="mt-5 text-gray-600">
                                                            Keranjang Anda Kosong
                                                        </div>
                                                        <div class="mt-2 text-sm text-gray-500">
                                                            Silakan tambahkan produk ke keranjang untuk memulai pemesanan
                                                        </div>
                                                        <div class="mt-4">
                                                            <a href="{{ route('product.index') }}" 
                                                               class="inline-flex items-center px-4 py-2 bg-sky-600 text-white text-sm font-medium rounded-lg hover:bg-sky-700 transition-colors duration-200">
                                                                <i class="fas fa-shopping-bag mr-2"></i>
                                                                Lihat Produk
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                    <tr class="bg-blue-50 text-blue-900 font-semibold">
                                        <td class="py-3 px-4 whitespace-nowrap"></td>
                                        <td class="py-3 px-4 whitespace-nowrap">Total</td>
                                        <td class="py-3 px-4 whitespace-nowrap text-right text-teal-500">
                                            {{ $grandQuantity }} (Qty)
                                        </td>
                                        <td class="py-3 px-4 whitespace-nowrap"></td>
                                        <td class="py-3 px-4 whitespace-nowrap"></td>
                                        <td class="py-3 px-4 whitespace-nowrap"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        @php
                            $draftItems = $carts->where('status.value', 'Draft')->count();
                            $readyToOrderItems = $draftItems;
                        @endphp
                        
                        @if($readyToOrderItems > 0)
                            <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                                <div class="text-center">
                                    <p class="text-sm text-green-800 mb-3">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        {{ $readyToOrderItems }} item siap diorder!
                                    </p>
                                    <div class="flex gap-2 justify-center">
                                        <a href="#order-section" class="inline-flex items-center px-6 py-2 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition-colors duration-200">
                                            <i class="fas fa-shopping-cart mr-2"></i>
                                            ORDER SEKARANG
                                        </a>
                                        <form action="{{ route('cart.submit-to-admin') }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-6 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                                <i class="fas fa-paper-plane mr-2"></i>
                                                KIRIM KE ADMIN
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="md:col-span-4" x-data="{ open: false }" id="order-section">
                    @if($carts->count() > 0)
                        <form action="{{ route('transaction.store') }}" method="POST">
                            @csrf
                            <div class="border rounded-lg overflow-hidden">
                                <div class="bg-white border-b px-4 py-2.5 text-gray-700 font-medium flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-file-invoice mr-1" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                                        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z">
                                        </path>
                                        <line x1="9" y1="7" x2="10" y2="7"></line>
                                        <line x1="9" y1="13" x2="15" y2="13"></line>
                                        <line x1="13" y1="17" x2="15" y2="17"></line>
                                    </svg>
                                    Konfirmasi Pesanan
                                </div>
                            <div class="p-4 bg-white">
                                <div class="flex flex-col gap-4">
                                    <div class="flex flex-col gap-y-2">
                                        <label class="text-sm text-gray-700">
                                            Nama Lengkap
                                        </label>
                                        <input type="text"
                                            class="rounded-lg border p-2 text-sm text-gray-700 focus:outline-none bg-gray-200 cursor-not-allowed"
                                            placeholder="Rafi Taufiqurrahman" value="{{ Auth::user()->name }}"
                                            name="name" readonly />
                                    </div>
                                    <div class="flex flex-col gap-y-2">
                                        <label class="text-sm text-gray-700">
                                            Email
                                        </label>
                                        <input type="email"
                                            class="rounded-lg border p-2 text-sm text-gray-700 focus:outline-none bg-gray-200 cursor-not-allowed"
                                            placeholder="" value="{{ Auth::user()->email }}" name="email" readonly />
                                    </div>
                                    <div class="flex flex-col gap-y-2">
                                        <label class="text-sm text-gray-700">
                                            Divisi
                                        </label>
                                        <input type="email"
                                            class="rounded-lg border p-2 text-sm text-gray-700 focus:outline-none bg-gray-200 cursor-not-allowed"
                                            placeholder="" value="{{ Auth::user()->department }}" name="department"
                                            readonly />
                                    </div>
                                    <div class="flex flex-col gap-y-2">
                                        <label class="text-sm text-gray-700">
                                            Total Barang
                                        </label>
                                        <input type="text"
                                            class="rounded-lg border p-2 text-sm text-gray-700 focus:outline-none bg-gray-200 cursor-not-allowed"
                                            placeholder="" name="grand_total"
                                            value="{{ $carts->count() }} ({{ $grandQuantity }} Qty)" readonly />
                                    </div>
                                    
                                    @php
                                        $readyToOrderCarts = $carts->where('status.value', 'Draft');
                                    @endphp
                                    
                                    @if($readyToOrderCarts->count() > 0)
                                        <div class="flex flex-col gap-y-2">
                                            <label class="text-sm text-gray-700">
                                                Item yang akan diorder
                                            </label>
                                            <div class="max-h-32 overflow-y-auto border rounded-lg p-2 bg-gray-50">
                                                @foreach($readyToOrderCarts as $cart)
                                                    <div class="flex justify-between items-center text-sm py-1 border-b border-gray-200 last:border-b-0">
                                                        <span class="text-gray-700">{{ $cart->product->name }}</span>
                                                        <span class="text-gray-500">{{ $cart->quantity }} qty</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="text-xs text-gray-500 text-center mt-2">
                                                <i class="fas fa-info-circle mr-1"></i>
                                                Total {{ $readyToOrderCarts->count() }} item, {{ $readyToOrderCarts->sum('quantity') }} qty
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="my-3">
                            @php
                                $draftItems = $carts->where('status.value', 'Draft')->count();
                                $readyToOrderItems = $draftItems; // Hanya Draft yang bisa diorder langsung
                                $pendingItems = $carts->where('status.value', 'Menunggu Persetujuan')->count();
                                $rejectedItems = $carts->where('status.value', 'Ditolak')->count();
                                $outOfStockItems = $carts->where('status.value', 'Stok Kosong')->count();
                            @endphp
                            
                            @if($readyToOrderItems > 0)
                                <button class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition-colors duration-200 shadow-lg" type="submit">
                                    <i class="fas fa-shopping-cart mr-2"></i>
                                    ORDER SEKARANG
                                </button>
                                <div class="mt-2 text-center">
                                    <span class="text-sm text-green-600 font-medium">{{ $readyToOrderItems }} item siap diorder</span>
                                </div>
                            @else
                                <button class="w-full bg-gray-400 text-white font-bold py-3 px-4 rounded-lg cursor-not-allowed" type="button" disabled>
                                    @if($pendingItems > 0)
                                        <i class="fas fa-clock mr-2"></i>
                                        MENUNGGU ADMIN ({{ $pendingItems }} item)
                                    @elseif($rejectedItems > 0)
                                        <i class="fas fa-times mr-2"></i>
                                        ADA ITEM DITOLAK ({{ $rejectedItems }} item)
                                    @elseif($outOfStockItems > 0)
                                        <i class="fas fa-exclamation mr-2"></i>
                                        STOK KOSONG ({{ $outOfStockItems }} item)
                                    @else
                                        <i class="fas fa-shopping-cart mr-2"></i>
                                        TIDAK ADA ITEM
                                    @endif
                                </button>
                                @if($pendingItems > 0)
                                    <div class="mt-2 text-center">
                                        <span class="text-sm text-yellow-600">⏳ Admin sedang memproses {{ $pendingItems }} item</span>
                                    </div>
                                @elseif($rejectedItems > 0)
                                    <div class="mt-2 text-center">
                                        <span class="text-sm text-red-600">❌ {{ $rejectedItems }} item ditolak, cek catatan admin</span>
                                    </div>
                                @elseif($outOfStockItems > 0)
                                    <div class="mt-2 text-center">
                                        <span class="text-sm text-red-600">⚠️ {{ $outOfStockItems }} item stok kosong</span>
                                    </div>
                                @endif
                            @endif
                        </form>
                    @else
                        <div class="border rounded-lg overflow-hidden">
                            <div class="bg-white border-b px-4 py-2.5 text-gray-700 font-medium flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="icon icon-tabler icon-tabler-file-invoice mr-1" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                                    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z">
                                    </path>
                                    <line x1="9" y1="7" x2="10" y2="7"></line>
                                    <line x1="9" y1="13" x2="15" y2="13"></line>
                                    <line x1="13" y1="17" x2="15" y2="17"></line>
                                </svg>
                                Konfirmasi Pesanan
                            </div>
                            <div class="p-4 bg-white text-center">
                                <div class="text-gray-500 mb-4">
                                    <i class="fas fa-shopping-cart text-4xl mb-2"></i>
                                    <p class="text-sm">Keranjang Anda kosong</p>
                                </div>
                                <a href="{{ route('product.index') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-sky-600 text-white text-sm font-medium rounded-lg hover:bg-sky-700 transition-colors duration-200">
                                    <i class="fas fa-shopping-bag mr-2"></i>
                                    Lihat Produk
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
@endpush
