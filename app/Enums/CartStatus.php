<?php

namespace App\Enums;

enum CartStatus : string {
    case Draft = 'Draft';
    case Pending = 'Menunggu Persetujuan';
    case Approved = 'Disetujui';
    case Rejected = 'Ditolak';
    case OutOfStock = 'Stok Kosong';
}
