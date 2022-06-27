<?php

namespace App\Exports;

use App\Models\Absence;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AbsenceExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Absence::where('status','Send')->select('id','user_id','user_name','reason_id','description','startDay','endDay','beingLate_id')->get();
    }
    public function headings() :array {
    	return ["ID", "User_id", "Tên người dùng","Lý do nghỉ","Nội dung","Từ ngày","Đến ngày","Buổi nghỉ"];
    }
}
