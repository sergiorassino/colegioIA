<?php

namespace App\Listeners;

use App\Events\PasswordShouldBeRehashed;
use Illuminate\Support\Facades\Hash;

class RehashPasswordListener
{
    public function handle(PasswordShouldBeRehashed $event): void
    {
        $event->user->forceFill([
            'pwrd' => Hash::make($event->plainTextPassword),
        ])->save();
    }
}
