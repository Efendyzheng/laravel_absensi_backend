<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Company;
use App\Models\Permission;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $userCount = User::count();

        $newPermissionCount = Permission::whereNull('is_approved')->count();


        $company = Company::first();
        $companyTimeIn = $company->time_in . ":00";
        $companyTimeOut = $company->time_out . ":00";
        $date = Carbon::now();
        $month = $date->format('m');
        $year = $date->format('Y');


        $totalUserComeLate = Attendance::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->whereTime('time_in', '>', $companyTimeIn)
            ->count();

        $totalUserBackEarly = Attendance::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->whereTime('time_out', '<', $companyTimeOut)
            ->count();

        $permissions = Permission::orderBy('id', 'desc')->get();

        // Chart counting how many user not working on full hours
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $datesOfWeek = [];
        $currentDate = $startOfWeek->copy();
        while ($currentDate->lte($endOfWeek)) {
            $datesOfWeek[] = $currentDate->format('Y-m-d');
            $currentDate->addDay();
        }

        $query = 'distinct date, count(id) as count';
        $notFullAttendances = Attendance::whereBetween('date', [$startOfWeek, $endOfWeek])
            ->where(function ($condition) use ($companyTimeIn, $companyTimeOut) {
                $condition->whereTime('time_in', '>', $companyTimeIn)
                    ->orWhereTime('time_out', '<', $companyTimeOut);
            })
            ->select(DB::raw($query))
            ->groupBy('date')
            ->pluck('count', 'date');

        $results = [];
        foreach ($datesOfWeek as $date) {
            $results[] = [
                'date' => $date,
                'count' => $notFullAttendances->get($date, 0),
            ];
        }
        $chartAttendances = json_encode($results);

        return view('pages.dashboard', compact([
            'userCount',
            'newPermissionCount',
            'totalUserComeLate',
            'totalUserBackEarly',
            'permissions',
            'chartAttendances'
        ]));
    }
}
