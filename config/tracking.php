<?php

return [
    'statistics_enabled' => env('STATISTICS_ENABLED', false),
    'ignore_ips' => array_filter(
        explode(',', env('IGNORE_IPS', ''))
    )
];
