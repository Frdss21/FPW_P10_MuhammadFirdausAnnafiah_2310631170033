<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Carbon\Carbon;

class ProductsExport implements FromCollection, WithEvents
{
    public function collection()
    {
        return Product::select('product_name', 'unit', 'type', 'information', 'qty', 'producer', 'created_at')->get();
    }

    public function headings(): array
    {
        return ['Name', 'Unit', 'Category', 'Description', 'Stock', 'Supplier', 'Barang Masuk'];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $now = Carbon::now()->locale('id')->translatedFormat('d F Y, H:i');

                // Insert rows for header
                $sheet->insertNewRowBefore(1, 6);

                // Tambahkan logo
                $drawing = new Drawing();
                $drawing->setName('Logo');
                $drawing->setDescription('Company Logo');
                $drawing->setPath(public_path('logo.jpeg')); // simpan logo di folder public/logo.jpeg
                $drawing->setHeight(60);
                $drawing->setCoordinates('A1');
                $drawing->setOffsetX(10);
                $drawing->setWorksheet($sheet);

                // Header
                $sheet->setCellValue('C1', 'PT UUS MIRACLE');
                $sheet->setCellValue('C2', 'REKAP MUTASI STOCK BULANAN');
                $sheet->setCellValue('C3', 'Periode: ' . Carbon::now()->startOfMonth()->format('d M Y') . ' s/d ' . Carbon::now()->endOfMonth()->format('d M Y'));

                $sheet->mergeCells('C1:G1');
                $sheet->mergeCells('C2:G2');
                $sheet->mergeCells('C3:G3');

                $sheet->getStyle('C1:C3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle('C1')->getFont()->setBold(true)->setSize(16);
                $sheet->getStyle('C2')->getFont()->setBold(true)->setSize(13);
                $sheet->getStyle('C3')->getFont()->setItalic(true)->setSize(11);

                // Header kolom (row 6)
                $headers = ['Name', 'Unit', 'Category', 'Description', 'Stock', 'Supplier', 'Barang Masuk'];
                $sheet->fromArray([$headers], null, 'A6');

                // Styling header kolom
                $sheet->getStyle('A6:G6')->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => 'D9EAD3'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                    ],
                ]);

                // Ambil data produk dan tulis manual
                $data = $this->collection()->toArray();
                $sheet->fromArray($data, null, 'A7');

                // Styling isi tabel
                $lastRow = 6 + count($data);
                $sheet->getStyle("A7:G{$lastRow}")->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                ]);

                // Footer
                $footerStart = $lastRow + 2;
                $sheet->setCellValue("A{$footerStart}", "* Data ini bersifat rahasia.");
                $sheet->setCellValue("A" . ($footerStart + 1), "Tanggal cetak: $now");
                $sheet->setCellValue("F" . ($footerStart + 3), "Diketahui,");
                $sheet->setCellValue("F" . ($footerStart + 7), "Muhammad Firdaus Annafiah,S.Kom");

                // Auto-size columns
                foreach (range('A', 'G') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}