<?php

namespace App\Http\Controllers\Api\Excel;

use App\Exports\AbsenceExport;
use App\Exports\AbsenceExportByTime;
use App\Exports\AbsenceExportByUser;
use App\Http\Controllers\Controller;
use App\Models\Absence;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AbsenceExportController extends Controller
{
    public function export(){
        return Excel::download(new AbsenceExport, 'AbsenceExport.xlsx');
    }

    public function exportByTime(Request $request){

        // $start = $request->start_date; //'2022-03-22'; Carbon::parse($request->start_date);
        // $end =  $request->end_date; //'2022-03-25'; Carbon::parse($request->end_date);
        // if(!$end){
        //     $end = $start;
        // }
        // $get_all_absence = Absence::where('status', 'Send')->where('endDay','<=',$end)
        // ->where('startDay','>=',$start)
        // ->get();
        // if(!$get_all_absence){
            return Excel::download(new AbsenceExportByTime, 'AbsenceExportByTime.xlsx');
        // }else{
        //     return 1;
        // }
    }

    public function exportByUser(Request $request){
        return Excel::download(new AbsenceExportByUser, 'AbsenceExportByUser.xlsx');
    }
}
