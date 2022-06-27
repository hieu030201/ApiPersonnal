<?php

namespace App\Http\Controllers\Api\Management;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AbsenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $absences = Absence::where('user_id', $user->id)->get();
        return response()->json([
            'code' => 200,
            'data' => $absences
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'reason_id'=>'required',
            'startDay' => 'required',
            'endDay' => 'required',
            'beingLate_id' => 'required',
        ]); 

            $user = Auth::user();
            $absences = new Absence();
            $absences->user_id = $user->id;
            $absences->user_name = $user->name;
            $absences->reason_id = $request->reason_id;
            $absences->startDay = $request->startDay;
            $absences->endDay = $request->endDay;
            $absences->description = $request->description;
            $absences->beingLate_id = $request->beingLate_id;
            if($absences->save()){
                return response()->json([
                    'code'=>200,
                    'data'=>$absences
                ]);
            }   
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'reason_id'=>'required',
            'startDay' => 'required',
            'endDay' => 'required',
            'beingLate_id' => 'required',
        ]);

            $user = Auth::user();
            // $user = $request->user();
            $absences = Absence::find($id);
            $absences->user_id = $user->id;
            $absences->user_name = $user->name;
            $absences->reason_id = $request->reason_id;
            $absences->startDay = $request->startDay;
            $absences->endDay = $request->endDay;
            $absences->description = $request->description;
            $absences->beingLate_id = $request->beingLate_id;
    
            if($absences->save()){
                return response()->json([
                    'code'=>200,
                    'data'=>$absences
                ]);
            }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Absence::destroy($id);
        return response(['message' => 'Deleted']);
    }

    public function sendAbsence($id)
    {
        $absence = Absence::find($id)->get();
        if(!$absence)
        {
            return response()->json(['message'=>'Send Faild!']);
        }else
        {
            $absence = Absence::where('id', $id)->update(['status'=>'Send']);
        }
        return response()->json([
            'code'=>200,
            'message'=>'successfully sent'
        ]);
    }

    public function adminList()
    {
        $absences = Absence::where('status', 'Send')->get();
        return response()->json([
            'code'=>200,
            'data'=>$absences
        ]);
    }

    public function searchAbsenceOfUser(Request $request)
    {
        $user_name = $request->get('user_name');
        $data = Absence::where('status', 'Send')->where('user_name','LIKE','%'.$user_name.'%')->paginate(5);
        return response()->json([
            'code' => 200,
            'data' => $data,
        ]);
    }

    public function searchAbsenceOfDate(Request $request)
    {
        $start = $request->start_date; //'2022-03-22'; Carbon::parse($request->start_date);
        $end =  $request->end_date; //'2022-03-25'; Carbon::parse($request->end_date);
        if(!$end){
            $end = $start;
        }
        $get_all_absence = Absence::where('status', 'Send')->where('endDay','<=',$end)
        ->where('startDay','>=',$start)
        ->get();
        return response()->json([
            'code' => 200,
            'data' => $get_all_absence,
        ]);
        
    }
}
