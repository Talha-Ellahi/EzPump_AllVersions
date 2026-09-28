<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Tank;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class OpenTankShifts extends Command
{
    protected $signature = 'tank:open-shifts';
    protected $description = 'Open shifts for all tanks with user_id=1';

    public function handle()
    {
        $tanks = Tank::all();

        if ($tanks->isEmpty()) {
            $this->error('No tanks found in database');
            return;
        }

        $controller = new \App\Http\Controllers\TankShiftController();
        $request = new \Illuminate\Http\Request();

        foreach ($tanks as $tank) {
            $request->replace([
                'tank_id' => $tank->id,
                'product_id' => $tank->product_id,
                'user_id' => 7,
                'is_modified' => false
            ]);

            $response = $controller->openShift($request);

            if ($response->getStatusCode() === 200) {
                $this->info("Successfully opened shift for Tank ID: {$tank->id}");
            } else {
                $this->error("Failed to open shift for Tank ID: {$tank->id}");
            }
        }

        $this->info('Completed opening shifts for all tanks');
    }
}
