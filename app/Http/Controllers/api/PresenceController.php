<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\PermitEmployee;
use App\Models\Presence;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PresenceController extends Controller
{
    public function getPresenceToday()
    {
        $userId = Auth::user()->id;
        $today = now()->toDateString();
        $currentMonth = now()->month;

        $presenceToday = Presence::select('start_date_time', 'end_date_time')
                                ->where('created_by_id', $userId)
                                ->whereDate('created_at', $today)
                                ->first();

        $presenceThisMonth = Presence::select('start_date_time', 'end_date_time', 'created_at')
                                ->where('created_by_id', $userId)
                                ->whereMonth('created_at', $currentMonth)
                                ->get()
                                ->map(function ($attendance) {
                                    return [
                                        'start_date_time' => $attendance->start_date_time,
                                        'end_date_time' => $attendance->end_date_time,
                                        'date' => $attendance->created_at->toDateString()
                                    ];
                                });

        return response()->json([
            'success' => true,
            'data' => [
                'today' => $presenceToday,
                'this_month' => $presenceThisMonth
            ],
            'message' => 'Success get attendance today'
        ]);
    }

    public function getEmployee()
    {
        // $schedule = Schedule::with(['office', 'shift'])
        //                 ->where('user_id', auth()->user()->id)
        //                 ->first();

        $employee = Employee::where('user_id', Auth::user()->id)->first();

        $today = Carbon::today()->format('Y-m-d');
        $approvedLeave = PermitEmployee::where('created_by_id', Auth::user()->id)
                            ->where('status', '2')
                            ->whereDate('from_date', '<=', $today)
                            ->whereDate('until_date', '>=', $today)
                            ->exists();

        if ($approvedLeave) {
            return response()->json([
                'success' => true,
                'message' => 'Anda tidak dapat melakukan presensi karena sedang cuti',
                'data' => null
            ]);
        }

        if ($employee->is_banned) {
            return response()->json([
                'success' => false,
                'message' => 'You are banned',
                'data' => null
            ]);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'Success get schedule',
                'data' => $employee
            ]);
        }

    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'store_id' => 'required',
            'shift_store_id' => 'required',
            'store_start_latitude' => 'required|numeric',
            'store_start_longitude' => 'required|numeric',
            'shift_start_time' => 'required',
            'shift_end_time' => 'required',
            'start_latitude' => 'required|numeric',
            'start_longitude' => 'required|numeric',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'data' => $validator->errors()
            ], 422);
        }

        $presence = Presence::where('created_by_id', Auth::user()->id)
                            ->whereDate('created_at', date('Y-m-d'))->first();

        if (!$presence) {
            $presence = Presence::create([
                'created_by_id' => Auth::user()->id, // ok
                'store_id' => $request->store_id, // ok
                'shift_store_id' => $request->shift_store_id, // ok
                'status' => 1,
                'store_start_latitude' => $request->store_start_latitude, // ok
                'store_start_longitude' => $request->store_start_longitude, // ok
                'shift_start_time' => $request->shift_start_time, // ok
                'shift_end_time' => $request->shift_end_time, // ok
                'start_latitude' => $request->start_latitude, // ok
                'start_longitude' => $request->start_longitude, // ok
                'start_date_time' => Carbon::now(), // ok
            ]);
        } else {
            $presence->update([
                'store_end_latitude' => $request->store_end_latitude,
                'store_end_longitude' => $request->store_end_longitude,

                'end_latitude' => $request->end_latitude,
                'end_longitude' => $request->end_longitude,
                'end_date_time' => Carbon::now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Success presence',
            'data' => $presence
        ]);
    }

    public function getPresenceByMonthYear($month, $year)
    {
        $validator = Validator::make(['month' => $month, 'year' => $year], [
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2023|max:'.date('Y')
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'data' => $validator->errors()
            ], 422);
        }

        $userId = Auth::user()->id;
        $presenceList = Presence::select('start_date_time', 'end_date_time', 'created_at')
                                ->where('created_by_id', $userId)
                                ->whereMonth('created_at', $month)
                                ->whereYear('created_at', $year)
                                ->get()
                                ->map(function ($presence) {
                                    return [
                                        'start_date_time' => $presence->start_date_time,
                                        'end_date_time' => $presence->end_date_time,
                                        'date' => $presence->created_at->toDateString()
                                    ];
                                });

        return response()->json([
            'success' => true,
            'data' => $presenceList,
            'message' => 'Success get attendance by month and year'
        ]);

    }

    public function banned()
    {
        $schedule = Employee::where('user_id', Auth::user()->id)->first();
        if ($schedule) {
            $schedule->update([
                'is_banned' => true
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Success banned schedule',
            'data' => $schedule
        ]);
    }

    public function getImage()
    {
        $user = Auth::user();
        return response()->json([
            'success' => true,
            'message' => 'Success get image',
            'data' => $user->image_url
        ]);
    }
}
