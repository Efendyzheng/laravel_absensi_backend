<?php

namespace App\Http\Controllers\Api;

use App\Helpers\SendNotificationHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    //checkin
    public function checkin(Request $request)
    {

        //validate lat and long
        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        //save new attendance
        $attendance = new Attendance;
        $attendance->user_id = $request->user()->id;
        $attendance->date = date('Y-m-d');
        $attendance->time_in = date('H:i:s');
        $attendance->latlon_in = $request->latitude . ',' . $request->longitude;
        $attendance->save();

        return response([
            'message' => 'Checkin success',
            'attendance' => $attendance
        ], 200);
    }

    //checkout
    public function checkout(Request $request)
    {
        //validate lat and long
        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        //get today attendance
        $attendance = Attendance::where('user_id', $request->user()->id)
            ->where('date', date('Y-m-d'))
            ->first();

        //check if attendance not found
        if (!$attendance) {
            return response(['message' => 'Checkin first'], 400);
        }

        //save checkout
        $attendance->time_out = date('H:i:s');
        $attendance->latlon_out = $request->latitude . ',' . $request->longitude;
        $attendance->save();

        return response([
            'message' => 'Checkout success',
            'attendance' => $attendance
        ], 200);
    }

    //check is checkedin
    public function isCheckedin(Request $request)
    {
        //get today attendance
        $attendance = Attendance::where('user_id', $request->user()->id)
            ->where('date', date('Y-m-d'))
            ->first();

        $isCheckout = $attendance ? $attendance->time_out : false;

        return response([
            'checkedin' => $attendance ? true : false,
            'checkedout' => $isCheckout ? true : false,
        ], 200);
    }

    //index
    public function index(Request $request)
    {
        $date = $request->input('date');

        $currentUser = $request->user();

        $query = Attendance::where('user_id', $currentUser->id);

        if ($date) {
            $query->where('date', $date);
        }

        $attendance = $query->get();

        return response([
            'message' => 'Success',
            'data' => $attendance
        ], 200);
    }


    public function getHistory(Request $request)
    {
        $currentUserId = $request->user()->id;

        $query = "
            SELECT date, MAX(reason) as reason, MAX(is_approved) as is_approved,
                IF(MAX(time_in) <= :companyTimeIn
                AND MAX(time_out) >= :companyTimeOut, true, false) as full_time,
                MAX(time_in) as time_in, MAX(time_out) as time_out,
                Max(:timeIn) as company_time_in,  Max(:timeOut) as company_time_out
                FROM (
                SELECT attendances.date as date, permissions.reason as reason,
                permissions.is_approved as is_approved,
                attendances.time_in as time_in,
                attendances.time_out as time_out
                FROM attendances
                LEFT JOIN permissions ON attendances.date = permissions.date and
                attendances.user_id = permissions.user_id
                WHERE attendances.user_id = :userId
                UNION
                SELECT permissions.date, permissions.reason as reason, permissions.is_approved as is_approved,
                NULL as time_in, NULL as time_out
                from permissions LEFT JOIN attendances
                ON permissions.date = attendances.date and permissions.user_Id = attendances.user_id
                WHERE permissions.user_id = :userId2
            ) as combined
            GROUP BY date
            ORDER BY date;
        ";

        $datas = DB::select($query, [
            'companyTimeIn' => Company::first()->time_in . ":00",
            'companyTimeOut' => Company::first()->time_out . ":00",
            'timeIn' => Company::first()->time_in,
            'timeOut' => Company::first()->time_out,
            'userId' => $currentUserId,
            'userId2' => $currentUserId,
        ]);


        return response([
            'message' => 'Success',
            'data' => $datas
        ], 200);
    }
}
