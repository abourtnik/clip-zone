<?php

namespace App\Charts;

use App\Models\Transaction;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;
use IcehouseVentures\LaravelChartjs\Builder;
use Illuminate\Support\Facades\DB;

class PremiumEarningsChart
{
    public string $name = 'premium_earnings_chart';
    public string $type = 'line';

    private Carbon $firstTransactionDate;

    public function __construct() {
        $this->firstTransactionDate = Transaction::query()->oldest('date')->value('date');
    }

    public function build(): Builder
    {
        return Chartjs::build()
            ->name($this->name)
            ->type($this->type)
            ->labels($this->labels())
            ->datasets($this->data())
            ->options($this->options())
            ->optionsRaw($this->options());
    }

    private function data() : array
    {
        $data = DB::query()
            ->selectRaw("DATE_FORMAT(m.month_start, '%Y-%m') AS month")
            ->selectRaw('COALESCE(SUM(t.amount), 0) AS total_amount')
            ->from('months', 'm')
            ->leftJoin('transactions as t', DB::raw("DATE_FORMAT(t.date, '%Y-%m')"), '=', DB::raw("DATE_FORMAT(m.month_start, '%Y-%m')"))
            ->groupBy("m.month_start")
            ->orderBy('m.month_start')
            ->withRecursiveExpression(
                'months',
                DB::query()
                    ->selectRaw("DATE('".$this->firstTransactionDate->format('Y-m-d')."') AS month_start")
                    ->unionAll(
                        DB::query()
                            ->selectRaw('DATE_ADD(month_start, INTERVAL 1 MONTH)')
                            ->from('months')
                            ->where('month_start', '<', now()->format('Y-m-d'))
                    ),
            )
            ->get()
            ->pluck('total_amount')
            ->toArray();

        return [
            [
                'backgroundColor' => "rgba(53, 162, 235, 0.5)",
                'borderColor' => "rgb(53, 162, 235)",
                'pointRadius' => 1,
                'fill' => true,
                "data" => $data
            ]
        ];
    }

    private function labels(): array
    {
        return collect(CarbonPeriod::since($this->firstTransactionDate)->month()->until(now()->subDay()))->map(fn ($date) => $date->format('M Y'))->toArray();
    }

    private function options() : array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'display' => false
                ],
                'tooltip' => [
                    'displayColors' => false,
                    'borderColor' => 'black',
                    'bodyFont' => [
                        'weight' => 'bold',
                        'size' => 17,
                    ],
                    'borderWidth' => 2
                ]
            ]
        ];
    }
}
