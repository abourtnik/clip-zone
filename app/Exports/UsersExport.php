<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class UsersExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting
{
    public string $fileName = 'users';

    private array $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function collection() : Collection
    {
        return User::filter($this->filters)->get();
    }

    public function headings(): array
    {
        return [
            '#',
            'Username',
            'Email',
            'Admin',
            'Created',
            'Updated',
        ];
    }

    public function map(mixed $row): array
    {
        return [
            $row->id,
            $row->username,
            $row->email,
            $row->is_admin ? 'X': '',
            $row->created_at->format('Y-m-d H:i:s'),
            $row->updated_at->format('Y-m-d H:i:s'),
        ];
    }

    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_DATE_DATETIME,
            'F' => NumberFormat::FORMAT_DATE_DATETIME,
        ];
    }
}
