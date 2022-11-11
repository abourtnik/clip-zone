<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class UsersExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting
{
    public function collection()
    {
        return User::all();
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

    /**
     * @var User $user
     */
    public function map($user): array
    {
        return [
            $user->id,
            $user->username,
            $user->email,
            $user->is_admin ? 'oui' : 'non',
            Date::dateTimeToExcel($user->created_at),
            Date::dateTimeToExcel($user->updated_at),
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
