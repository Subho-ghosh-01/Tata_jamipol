<?php

namespace App\Http\Controllers;

use App\Department;
use App\Division;
use Illuminate\Http\Request;
use App\UserLogin;
use App\JobLinking;
use DB;
use Session;

class SettingsController extends Controller
{

    public function index()
    {
        $divisions = Division::all();
        $setting_vendor_attendance = DB::table('settings')->where('type', 'attendance_last_day')->orderBy('id', 'desc')->get();
        $setting_vendor_esic = DB::table('settings')->where('type', 'esic_last_day')->orderBy('id', 'desc')->get();
        $setting_vendor_pf = DB::table('settings')->where('type', 'pf_last_day')->orderBy('id', 'desc')->get();
        $setting_bonus = DB::table('settings')->where('type', 'bonus_month')->orderBy('id', 'desc')->get();
        $setting_half_yearly1 = DB::table('settings')->where('type', 'half_yearly1')->orderBy('id', 'desc')->get();
        $setting_half_yearly2 = DB::table('settings')->where('type', 'half_yearly2')->orderBy('id', 'desc')->get();
        return view('admin.settings_master.index', compact('setting_vendor_attendance', 'setting_vendor_esic', 'setting_vendor_pf', 'setting_bonus', 'setting_half_yearly1', 'setting_half_yearly2'));
    }


    public function create()
    {

    }


    public function store(Request $request)
    {
        $request->validate([
            'type_name' => 'required|string',
        ]);

        $map = [
            'attendance' => 'attendance_last_day',
            'esic' => 'esic_last_day',
            'pf' => 'pf_last_day',
            'bonus_month' => 'bonus_month',
            'half_yearly' => 'half_yearly', // general key for two dates
        ];

        if (!array_key_exists($request->type_name, $map)) {
            return response()->json(['message' => 'Invalid setting type'], 400);
        }

        if ($request->type_name === 'half_yearly') {
            // Validate inputs
            $request->validate([
                'half_yearly1' => 'required|date_format:Y-m',
                'half_yearly2' => 'required|date_format:Y-m',
            ]);

            DB::table('settings')->updateOrInsert(
                ['type' => 'half_yearly1'],
                [
                    'value' => $request->half_yearly1,
                    'updated_date' => now(),
                    'updated_by' => Session::get('user_idSession')
                ]
            );

            DB::table('settings')->updateOrInsert(
                ['type' => 'half_yearly2'],
                [
                    'value' => $request->half_yearly2,
                    'updated_date' => now(),
                    'updated_by' => Session::get('user_idSession')
                ]
            );

            return response()->json(['message' => 'Setting updated successfully']);
        }

        // For other types
        DB::table('settings')->where('type', $map[$request->type_name])->update([
            'value' => $request->value,
            'updated_date' => now(),
            'updated_by' => Session::get('user_idSession')
        ]);

        return response()->json(['message' => 'Setting updated successfully']);
    }



    public function show(Department $department)
    {
        //
    }


    public function edit($skillIDenc)
    {


    }


    public function update(Request $request)
    {
        $type = $request->type_name;

        if ($type == 'attendance') {
            Setting::where('key', 'attendance_last_day')->update(['value' => $request->attendance_last_day]);
        } elseif ($type == 'esic') {
            Setting::where('key', 'esic_last_day')->update(['value' => $request->esic_last_day]);
        } elseif ($type == 'pf') {
            Setting::where('key', 'pf_last_day')->update(['value' => $request->pf_last_day]);
        } elseif ($type == 'bonus_return') {
            Setting::where('key', 'bonus_return')->update(['month' => $request->bonus_month]);
        }

        return back()->with('message', ucfirst($type) . ' updated successfully.');
    }
    public function updateSetting(Request $request)
    {
        $request->validate([
            'type_name' => 'required|string',
            'value' => 'required|integer|min:1|max:31',
        ]);

        $map = [
            'attendance' => 'attendance_last_day',
            'esic' => 'esic_last_day',
            'pf' => 'pf_last_day'
        ];

        if (!array_key_exists($request->type_name, $map)) {
            return response()->json(['message' => 'Invalid setting type'], 400);
        }

        DB::table('settings')->where('type', $map[$request->type_name])->update([
            'value' => $request->value,
            'updated_at' => now()
        ]);

        return response()->json(['message' => 'Setting updated successfully']);
    }


    public function destroy($skill)
    {
        date_default_timezone_set('Asia/Kolkata'); // or your preferred timezone
        $date = date('Y-m-d H:i:s');
        if ($skill) {

            $skill1 = DB::table('skill_clms')->where('id', $skill)->update([
                'is_deleted' => 'Y',
                'updated_by' => Session::get('user_idSession'),
                'updated_date' => $date
            ]);
            if ($skill1) {
                return back()->with('message', 'Skill Delete Successfully');
            } else {
                return back()->with('message', 'Error While Skill Delete');

            }
        }

    }

}
