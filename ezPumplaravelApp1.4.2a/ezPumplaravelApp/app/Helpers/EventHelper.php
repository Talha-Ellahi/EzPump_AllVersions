<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config; // Added Config facade

class EventHelper
{
    /**
     * Sends event data to the eventsend table.
     *
     * @param int $type The type of event (integer from config).
     * @param array $payload The data payload for the event.
     * @param string $status The initial status of the event (string, will be mapped to integer).
     * @return bool True on success, false on failure.
     */
//    public static function sendEventData(int $type, array $payload, $tankShiftId,string $status = 'pending'): bool
//    {
//        try {
//            $statusMapping = Config::get('event_config.status_mapping');
//            $statusCode = $statusMapping[$status] ?? null;
//
//            if ($statusCode === null) {
//                Log::error("Invalid event status string provided: " . $status);
//                return false; // Or handle as appropriate, e.g., default to a status
//            }
//
//            DB::table('eventsend')->insert([
//                'type' => $type,
//                'payload' => json_encode($payload),
//                'status' => $statusCode, // Use the integer status code
//                'created_at' => Carbon::now(),
//                'updated_at' => Carbon::now(),
//            ]);
//            if ($type==1 || $type==2) {
//
//                DB::table('tank_stock_event')->insert([
//                    'status' => $type,
//                    'shift_id' => $payload['id'],
//                    'tank_id' => $payload['tank_id'],
//                    'product_id' => $payload['product_id'], // Use the integer status code
//                    'user_id' => $payload['user_id'], // Use the integer status code
////                    'opening_totalizer' =>$payload['opening_totalizer'] , // shift id Use the integer  code
////                    'closing_totalizer' => $payload['closing_totalizer'], // Use the integer status code
//                    'opening_dip' => $payload['opening_dip'], // Use the integer status code
//                    'closing_dip' => $payload['closing_dip'], // Use the integer status code
//                    'comments' => 'no comments', // Use the integer status code
//                    'manual_opening_dip' => $payload['manual_opening_dip'], // Use the integer status code
//                    'manual_closing_dip' => $payload['manual_closing_dip'], // Use the integer status code
////                    'manual_opening_totalizer' => $payload['manual_opening_totalizer'], // Use the integer status code
////                    'manual_closing_totalizer' => $payload['manual_closing_totalizer'], // Use the integer status code
//                    'is_modified' => $payload['is_modified'], // Use the integer status code
//                    'added_at' => $payload['start_time'], // Use the integer status code
//                    'created_at' => Carbon::now(),
//                    'updated_at' => Carbon::now(),
//                    'sid'=>null,
//                    'stock_type' => 0, // Use the integer status code
//                    'stock_change'=>0,
//                    'new_stock_value'=>0,
//                    'millimeter'=>0,
//                ]);
//            }else{
//
//                DB::table('tank_stock_event')->insert([
//                    'status' => $type,
//                    'tank_id' => $payload['tank_id'],
//                    'stock_type' => 1, // Use the integer status code
//                    'shift_id' =>$tankShiftId , // shift id Use the integer  code
//                    'stock_change' => $payload['stock_change'], // Use the integer status code
//                    'millimeter' => $payload['millimeter'], // Use the integer status code
//                    'comments' => $payload['comments'], // Use the integer status code
//                    'new_stock_value' => $payload['new_stock_value'], // Use the integer status code
//                    'added_at' => $payload['added_at'], // Use the integer status code
//                    'created_at' => Carbon::now(),
//                    'updated_at' => Carbon::now(),
//                    'sid'=>null,
//                    'product_id' => 0, // Use the integer status code
//                    'user_id' => 0, // Use the integer status code
////                    'opening_totalizer' =>0, // shift id Use the integer  code
////                    'closing_totalizer' => 0, // Use the integer status code
//                    'opening_dip' =>0, // Use the integer status code
//                    'closing_dip' =>0, // Use the integer status code
//                    'manual_opening_dip' => 0, // Use the integer status code
//                    'manual_closing_dip' => 0, // Use the integer status code
////                    'manual_opening_totalizer' => 0, // Use the integer status code
////                    'manual_closing_totalizer' => 0, // Use the integer status code
//                    'is_modified' => 0,
//                ]);
//            }
//
//            return true;
//        } catch (\Exception $e) {
//            Log::error("Failed to send event data to eventsend and tank stock event table: " . $e->getMessage(), [
//                'type' => $type,
//                'payload' => $payload,
//                'status' => $status,
//            ]);
//            // Depending on requirements, you might want to re-throw the exception
//            // or handle it differently (e.g., queue for retry).
//            return false;
//        }
//    }

