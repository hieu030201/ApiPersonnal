<?php

namespace App\Http\Controllers\Api\Excel;

use App\Http\Controllers\Controller;
use App\Mail\SalaryMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ReadExcelController extends Controller
{
    public function ReadSalaryExcel(Request $request)
    {
        $reports = User::all();
        request()->has('mycsv');
        $datas = array_map('str_getcsv', file(request()->mycsv));
        // $encoded_csv = mb_convert_encoding($data, 'UCS-2LE', 'UTF-8');
        unset($datas[0]); // bỏ qua dòng đầu tiên
        return response()->json([
            'code' => 200,
            'data' => $datas,              
        ]);
    }

    public function SendEmailSalary()
    {
        $detail = [
           'title' => '',
           'body' => ''
        ];
        Mail::to("")->send(new SalaryMail($detail));
        Return response()->json([
            'code'=>200,
            'message'=> 'Email Sent'
        ]);

    }
}
