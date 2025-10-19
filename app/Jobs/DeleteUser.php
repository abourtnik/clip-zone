<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\Account\DeleteAccount;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class DeleteUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 120; // 2 minutes

    public User $user;

    public bool $deleteWhenMissingModels = true;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Exception
     */
    public function handle() : void
    {
        $this->user->notify(new DeleteAccount());

        $this->user->videos()->delete();

        $this->user->subscriptions()->detach();
        $this->user->subscribers()->detach();

        $this->user->comments()->delete();

        $this->user->playlists()->delete();

        $this->user->delete();
    }


    /**
     * Handle a job failure.
     *
     * @param Throwable  $exception
     * @return void
     */
    public function failed(Throwable $exception) : void
    {

    }
}
