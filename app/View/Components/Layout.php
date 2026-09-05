<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Layout extends Component
{
    public function __construct(
        public string $sidebar = 'default',
        public bool $statistics = false,
        public bool $ads = false,
        public ?string $type = null,
    ) {}

    public function render(): View
    {
        return view('components.layout', [
            'showStatistics' => $this->shouldShowStatistics(),
            'showAds' => $this->shouldShowAds(),
        ]);
    }

    public function shouldShowStatistics(): bool
    {
        return $this->statistics
            && config('app.statistics_enabled')
            && app()->isProduction()
            && !auth()->user()?->is_admin
            && !in_array(request()->ip(), config('app.ignore_ips'));
    }

    public function shouldShowAds(): bool
    {
        return $this->ads
            && config('app.ads_enabled')
            && app()->isProduction();
    }
}
