<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\Interaction;

class InteractionObserver
{
    /**
     * Handle the Interaction "updating" event.
     *
     * @param Interaction $interaction
     * @return void
     */
    public function updating(Interaction $interaction) : void
    {
        // Update status
        Activity::forSubject($interaction)->update([
            'properties' => json_encode([
                'status' => $interaction->status
            ])
        ]);
    }

    /**
     * Handle the Interaction "deleting" event.
     *
     * @param Interaction $interaction
     * @return void
     */
    public function deleting(Interaction $interaction) : void
    {
        // Remove activity for this comment
        Activity::forSubject($interaction)->delete();
    }
}
