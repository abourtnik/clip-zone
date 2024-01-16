<?php

namespace App\View\Composers;

use App\Enums\ReportReason;
use Illuminate\View\View;

class ReportComposer
{
    /**
     * Bind data to the view.
     *
     * @param View  $view
     * @return void
     */
    public function compose(View $view): void
    {
        $view->with('report_reasons', ReportReason::get());
    }
}
