<?php

namespace App\Listeners;

use App\Events\ShiftClosed;
use App\Models\TankStockLedger;
use App\Models\Tank;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use DB;
class ProcessClosedShift
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
    public function handle(ShiftClosed $event): void
    {
        $shiftData = $event->shiftData;
        // dd($shiftData);

        $tanks = DB::select('select * from tanks where product_id = ?', [$shiftData['shift']['icode']]);
        // todo: run a forloop to find which tank it belongs to using nozzle_ids
        $tankId = $tanks[0]->id ?? null;
        // dd($tankId);
        // $tankId = $shiftData['shift']['tank_id'] ?? null;
        $icode=$shiftData['shift']['icode'];
        if ($tankId) {
            TankStockLedger::create([
                'tank_id' => $tankId,
                'shift_id' => $shiftData['shift']['id'],
//                'icode' => $icode,
                'transaction_type' => 'shift_close',
                'quantity' =>  abs($shiftData['shift']['closing_fuel'] ?? 0),
                'comments' => "Shift closed transaction. shift_id: ". $shiftData['shift']['id']. " pumpid: ". $shiftData['shift']['pump_id'],
                'created_at' => now(),
                'updated_at' => now(),
                'stock_change' => abs ($shiftData['shift']['total_qty']??0)/100,
                'millimeter' => 0,
            ]);
//            DB::table('tank_stock_ledger_send')->insert([
//                'tank_id' => $tankId,
//                'shift_id' => $shiftData['shift']['id'],
//                'transaction_type' => 'shift_close',
//                'quantity' => abs($shiftData['shift']['closing_fuel'] ?? 0),
//                'comments' => "Shift closed transaction. shift_id: ". $shiftData['shift']['id']. " pumpid: ". $shiftData['shift']['pump_id'],
//                'stock_change' => abs($shiftData['shift']['total_qty'] ?? 0) / 100,
//                'millimeter' => 0,
//                'pump_id'=> $shiftData['shift']['pump_id'],
//                'created_at' => now(),
//                'updated_at' => now(),
//            ]);
            Tank::find($tankId)->stock()->update([
                'stock_value' => DB::raw('stock_value - ' . $shiftData['shift']['total_qty']/100),
                'updated_at' => now(),
            ]);
        }
    }
}
