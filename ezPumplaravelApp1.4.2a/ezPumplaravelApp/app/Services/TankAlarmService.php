<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TankAlarmService
{
    public static function checkTankLevelLiveold($tank, $currentLevelMM)
    {
//        Log::info("---- Alarm Check Started ----");
//        Log::info("Tank ID: " . $tank->id);
//        Log::info("Current Level: " . $currentLevelMM);

        /* =====================================================
         *              DETECT ALARM TYPE (Priority Based)
         * =====================================================*/

        $alarmType = null;

        // 🔴 LOW LEVEL
        if (!is_null($tank->low_low_level_alarm_mm) &&
            (float)$currentLevelMM <= (float)$tank->low_low_level_alarm_mm) {

            $alarmType = 'critical_low';

        } elseif (!is_null($tank->low_level_alarm_mm) &&
            (float)$currentLevelMM <= (float)$tank->low_level_alarm_mm) {

            $alarmType = 'low';
        }

        // 🔵 HIGH LEVEL
        elseif (!is_null($tank->high_high_level_alarm_mm) &&
            (float)$currentLevelMM >= (float)$tank->high_high_level_alarm_mm) {

            $alarmType = 'critical_high';

        } elseif (!is_null($tank->high_level_alarm_mm) &&
            (float)$currentLevelMM >= (float)$tank->high_level_alarm_mm) {

            $alarmType = 'high';
        }

//        Log::info("Detected Alarm Type: " . ($alarmType ?? "NULL"));

        /* =====================================================
         *              IF NO ALARM → CLOSE ACTIVE
         * =====================================================*/

        if (!$alarmType) {

            DB::table('tank_alarm_logs')
                ->where('tank_id', $tank->id)
                ->where('status', 'active')
                ->update([
                    'status' => 'resolved',
                    'end_time' => now(),
                    'updated_at' => now()
                ]);

            DB::table('tank_alarm_history')
                ->where('tank_id', $tank->id)
                ->where('status', 'active')
                ->update([
                    'status' => 'resolved',
                    'end_time' => now(),
                    'updated_at' => now()
                ]);

            $tank->last_alarm = null;
            $tank->save();

//            Log::info("No Alarm → Active Alarms Closed");

            return;
        }

        /* =====================================================
         *              PREVENT DUPLICATE INSERT
         * =====================================================*/

        $existing = DB::table('tank_alarm_logs')
            ->where('tank_id', $tank->id)
            ->where('alarm_type', $alarmType)
            ->where('status', 'active')
            ->first();

        if ($existing) {

//            Log::info("Alarm Already Active → Skipping Insert");
            return;
        }

        /* =====================================================
         *              CLOSE OLD ACTIVE ALARMS
         * =====================================================*/

        DB::table('tank_alarm_logs')
            ->where('tank_id', $tank->id)
            ->where('status', 'active')
            ->update([
                'status' => 'resolved',
                'end_time' => now(),
                'updated_at' => now()
            ]);

        DB::table('tank_alarm_history')
            ->where('tank_id', $tank->id)
            ->where('status', 'active')
            ->update([
                'status' => 'resolved',
                'end_time' => now(),
                'updated_at' => now()
            ]);

        /* =====================================================
         *              INSERT NEW ALARM (ONLY ONE)
         * =====================================================*/

        $insertData = [
            'tank_id'    => $tank->id,
            'product_id' => $tank->product_id ?? null,
            'alarm_type' => $alarmType,
            'alarm_info' => "Level reached {$currentLevelMM} mm",
            'level_mm'   => $currentLevelMM,
            'status'     => 'active',
            'start_time' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ];

        DB::table('tank_alarm_logs')->insert($insertData);
        DB::table('tank_alarm_history')->insert($insertData);

//        Log::info("New Alarm Inserted Successfully: " . $alarmType);

        /* =====================================================
         *              UPDATE LAST ALARM
         * =====================================================*/

        $tank->last_alarm = $alarmType;
        $tank->save();

//        Log::info("---- Alarm Process Completed ----");
    }
    public static function checkTankLevelLive($tank, $currentLevelMM)
    {
        $alarmType = null;

        /* =====================================================
         *              DETECT ALARM TYPE (Priority Based)
         * =====================================================*/

        // 🔴 LOW LEVEL
        if (!is_null($tank->low_low_level_alarm_mm) &&
            (float)$currentLevelMM <= (float)$tank->low_low_level_alarm_mm) {

            $alarmType = 'critical_low';

        } elseif (!is_null($tank->low_level_alarm_mm) &&
            (float)$currentLevelMM <= (float)$tank->low_level_alarm_mm) {

            $alarmType = 'low';
        }

        // 🔵 HIGH LEVEL
        elseif (!is_null($tank->high_high_level_alarm_mm) &&
            (float)$currentLevelMM >= (float)$tank->high_high_level_alarm_mm) {

            $alarmType = 'critical_high';

        } elseif (!is_null($tank->high_level_alarm_mm) &&
            (float)$currentLevelMM >= (float)$tank->high_level_alarm_mm) {

            $alarmType = 'high';
        }

        /* =====================================================
         *              IF NO ALARM → CLOSE ACTIVE
         * =====================================================*/

        if (!$alarmType) {

            DB::table('tank_alarm_logs')
                ->where('tank_id', $tank->id)
                ->where('status', 'active')
                ->update([
                    'status' => 'resolved',
                    'end_time' => now(),
                    'updated_at' => now()
                ]);

            DB::table('tank_alarm_history')
                ->where('tank_id', $tank->id)
                ->where('status', 'active')
                ->update([
                    'status' => 'resolved',
                    'end_time' => now(),
                    'updated_at' => now()
                ]);

            $tank->last_alarm = null;
            $tank->save();

            return;
        }

        /* =====================================================
         *              PREVENT DUPLICATE INSERT
         * =====================================================*/

        $existing = DB::table('tank_alarm_logs')
            ->where('tank_id', $tank->id)
            ->where('alarm_type', $alarmType)
            ->where('status', 'active')
            ->first();

        if ($existing) {
            return; // already active → no duplicate
        }

        /* =====================================================
         *              CLOSE OLD ACTIVE ALARMS
         * =====================================================*/

        DB::table('tank_alarm_logs')
            ->where('tank_id', $tank->id)
            ->where('status', 'active')
            ->update([
                'status' => 'resolved',
                'end_time' => now(),
                'updated_at' => now()
            ]);

        DB::table('tank_alarm_history')
            ->where('tank_id', $tank->id)
            ->where('status', 'active')
            ->update([
                'status' => 'resolved',
                'end_time' => now(),
                'updated_at' => now()
            ]);

        /* =====================================================
         *              INSERT NEW ALARM
         * =====================================================*/

        $insertData = [
            'tank_id'    => $tank->id,
            'product_id'  => $tank->product_id ?? null,
            'alarm_type'  => $alarmType,
            'alarm_info'  => "Level reached {$currentLevelMM} mm",
            'level_mm'    => $currentLevelMM,
            'status'      => 'active',
            'start_time'  => now(),
            'created_at'  => now(),
            'updated_at'  => now()
        ];

        DB::table('tank_alarm_logs')->insert($insertData);
        DB::table('tank_alarm_history')->insert($insertData);

        /* =====================================================
         *              SEND EMAIL TO MANAGERS (ROLE 2)
         * =====================================================*/

//        $emails = DB::table('alarm_email_settings')
//            ->where('is_active', 1)
//            ->whereNotNull('email')
//            ->pluck('email');
//
//        /* ================= SEND EMAIL ================= */
//        $alarmSettings = DB::table('atg_alarm_settings')->first();
//
//        if (!$alarmSettings || $alarmSettings->is_email != 0) {
//
//        foreach ($emails as $email) {
//
//            \Mail::send([], [], function ($message) use ($email, $tank, $alarmType, $currentLevelMM) {
//
//                $message->to($email)
//                    ->subject("🚨 Tank {$tank->id} {$alarmType} Alert")
//                    ->html('
//        <div style="background:#f4f6f9;padding:40px;font-family:Arial,sans-serif;">
//
//            <div style="max-width:600px;margin:auto;background:#ffffff;
//                        padding:30px;border-radius:18px;
//                        box-shadow:0 10px 30px rgba(0,0,0,0.08);">
//
//                <!-- HEADER -->
//                <div style="text-align:center;margin-bottom:25px;">
//                    <h2 style="color:#dc3545;margin:0;">
//                        🚨 Tank Alarm Alert
//                    </h2>
//                    <p style="color:#777;margin-top:5px;">
//                        Real-Time Monitoring System
//                    </p>
//                </div>
//
//                <!-- ALARM BADGE -->
//                <div style="text-align:center;margin-bottom:20px;">
//                    <span style="background:' .
//                        (($alarmType == 'critical_high' || $alarmType == 'critical_low')
//                            ? '#dc3545' : '#ffc107') . ';
//                        color:#fff;
//                        padding:8px 18px;
//                        border-radius:25px;
//                        font-weight:bold;
//                        font-size:13px;">
//                        ' . strtoupper($alarmType) . '
//                    </span>
//                </div>
//
//                <!-- INFO CARD -->
//                <div style="background:#f8f9fa;
//                            padding:20px;
//                            border-radius:14px;
//                            border-left:5px solid ' .
//                        (($alarmType == 'critical_high' || $alarmType == 'critical_low')
//                            ? '#dc3545' : '#ffc107') . '">
//
//                    <p style="margin:6px 0;">
//                        <strong>Tank ID:</strong> ' . $tank->tank_name . '
//                    </p>
//
//                    <p style="margin:6px 0;">
//                        <strong>Product ID:</strong> ' . ($tank->product_id ?? '-') . '
//                    </p>
//
//                    <p style="margin:6px 0;">
//                        <strong>Fuel Level:</strong>
//                        <span style="color:#0d6efd;font-weight:bold;">
//                            ' . $currentLevelMM . ' mm
//                        </span>
//                    </p>
//
//                    <p style="margin:6px 0;">
//                        <strong>Status:</strong>
//                        <span style="color:#dc3545;font-weight:bold;">
//                            ACTIVE
//                        </span>
//                    </p>
//
//                </div>
//
//                <!-- FOOTER -->
//                <div style="margin-top:25px;
//                            text-align:center;
//                            font-size:12px;
//                            color:#888;">
//
//                    ⚡ This is an automated alert from ATG Tank Monitoring System.
//
//                </div>
//
//            </div>
//
//        </div>
//        ');
//            });
//
//            /* ================= LOG ================= */
//
//            Log::info("Tank Alarm Email Sent", [
//                'tank_id'   => $tank->id,
//                'alarm_type'=> $alarmType,
//                'sent_to'   => $email,
//                'time'      => now()
//            ]);
//        }
//        }
        /* =====================================================
         *              UPDATE LAST ALARM
         * =====================================================*/

        $tank->last_alarm = $alarmType;
        $tank->save();
    }
}
