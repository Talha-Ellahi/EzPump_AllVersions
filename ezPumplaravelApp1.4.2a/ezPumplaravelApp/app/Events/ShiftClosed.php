<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ShiftClosed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $shiftData;

    /**
     * Create a new event instance.
     *
     * @param array $shiftData
     * @return void
     */
    public function __construct(array $shiftData)
    {
        $this->shiftData = $shiftData;
    }
}
