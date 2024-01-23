<?php

namespace App\Listeners;

use App\Events\ListingCreated;
use App\Mail\ListingCreatedMail;
use Illuminate\Support\Facades\Mail;

class SendMailListingCreated
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
        Mail::to('thuygiang3004@gmail.com')->send(new ListingCreatedMail($event->listing));
    }
}
