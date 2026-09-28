<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AtgEmailController extends Controller
{
    // GET all emails
    public function index()
    {

        return DB::table('alarm_email_settings')
            ->orderBy('id','desc')
            ->get();
    }

    // POST new email
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        DB::table('alarm_email_settings')->insert([
            'email' => $request->email,
            'is_active' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json(['message'=>'Email Added']);
    }

    // UPDATE checkbox
    public function update(Request $request, $id)
    {
        DB::table('alarm_email_settings')
            ->where('id',$id)
            ->update([
                'is_active' => $request->is_active,
                'updated_at' => now()
            ]);

        return response()->json(['message'=>'Updated']);
    }

    // DELETE email
    public function destroy($id)
    {
        DB::table('alarm_email_settings')
            ->where('id',$id)
            ->delete();

        return response()->json(['message'=>'Deleted']);
    }
    public function vendor()
    {
        return DB::table('VENDORS')
            ->get();
    }
}
