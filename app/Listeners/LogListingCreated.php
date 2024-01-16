<?php

namespace App\Listeners;

use App\Events\ListingCreated;
use Illuminate\Support\Facades\Log;

class LogListingCreated
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
    public function handle(ListingCreated $event): void
    {
        Log::info('Listing Created: ' . $event->listing->id);
    }
}
