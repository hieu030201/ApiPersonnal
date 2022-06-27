<?php

namespace App\Exports;

use App\Models\Absence;
use Maatwebsite\Excel\Concerns\FromCollection;

class AbsenceExportByUser implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $search = request()->search;
        return Absence::where('status', 'Send')
        ->where('user_name','LIKE','%'.$search.'%' || 'email','LIKE','%'.$search.'%')
        ->select('id','user_id','user_name','reason_id','description','startDay','endDay','beingLate_id')
        ->get();
    }
    public function headings() :array 
    {
    	return ["ID", "User_id", "Tên người dùng","Lý do nghỉ","Nội dung","Từ ngày","Đến ngày","Buổi nghỉ"];
    }
}
