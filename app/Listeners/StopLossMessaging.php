<?php

namespace App\Listeners;

use App\Events\StopLossReached;
use App\Mail\StopLossMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class StopLossMessaging
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
    public function handle(StopLossReached $event): void
    {
        // Log-Eintrag
        Log::info("Stop-Loss ausgelöst für {$event->share} bei {$event->value} EUR");

        // E-Mail-Benachrichtigung senden
        Mail::to('jens@freude-now.de')->send(new StopLossMail($event->share, $event->value));

    }
}
