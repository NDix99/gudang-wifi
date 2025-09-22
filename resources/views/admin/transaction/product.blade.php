@extends('layouts.master', ['title' => 'Barang Keluar'])

@section('content')
    <x-container>
        <div class="col-12">
            <x-card title="DAFTAR BARANG KELUAR" class="card-body p-0">
                <x-table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Customer</th>
                            <th>Nama Produk</th>
                            <th>Kategori Produk</th>
                            <th>Kuantitas</th>
                            <th>Tanggal & Waktu Keluar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stockHistories as $i => $history)
                            <tr>
                                <td>{{ $i + $stockHistories->firstItem() }}</td>
                                <td>{{ $history->user->name ?? 'System' }}</td>
                                <td>{{ $history->product->name }}</td>
                                <td>{{ $history->product->category->name }}</td>
                                <td>{{ abs($history->quantity_change) }} - {{ $history->product->unit }}</td>
                                <td>
                                    <div class="text-nowrap">
                                        <div class="font-weight-bold">
                                            {{ \Carbon\Carbon::parse($history->created_at)->format('d/m/Y') }}
                                        </div>
                                        <div class="text-muted small">
                                            {{ \Carbon\Carbon::parse($history->created_at)->format('H:i:s') }}
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="5" class="font-weight-bold text-uppercase">
                                Total Barang Keluar
                            </td>
                            <td class="font-weight-bold text-danger text-right">
                                {{ $grandQuantity }} Barang
                            </td>
                        </tr>
                    </tbody>
                </x-table>
            </x-card>
            <div class="d-flex justify-content-end">{{ $stockHistories->links() }}</div>
        </div>
    </x-container>
@endsection
