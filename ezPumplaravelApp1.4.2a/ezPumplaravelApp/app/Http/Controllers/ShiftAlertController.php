<?php

namespace App\Http\Controllers;

use App\Models\BypassRecord;
use App\Models\Settings;
use App\Models\Shift;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailer;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class ShiftAlertController extends Controller
{


//    public function upcomingShiftEndings()
//    {
//
//        $now = Carbon::now();
//        $alertBeforeMinutes = 55; // 1 hour early alert
//        $sys_config = DB::table('SysConfig')->first();
//        $durationHours = $this->getShiftDuration($sys_config);
////        $durationHours = 1;
//
//        if (isset($sys_config->is_screen_lock) && $sys_config->is_screen_lock == 1) {
//
//            // --- Check Active Bypass Unlock ---
//            $activeBypass = BypassRecord::whereDate('created_at', $now->toDateString())
//                ->orderBy('created_at', 'desc')
//                ->first();
//            $bypassActive = false;
//            $bypassEndTime = null;
//
//            if ($activeBypass) {
////                $bypassEndTime = Carbon::parse($activeBypass->created_at)->addHours($activeBypass->add_hours);
//                $bypassEndTime = Carbon::parse($activeBypass->created_at)->addMinutes($activeBypass->add_hours);
//                if ($now->lessThan($bypassEndTime)) {
//                    $bypassActive = true;
//                }
//            }
//
//            $response = [];
//
//            foreach (Shift::orderBy('pump_id')->get() as $shift) {
//                $start = Carbon::parse($shift->start_date);
//
//                $end = $start->copy()->addHours($durationHours);
//
//
////                $end = $start->copy()->addMinutes($durationHours);
//                $remainingMinutes = $now->diffInMinutes($end, false);
//                $isLocked = $end->lessThanOrEqualTo($now);
//                // --- Email Reminder Logic ---
//                // 1 Hour (55 min) pehle reminder bhejna
//                if ($remainingMinutes == 55) {
//                    $this->emailTrigger($shift, 0, $end->format('Y-m-d H:i:s'));
//                }
//                // Jab shift time nikal gaya aur abhi lock ho gayi (missed closure)
////                if ($isLocked) {
////                    $this->emailTrigger($shift, 1, $end->format('Y-m-d H:i:s'));
////                }
//                // Override Lock if Bypass is Active
//                if ($isLocked && $bypassActive) {
//                    Cache::get('system_locked');
//                    Cache::forget('bypass_active_until');
//                    $isLocked = false;
//                }
////                if ($remainingMinutes == true) {
////                    Cache::put('shift_minutes_remaining', true, now()->addHours(2));
////                }
//                if ($remainingMinutes == 55 && !Cache::has("reminder_sent_$shift->id")) {
//                    $this->emailTrigger($shift, 0, $end->format('Y-m-d H:i:s'));
//                    Cache::put("reminder_sent_$shift->id", true, now()->addMinutes(60));
//                }
//                // Cache Lock Status Only if No Active Bypass
//                if ($isLocked && !$bypassActive) {
//                    Cache::put('system_locked', true, now()->addHours(2));
//                    Cache::put('bypass_active_until', true, now()->addHours(2));
//                }
//
//                $response[] = [
//                    'id' => $shift->id,
//                    'shift_code' => $sys_config->NoofShifts ?? null,
//                    'shift_number' => $this->getShiftNumber($shift, $sys_config),
//                    'locked' => $isLocked,
//                    'about_to_end' => ($remainingMinutes <= $alertBeforeMinutes && $remainingMinutes > 0),
//                    'minutes_remaining' => $remainingMinutes,
//                    'should_beep' => ($remainingMinutes <= 5 && $remainingMinutes > 0),
//                    'bypass_active_until' => $bypassActive ? $bypassEndTime->toDateTimeString() : null,
//                ];
//            }
//
//            return response()->json(array_values($response));
//
//        } else {
//            // Screen Lock Disabled in SysConfig
//            Cache::forget('system_locked');
//            Cache::forget('bypass_active_until');
//            Cache::forget('shift_minutes_remaining');
//            return response()->json([]);
//        }
//    }
    public function upcomingShiftEndings()
    {
        $now = Carbon::now();
        $sys_config = DB::table('SysConfig')->first();
        $durationHours = $this->getShiftDuration($sys_config);

        if (isset($sys_config->is_screen_lock) && $sys_config->is_screen_lock == 1) {

            // --- Check Active Bypass Unlock ---
            $activeBypass = BypassRecord::whereDate('created_at', $now->toDateString())
                ->orderBy('created_at', 'desc')
                ->first();
            $bypassActive = false;
            $bypassEndTime = null;

            if ($activeBypass) {
                $bypassEndTime = Carbon::parse($activeBypass->created_at)->addMinutes($activeBypass->add_hours);
                if ($now->lessThan($bypassEndTime)) {
                    $bypassActive = true;
                }
            }

            $response = [];

            foreach (Shift::orderBy('pump_id')->get() as $shift) {
                $start = Carbon::parse($shift->start_date);
                $end = $start->copy()->addHours($durationHours);
                $remainingMinutes = $now->diffInMinutes($end, false);
                $isLocked = $end->lessThanOrEqualTo($now);

                // --- Email Reminder Logic ---
                // 1 Hour (55 min) before
                if ($remainingMinutes == 55 && !Cache::has("reminder_55_sent_$shift->id")) {
//                    $this->emailTrigger($shift, 0, $end->format('Y-m-d H:i:s'));
                    Cache::put("reminder_55_sent_$shift->id", true, now()->addHours(2));
                }

                // 20 Minutes before
                if ($remainingMinutes == 20 && !Cache::has("reminder_20_sent_$shift->id")) {
//                    $this->emailTrigger($shift, 2, $end->format('Y-m-d H:i:s'));
                    Cache::put("reminder_20_sent_$shift->id", true, now()->addHours(1));
                }

                // Override Lock if Bypass is Active
                if ($isLocked && $bypassActive) {
                    Cache::forget('bypass_active_until');
                    $isLocked = false;
                }

                // Cache Lock Status Only if No Active Bypass
                if ($isLocked && !$bypassActive) {
                    Cache::put('system_locked', true, now()->addHours(2));
                    Cache::put('bypass_active_until', true, now()->addHours(2));
                }

                $response[] = [
                    'id' => $shift->id,
                    'shift_code' => $sys_config->NoofShifts ?? null,
                    'shift_number' => $this->getShiftNumber($shift, $sys_config),
                    'locked' => $isLocked,
                    'about_to_end' => ($remainingMinutes <= 55 && $remainingMinutes > 0),
                    'minutes_remaining' => $remainingMinutes,
                    'should_beep' => ($remainingMinutes <= 5 && $remainingMinutes > 0),
                    'bypass_active_until' => $bypassActive ? $bypassEndTime->toDateTimeString() : null,
                ];
            }

            return response()->json(array_values($response));

        } else {
            // Screen Lock Disabled in SysConfig
            Cache::forget('system_locked');
            Cache::forget('bypass_active_until');
            Cache::forget('shift_minutes_remaining');
            return response()->json([]);
        }
    }

// Helper to get Shift Number
    private function getShiftNumber($shift, $sys_config)
    {
        if ($sys_config->NoofShifts == 1) {

            if ($sys_config->Shift1Code) {

                return ['name' => 'Shift6 (24h)', 'code' => $sys_config->Shift1Code];
            }
        } elseif ($sys_config->NoofShifts == 2) {
            if ($sys_config->Shift1Code) {
                return ['name' => 'Shift 4', 'code' => $sys_config->Shift1Code];
            } elseif ($shift->Shift2Code) {
                return ['name' => 'Shift 5', 'code' => $sys_config->Shift2Code];
            }
        } else {
            if ($sys_config->Shift1Code) {
                return ['name' => 'Shift 1', 'code' => $sys_config->Shift1Code];
            } elseif ($shift->Shift2Code) {
                return ['name' => 'Shift 2', 'code' => $sys_config->Shift2Code];
            } elseif ($shift->Shift3Code) {
                return ['name' => 'Shift 3', 'code' => $sys_config->Shift3Code];
            }
        }

        return ['name' => 'Unknown', 'code' => null];
    }


    private function getShiftDuration($shift)
    {
        // This logic should be based on how you define shift duration
        // You can use a `shift_type` field or pump-wise logic
        // For now, example:
//           dd($sys_config->NoofShifts,$sys_config);

        if ($shift->NoofShifts == 1) {
            if ($shift->Shift1Code == 6) {
                return 24;
            }
        } elseif ($shift->NoofShifts == 2) {
            if ($shift->Shift1Code == 4) {
                return 12;
            } elseif ($shift->Shift2Code == 5) {
                return 12;
            }
        } else {
            if ($shift->Shift1Code == 1) {
                return 8;
            } elseif ($shift->Shift2Code == 2) {
                return 8;
            } elseif ($shift->Shift2Code == 3) {
                return 8;
            }
        }
    }


//    public function emailTrigger()
    private function emailTrigger($shift, $reminder,$end)

    {

//        $shift=Shift::first();
//        $reminder=0;
//        $end= Carbon::parse($shift->start_date)->format('Y-m-d H:i:s');
        $user = User::whereIn('role',['2','10'] )->first();
        $setting = Settings::where('key', 'name')->value('value');
        $setting_line = Settings::where('key', 'line1')->value('value');
        $shiftData = [
            'shift_name' => $shift->cashier_name ?? 'Shift Operator',
            'end_time' => $end,
            'user_name' => $user->name ?? 'User',
            'station_name' => $setting,
            'station_location' => $setting_line,
            'start_name' => Carbon::parse($shift->start_date)->format('Y-m-d H:i:s'),
        ];
        if ($reminder == 0) {
            // Email reminder 1 hour before shift ends
            $subject = "⏰ Shift Ending Soon: {$shiftData['shift_name']} - Remaining";
            $template = 'emails.shift_reminder';
        } else {
            // Email after shift completion
            $subject = "✅ Shift Closure Missed – Immediate Action Required";
            $template = 'emails.shift_completed';
        }
//dd($user->email);
        $this->sendEmail($user->email ?? 'admin@system.com', $subject, $shiftData, $template);

//        $this->sendEmail('sanat.ahmad@trackingworld.com.pk', $subject, $shiftData, $template);
    }

    private function sendEmail($to, $subject, $data, $template)
    {
        try {
            Mail::send($template, $data, function ($message) use ($to, $subject) {
                $message->to($to)
                    ->subject($subject);
            });

            Log::info('Email send function executed for: ' . $to);
            return true;

        } catch (TransportExceptionInterface $e) {
            Log::info('SMTP Transport Error: ' . $e->getMessage());
            return false;

        } catch (\Exception $e) {
//            dd('General Mail Error: ' . $e->getMessage());
            Log::error("Email sending failed: " . $e->getMessage());
            return false;
        }

    }
}
