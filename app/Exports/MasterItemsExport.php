<?php

namespace App\Exports;

use App\Models\MasterItem;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MasterItemsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Ambil semua data yang ingin diekspor
     */
    public function collection()
    {
        return MasterItem::with(['kategori'])->get();
    }

    /**
     * Mapping data untuk setiap row
     */
    public function map($item): array
    {
        static $no = 0;
        $no++;

        $namaKategori = $item->kategori && $item->kategori->count() > 0
            ? $item->kategori->pluck('nama')->implode(', ')
            : '-';

        return [
            $no,
            $namaKategori,
            $item->nama,
            $item->supplier ?? '-',
            $item->harga_beli,
            $item->laba,
            $item->harga_beli + $item->laba,
        ];
    }

    /**
     * Tambahkan header kolom
     */
    public function headings(): array
    {
        return [
            'No',
            'Nama Kategori',
            'Nama Items',
            'Nama Supplier',
            'Harga',
            'Laba',
            'Harga Jual'
        ];
    }
}
