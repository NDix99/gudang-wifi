@extends('layouts.master', ['title' => 'Histori Stok'])

@section('content')
    <x-container>
        <div class="col-12">
            <x-card title="DAFTAR BARANG MASUK" class="card-body p-0">
                <x-table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Produk</th>
                            <th>Perubahan</th>
                            <th>Sebelum</th>
                            <th>Sesudah</th>
                            <th>Catatan</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($histories as $i => $h)
                            <tr>
                                <td>{{ $i + $histories->firstItem() }}</td>
                                <td>{{ $h->product?->name }}</td>
                                <td>{{ $h->quantity_change }}</td>
                                <td>{{ $h->quantity_before }}</td>
                                <td>{{ $h->quantity_after }}</td>
                                <td>{{ $h->note }}</td>
                                <td>{{ $h->created_at->format('d/m/Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </x-table>
                <div class="p-3">
                    {{ $histories->links() }}
                </div>
            </x-card>
        </div>
    </x-container>
@endsection


