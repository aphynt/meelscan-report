<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ConsumptionExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    ShouldAutoSize
{
    protected Collection $data;
    protected int $rowNumber = 0;

    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
    }

    /** ✅ HEADER SESUAI FILE */
    public function headings(): array
    {
        return [
            'NO',
            'NIK',
            'NAME',
            'MEAL TYPE',
            'QUANTITY',
            'FACE',
            'CREATED BY',
            'POSITION',
            'FOOD CATEGORY',
            'RATING',
            'ATTENDANCE DATE',
            'ATTENDANCE TIME',
        ];
    }

    /** ✅ DATA MAPPING SESUAI HEADER */
    public function map($row): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $row->nik,
            $row->name,
            ucfirst($row->meal_type),
            $row->quantity,
            $row->is_real_face,
            $row->created_by,
            $row->position,
            $row->food_category,
            match ((int) $row->rating) {
                1 => 'Sangat Tidak Enak',
                2 => 'Tidak Enak',
                3 => 'Cukup',
                4 => 'Enak',
                5 => 'Sangat Enak',
                default => '',
            },
            Carbon::parse($row->attendance_date)->format('d-m-Y'),
            Carbon::parse($row->attendance_time)->format('H:i'),
        ];
    }

    /** ✅ STYLING SESUAI FILE */
    public function styles(Worksheet $sheet)
    {
        $lastRow    = $sheet->getHighestRow();
        $lastColumn = $sheet->getHighestColumn();

        // Freeze header
        $sheet->freezePane('A2');

        // Header style
        $sheet->getStyle("A1:{$lastColumn}1")->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'E7E6E6',
                ],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        // Body style
        $sheet->getStyle("A2:{$lastColumn}{$lastRow}")->applyFromArray([
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);
    }
}
