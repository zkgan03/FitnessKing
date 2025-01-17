<?php

namespace App\Observers;

use Illuminate\Support\Facades\Log;

class Customer implements Observer
{
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function update(string $message)
    {
        // Use Log instead of echo to capture the output
        Log::info("Coach {$this->name} notified: {$message}");
    }
}
