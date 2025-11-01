<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ProductsExport implements FromCollection, WithHeadings, WithEvents
{
    /**
     * Mengambil data dari tabel Product
     */
    public function collection()
    {
        return Product::all(['id', 'product_name', 'unit', 'type', 'information', 'qty', 'producer', 'created_at', 'updated_at']);
    }

    /**
     * Judul kolom header tabel
     */
    public function headings(): array
    {
        return [
            'ID',
            'Product Name',
            'Unit',
            'Type',
            'Information',
            'Quantity',
            'Producer',
            'Created At',
            'Updated At',
        ];
    }

    /**
     * Event styling dan penambahan judul di atas tabel
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                // Tambah 3 baris kosong di atas header tabel
                $sheet->insertNewRowBefore(1, 3);

                // Judul perusahaan dan laporan
                $sheet->setCellValue('A1', 'PT Mahkota Indah Jaya');
                $sheet->setCellValue('A2', 'Rekap Stock Produk Gudang');
                $sheet->setCellValue('A3', 'Tanggal: ' . date('d M Y'));

                // Merge cell untuk judul agar rata tengah
                $sheet->mergeCells('A1:I1');
                $sheet->mergeCells('A2:I2');
                $sheet->mergeCells('A3:I3');

                // Styling font
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
                $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(13);
                $sheet->getStyle('A3')->getFont()->setItalic(true)->setSize(11);

                // Rata tengah semua judul
                $sheet->getStyle('A1:A3')->getAlignment()->setHorizontal('center');

                // Styling header tabel (baris ke-4)
                $headerRow = 4;
                $sheet->getStyle("A{$headerRow}:I{$headerRow}")->getFont()->setBold(true);
                $sheet->getStyle("A{$headerRow}:I{$headerRow}")
                    ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFEFEFEF');

                // Lebar kolom otomatis menyesuaikan
                foreach (range('A', 'I') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
