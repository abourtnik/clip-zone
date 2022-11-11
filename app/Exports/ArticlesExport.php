<?php

namespace App\Exports;

use App\Models\Article;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ArticlesExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting
{
    public function collection()
    {
        return Article::all();
    }

    public function headings(): array
    {
        return [
            '#',
            'Title',
            'Status',
            'Created',
            'Updated',
        ];
    }

    /**
     * @var Article $article
     */
    public function map($article): array
    {
        return [
            $article->id,
            $article->title,
            $article->status ? 'En ligne' : 'Hors ligne',
            Date::dateTimeToExcel($article->created_at),
            Date::dateTimeToExcel($article->updated_at),
        ];
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_DATE_DATETIME,
            'E' => NumberFormat::FORMAT_DATE_DATETIME,
        ];
    }
}
