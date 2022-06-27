<?php

namespace App\Exports;

use App\Models\Absence;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;


class AbsenceExportByTime implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $start = request()->end_date;
        $end = request()->start_date;
       return Absence::where('status','Send')
       ->where('endDay','<=',$end)
       ->where('startDay','>=',$start)
       ->select('id','user_id','user_name','reason_id','description','startDay','endDay','beingLate_id')->get();
    }
    
    public function headings() :array 
    {
    	return ["ID", "User_id", "Tên người dùng","Lý do nghỉ","Nội dung","Từ ngày","Đến ngày","Buổi nghỉ"];
    }
}
