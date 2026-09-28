<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Settings;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use function Symfony\Component\Translation\t;

class SettingsController extends Controller
{
    public function getSysConfig(Request $request)
    {
        // Fetch the system configuration settings
        $config =  DB::table('SysConfig')->first();

        // Return the configuration as a JSON response
        return response()->json($config);
    }
    public function updateAll(Request $request)
    {
        $data = $request->all();
        foreach ($data['data'] as  $item) {

            (object) $item;
            // Check if the setting already exists
            if (isset($item['id'])) {
                $setting = Settings::find($item['id']);
            } else {
                $setting = new Settings();
            }

            // Assign the key and description
            $setting->key = $item['key'] ?? $setting->key;
            $setting->description = $item['description'];

            // Handle different types of settings
            switch ($item['type']) {
                case 'text':
                case 'number':
                    $setting->value = $item['value'];
                    break;

                case 'bool':
                    $setting->value = $item['value'] ? '1' : '0';
                    break;

                case 'file':
                    if ($item['value'] && $item['value'] instanceof \Illuminate\Http\UploadedFile) {
                        // Store the file and save the path
                        $file = $item['value'];
                        $path = $file->store('settings_files', 'public');
                        $setting->value = $path;
                    }
                    break;

                default:
                    break;
                    return response()->json(['error' => 'Invalid type provided'], 400);
            }

            // Save the type and save the setting
            $setting->type = $item['type'];
            $setting->save();
        }

        return response()->json(['success' => 'Settings updated successfully']);
    }

    public function getAll(Request $request){
        $settings = DB::table('settings')->get()->toArray();
        $sysConf=DB::table('SysConfig')->first();

//        return response()->json($settings);
        return response()->json(['settings' => $settings,
            'sysConfig' => $sysConf]);
    }

    public function get($key){
        return response()->json(Settings::where('key', $key)->first());
    }
    public function getAllSettings(){
        return response()->json(DB::table('settings')->get());
    }
    public function updateAllConfig(Request $request)
    {
        $type = $request->input('type');
        $data = $request->input('data');
        try {
            switch ($type) {
                case 'email':
                    if (!isset($data['enable'])) {
                        return response()->json(['status' => 'error', 'message' => 'Missing email_enable value'], 400);
                    }
                    DB::table('SysConfig')->update([
                        'email_enable' => (int) $data['enable'],
                    ]);
                    break;

                case 'screen':
                    if (!isset($data['lock'])) {
                        return response()->json(['status' => 'error', 'message' => 'Missing is_screen_lock value'], 400);
                    }
//                    is_screenlock disable ho to
                    $lockValue = (int) $data['lock'];

                    // If screen lock is being disabled
                    if ($lockValue === 0) {
                        Cache::forget('system_locked');
                        Cache::forget('bypass_active_until');
                        Cache::forget('shift_minutes_remaining');
                    }
                    DB::table('SysConfig')->update([
                        'is_screen_lock' => $lockValue,
                    ]);
                    break;

                case 'shift':
                    if (!isset($data['selected'])) {
                        return response()->json(['status' => 'error', 'message' => 'Missing shift selection'], 400);
                    }

                    $selected = (int) $data['selected'];

                    if ($selected === 1) {
                        DB::table('SysConfig')->update([
                            'NoofShifts' => 1,
                            'Shift1Code' => 6,
                            'Shift2Code' => 0,
                            'Shift3Code' => 0,
                        ]);
                    } elseif ($selected === 2) {
                        DB::table('SysConfig')->update([
                            'NoofShifts' => 2,
                            'Shift1Code' => 4,
                            'Shift2Code' => 5,
                            'Shift3Code' => 0,
                        ]);
                    } elseif ($selected === 3) {
                        DB::table('SysConfig')->update([
                            'NoofShifts' => 3,
                            'Shift1Code' => 1,
                            'Shift2Code' => 2,
                            'Shift3Code' => 3,
                        ]);
                    } else {
                        return response()->json(['status' => 'error', 'message' => 'Invalid shift selection'], 400);
                    }
                    break;

                default:
                    return response()->json(['status' => 'error', 'message' => 'Invalid config type'], 400);
            }

            return response()->json(['status' => 'success', 'message' => ucfirst($type) . ' updated successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ], 500);
        }
    }


}
