<?php

namespace App\Listeners;

use IlluminateAuthEventsLogout;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Auth\Events\Logout;
use App\Models\LoginHistory;

class LogSuccessfulLogout
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Logout $event)
    {
        // Update the latest login record for this user
        $history = LoginHistory::where('user_id', $event->user->id)
                    ->whereNull('logout_at')
                    ->latest()
                    ->first();

        if ($history) {
            $history->update(['logout_at' => now()]);
        }
    }
}
