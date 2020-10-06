<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;

class ScheduleController extends Controller
{
    public function newSchedule(Request $request){

        $schedule = new Schedule;
        $schedule->description = $request->description;
        $schedule->month = $request->month;
        $schedule->day = $request->day;
        $schedule->save();

        $response = array(
            'status' => 'success',
            'msg' => "Successfully added new schedule!"
        );

        return response()->json($response);
    }

    public function getSchedule(Request $request){

        $schedules = Schedule::where('month', $request->month)->where('day', $request->day)->get();

        $response = array(
            'status' => 'success',
            'msg' =>  $schedules
        );

        return response()->json($response);
    }
}
