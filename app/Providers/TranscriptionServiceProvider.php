<?php

namespace App\Providers;

use App\Integrations\Transcription\AssemblyAIProvider;
use App\Integrations\Transcription\FakeProvider;
use App\Integrations\Transcription\TranscriptionContract;
use Illuminate\Container\Container;
use Illuminate\Support\ServiceProvider;

class TranscriptionServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() : void
    {
        $this->app->bind(
            TranscriptionContract::class,
            function (Container $app) {
                $driver = config('transcription.default');
                return match ($driver) {
                    'fake' => new FakeProvider(),
                    'assemblyai' => new AssemblyAIProvider(config('transcription.providers.assemblyai.api_key'))
                };
            }
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() :void
    {

    }
}
