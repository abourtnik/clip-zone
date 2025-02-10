<?php

namespace App\Charts;
use App\Models\Video;
use Carbon\CarbonPeriod;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;
use IcehouseVentures\LaravelChartjs\Builder;
use Illuminate\Support\Facades\DB;

class VideoStatsChart
{
    private Video $video;

    public string $name = 'video_stats_chart';
    public string $type = 'line';

    public function __construct(Video $video)
    {
        $this->video = $video;
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
        $views = DB::query()
            ->selectRaw('COALESCE(SUM(v.daily_count) OVER (ORDER BY date_series.date), 0) AS cumulative_views_count')
            ->from('date_series')
            ->leftJoinSub(
                DB::query()
                    ->selectRaw('DATE(view_at) AS date')
                    ->selectRaw('count(*) as daily_count')
                    ->from('views')
                    ->where('video_id', $this->video->id)
                    ->groupByRaw('DATE(view_at)'),
                'v',
                'date_series.date',
                '=',
                'v.date'
            )
            ->orderBy('date_series.date')
            ->withRecursiveExpression(
                'date_series',
                DB::query()
                    ->selectRaw('DATE(created_at) AS date')
                    ->from('videos')
                    ->where('id', $this->video->id)
                    ->unionAll(
                        DB::query()
                            ->selectRaw('DATE_ADD(date, INTERVAL 1 day)')
                            ->from('date_series')
                            ->where('date', '<', now()->subDay())
                    ),
            )
            ->get()
            ->pluck('cumulative_views_count')
            ->toArray();

        return [
            [
                'backgroundColor' => "rgba(53, 162, 235, 0.5)",
                'borderColor' => "rgb(53, 162, 235)",
                'pointRadius' => 1,
                'fill' => true,
                "data" => $views
            ]
        ];
    }

    private function labels(): array
    {
        return collect(CarbonPeriod::since($this->video->created_at)->until(now()->subDay()))->map(fn ($date) => $date->format('d M Y'))->toArray();
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
