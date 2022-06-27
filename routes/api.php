<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Management\AbsenceController;
use App\Http\Controllers\Api\Management\BeingLateController;
use App\Http\Controllers\Api\Management\ReasonController;
use App\Http\Controllers\Api\Management\StatusController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum','verified')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'auth'], function(){
    //Chi admin moi co the truy cap
    Route::get('/listUser', [App\Http\Controllers\Api\AuthController::class,'listUser']);
    Route::post('/register', [App\Http\Controllers\Api\AuthController::class,'register'])->middleware('auth:sanctum')->middleware(['checkAdmin']);
    Route::post('/editUser', [App\Http\Controllers\Api\AuthController::class,'editUser'])->middleware('auth:sanctum')->middleware('checkAdmin');//->middleware('auth')->middleware(['checkAdmin'])
    Route::get('/getuser/{id}', [App\Http\Controllers\Api\AuthController::class,'getUser'])->middleware('auth:sanctum')->middleware('checkAdmin');//->middleware('auth')->middleware(['checkAdmin'])
    Route::post('/searchUser', [App\Http\Controllers\Api\AuthController::class,'searchUser'])->middleware('auth:sanctum')->middleware('checkAdmin');//->middleware('auth')->middleware(['checkAdmin'])
    // admin vs personnel deu co quyen truy cap
    Route::post('/login', [App\Http\Controllers\Api\AuthController::class,'login']);
    Route::post('/logout',[App\Http\Controllers\Api\AuthController::class,'logout'])->middleware('auth:sanctum');
    Route::get('/me',[App\Http\Controllers\Api\AuthController::class,'me'])->middleware('auth:sanctum');
    Route::post('/activeFollow/{id}', [App\Http\Controllers\Api\Management\AbsenceController::class, 'sendAbsence'])->middleware('auth:sanctum');
});

// Route::middleware(['auth:sanctum'])->group(function(){
//     Route::resource('status','Api\StatusController');
//     Route::resource('level','Api\levelController');
// });

//gửi thông báo vs kích hoạt tk 
Route::post('/email/verification-notification', [App\Http\Controllers\Api\EmailVerificationController::class, 'sendVerificationEmail'])->middleware('auth:sanctum');
Route::get('/verify-email/{id}/{hash}', [App\Http\Controllers\Api\EmailVerificationController::class, 'verify'])->name('verification.verify');
Route::post('/forgot-password', [App\Http\Controllers\Api\VerifyPasswordController::class, 'forgotPassword']);
Route::post('/verify-password/{token}', [App\Http\Controllers\Api\VerifyPasswordController::class, 'verifyPass']);

//
Route::group(['prefix' => 'management'], function(){
    Route::resource('/manage-reason', ReasonController::class)->middleware('checkAdmin');
    Route::resource('/manage-beinglate', BeingLateController::class)->middleware('checkAdmin');
    Route::resource('/manage-absence', AbsenceController::class)->middleware('auth:sanctum');
    Route::post('/send-absence/{id}', [App\Http\Controllers\Api\Management\AbsenceController::class, 'sendAbsence'])->middleware('auth:sanctum');
    Route::get('/admin-list-absence', [App\Http\Controllers\Api\Management\AbsenceController::class, 'adminList'])->middleware('auth:sanctum')->middleware('checkAdmin');
    // Route::get('/searchAbsenceOfUser/{id}', [App\Http\Controllers\Api\Management\AbsenceController::class, 'adminList'])->middleware('checkAdmin');
    //Route::get('/searchAbsenceOfDate', [App\Http\Controllers\Api\Management\AbsenceController::class, 'searchAbsenceOfDate'])->middleware('auth:sanctum')->middleware('checkAdmin');
    Route::post('/searchAbsenceOfDate', [App\Http\Controllers\Api\Management\AbsenceController::class, 'searchAbsenceOfDate'])->middleware('auth:sanctum')->middleware('checkAdmin');
});

Route::group(['prefix' => 'export'], function(){
    Route::get('/' , [App\http\Controllers\Api\Excel\AbsenceExportController::class, 'export']);
    Route::post('/export-by-time' , [App\http\Controllers\Api\Excel\AbsenceExportController::class, 'exportByTime']);
    Route::get('/export-by-user' , [App\http\Controllers\Api\Excel\AbsenceExportController::class, 'exportByUser']);
});

Route::post('/read-salary-excel' , [App\http\Controllers\Api\Excel\ReadExcelController::class, 'ReadSalaryExcel']);
Route::get('/send-email-salary' , [App\http\Controllers\Api\Excel\ReadExcelController::class, 'SendEmailSalary']);





