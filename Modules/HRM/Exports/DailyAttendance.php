<?php

namespace Modules\HRM\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DailyAttendance implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithMapping
{
    private $attendances;

    protected $title;

    public function __construct($attendances, $title)
    {
        $this->attendances = $attendances;
        $this->title = $title;
    }

    public function collection()
    {
        $finalCollection = $this->attendances->get();

        return $finalCollection;
    }

    public function map($finalCollection): array
    {
        return [
            $finalCollection->employee_id,
            $finalCollection->employee_name,
            $finalCollection->section_name,
            $finalCollection->designation_name,
            Carbon::parse($finalCollection->clock_in)->format('h:ia'),
            Carbon::parse($finalCollection->clock_out)->format('h:ia'),
            $finalCollection->shift,
            $finalCollection->status,
        ];
    }

    public function headings(): array
    {
        return [
            [
                'Daily Attendance List -  Dotbook ERP',
            ],
            [
                'Employee ID',
                'Employee Name',
                'Section',
                'Designation',
                'Clock in',
                'Clock out',
                'Shift',
                'Status',
            ],
        ];

    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:H1');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        $sheet
            ->getStyle('A1')
            ->getFont()
            ->setBold(true)
            ->setSize(14)
            ->getColor()
            ->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
        $sheet
            ->getStyle('A2:H2')
            ->getFont()
            ->setBold(true)
            ->getColor()
            ->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
        $sheet
            ->getStyle('A2:H2')
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('black');

        return [
            2 => ['font' => ['bold' => true]],
        ];
    }
}