    public static function sendEventData(int $type, array $payload, $tankShiftId, string $status = 'pending'): bool
    {
        try {
            $statusMapping = Config::get('event_config.status_mapping');
            $statusCode = $statusMapping[$status] ?? null;

            if ($statusCode === null) {
                Log::error("Invalid event status string provided: " . $status);
                return false;
            }

            // ✅ normalize mm → dip mapping
            $map = [
                'opening_mm'         => 'opening_dip',
                'closing_mm'         => 'closing_dip',
                'manual_opening_mm'  => 'manual_opening_dip',
                'manual_closing_mm'  => 'manual_closing_dip',
            ];

            foreach ($map as $mmKey => $dipKey) {
                if (isset($payload[$mmKey]) && !isset($payload[$dipKey])) {
                    $payload[$dipKey] = $payload[$mmKey];
                }
            }

            // insert into eventsend
            DB::table('eventsend')->insert([
                'type'       => $type,
                'payload'    => json_encode($payload),
                'status'     => $statusCode,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            // insert into tank_stock_event
            if ($type == 1 || $type == 2) {
//                DB::table('tank_stock_event')->insert([
//                    'status'              => $type,
//                    'tank_id'             => $payload['tank_id'] ?? null,
//                    'product_id'          => $payload['product_id'] ?? 0,
//                    'user_id'             => $payload['user_id'] ?? 0,
//                    'comments'            => $payload['comments'] ?? 'no comments',
//                    'stock_change'        => 0,
//                    'new_stock_value'     => 0,
//                    'millimeter'          => 0,
//
//                    // Always null/0 for shift open/close
//                    'vendor_name'         =>  null,
//                    'vehicle_no'        =>    null,
//                    'invoice_no'          => null,
//                    'delivery_or_sap_no'         =>  null,
//                    'driver_name'          =>  0,
//                    'driver_cell'         =>  0,
//                    'invoice_date'          =>  null,
//                    'chemb'              =>  null,
//                    'chemb_filling_dip'  =>  null,
//                    'chemb_decanting_dip'=> null,
//                    'seal_no'            => null,
//                    'filling_tmp'        => null,
//                    'decanting_tmp'      =>  null,
//                ]);
            }else{

                $test=DB::table('tank_stock_event')->insert([
                    'status'              => 2,
                    'tank_id'             => $payload['tank_id'] ?? null,
                    'stock_change'        => $payload['stock_change'] ?? 0,
                    'millimeter'          => $payload['millimeter'] ?? 0,
                    'comments'            => $payload['comments'] ?? '',
                    'new_stock_value'     => $payload['new_stock_value'] ?? 0,
                    'product_id'          => $payload['product_id'] ?? 0,
                    'user_id'             => 1,
                    // Stock specific fields → from payload
                    'vendor_name'         => $payload['vendor_name'] ?? 0,
                    'vehicle_no'        =>   $payload['vehicle_no'] ?? 0,
                    'invoice_no'          => $payload['invoice_no'] ?? 0,
                    'delivery_or_sap_no'         => $payload['delivery_no'] ?? 0,
                    'driver_name'          => $payload['driver_name'] ?? 0,
                    'driver_cell'         => $payload['driver_cell'] ?? 0,
                    'invoice_date'          => $payload['invoice_date'] ?? date('Y-m-d'),
                    'chemb'              => $payload['chemb'] ?? 0,
                    'chemb_filling_dip'  => $payload['chemb_filling_dip'] ?? 0,
                    'chemb_decanting_dip'=> $payload['chemb_decanting_dip'] ?? 0,
                    'seal_no'            => $payload['seal_no'] ?? 0,
                    'filling_tmp'        => $payload['filling_tmp'] ?? 0,
                    'decanting_tmp'      => $payload['decanting_tmp'] ?? 0,
                    'net_amount'      => $payload['net_amount']??0,
                    'vendor_id'          => $payload['vendor_id'] ?? 0,
                    'pid'              => $payload['pid'] ?? 0,
                    'calendar_id'              => $payload['calendar_id'] ?? 0,
                ]);


            }



            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send event data to eventsend and tank stock event table: " . $e->getMessage(), [
                'type'   => $type,
                'payload'=> $payload,
                'status' => $status,
            ]);
            return false;
        }
    }

}
