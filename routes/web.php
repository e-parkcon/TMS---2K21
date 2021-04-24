<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
|
|   PROGRAMMER : EMIL PARCON
|
*/

// use App\Jobs\SendLeaveMail;
// use App\Mail\LeaveMailable;
// use App\Mail\OTMailable;
// use App\Models\Web_Menu;
// use Carbon\Carbon;

use App\Models\Employee_Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Models\Overtime;
use Intervention\Image\ImageManagerStatic as Image;

Route::get('/', function () {
    return view('auth.login');
}); 

Auth::routes();

Route::get('/verifyUserOTP', 'OTPController@verify_otp_page')->middleware('auth');
Route::post('/verifyUserOTP', 'OTPController@verify_otp')->name('vertifyOTP');

Route::group(['middleware' =>  ['TwoFactorAuth', 'auth']], function () {

    Route::get('/update/pdf_ot_col', function(){

        $overtime   =   Overtime::whereBetween('dateot', ['2020-01-01', '2020-12-31'])
                            ->where('submitted', 'Y')
                            ->where('pdf_file_ot', '!=', '')->get();

        foreach($overtime as $ot){

            $year       = date('Y', strtotime($ot->timestart));
            $month      = date('m', strtotime($ot->timestart));
            $day        = date('d', strtotime($ot->timestart));

            $filees =   array('/uploaded_files/Overtime/' . $year .'/' . $month . '/' . $day . '/' . $ot->pdf_file_ot);

            Overtime::where('empno', $ot->empno)
                            ->where('refno', $ot->refno)
                            ->update(['pdf_file_ot'   =>  json_encode($filees, JSON_FORCE_OBJECT)]);
        }

    });

    Route::get('/fetch/image/{empno}', 'EmployeeListsController@fetch_image')->name('fetch.image');

    // Route::get('/sendMail', function(){
    //     dispatch(new SendLeaveMail('Emil Parcon'));
    //     // dispatch(new SendLeaveMail())->delay(Carbon::now()->addSeconds(5)); 
    //     return 'test';
    // });

    // Route::get('/overtimeMail', function(){
    //     return new OTMailable();
    // });

    Route::get('/health_declaration', 'HealthDeclaration@health_declaration_page')->name('health_declaration.page');
    Route::post('/health_declaration', 'HealthDeclaration@gen_qrcode_info')->name('health_declaration.save');
    Route::get('/download/qr_code', 'HealthDeclaration@download_qrcode_pdf')->name('download_qr');

    Route::get('/home', 'HomeController@index')->name('home');

    Route::get('/leave', 'LeaveController@leave_dashboard')->name('lv_dashboard');
    Route::post('/leave', 'LeaveController@leave_post')->name('lv_post');
    Route::post('/update/leave', 'LeaveController@leave_update')->name('lv_update');
    Route::post('/cancel/leave', 'LeaveController@leave_cancel')->name('leave_cancel');
    Route::get('/leave/leave_ledger', 'LeaveController@leave_ledger')->name('leave.leave_ledger');
    Route::get('/leave_ledger-pdf', 'LeaveController@lv_ledger_pdf');
    Route::get('/leave-exports', 'LeaveController@leave_ledger_csv');

    Route::get('/overtime', 'OvertimeController@ot_dashboard')->name('ot_dashboard');
    Route::get('/overtime/lists/{refno}', 'OvertimeController@overtime_lists')->name('ot_lists');
    Route::post('/overtime', 'OvertimeController@overtime_post')->name('ot_post');
    Route::get('/overtime/add-new/{refno}', 'OvertimeController@add_new_ot')->name('add_ot_form');
    Route::post('/overtime/add-new/{refno}', 'OvertimeController@post_new_ot')->name('post_newOT');
    Route::post('/update/overtime/{refno}', 'OvertimeController@update_overtime')->name('update_overtime');
    Route::delete('/delete/overtime', 'OvertimeController@delete_overtime')->name('delete_ot');
    Route::delete('/delete/all/ot/{refno}', 'OvertimeController@delete_all_ot')->name('delete_allOT');
    Route::delete('/delete/attachment/{refno}/{ot_id}', 'OvertimeController@delete_file_uploaded')->name('ot.delete_attachment');
    Route::post('/submit/overtime', 'OvertimeController@submit_overtime')->name('submit_ot');

    // EMPLOYEE
    Route::group(['middleware' => ['UserListAuth']], function(){
        Route::get('/employees_lists', 'EmployeeListsController@employeeListResponse');
        Route::put('/employees/reset/{empno}', 'EmployeeListsController@reset_password');
        Route::put('/employess/change/status/{empno}', 'EmployeeListsController@user_change_status');
        Route::get('/employees/lv_credits/{empno}', 'EmployeeListsController@user_lv_credits');
        Route::post('/employees/lv_credits/{empno}', 'EmployeeListsController@save_leave_credits');
        Route::post('/change_photo/{empno}', 'EmployeeListsController@upload_photo')->name('uploadPhoto.empno');
        Route::put('/edit/lv/balance/{empno}', 'EmployeeListsController@edit_lv_balance')->name('edit.lv_balance');
        Route::resource('employees', 'EmployeeListsController');

        // EMPLOYEE APPROVING 
        Route::get('/approving_groups', 'ApprovingController@approving_groups');
        Route::get('/app_group/officers', 'ApprovingController@approving_officers');
        Route::resource('approving', 'ApprovingController');
    });

    // USER OWN DETAILS
    Route::get('/user/details', 'EmployeeListsController@auth_user_details')->name('auth.details');
    Route::patch('/user/details', 'EmployeeListsController@update_info')->name('update.information');
    Route::patch('/change/password', 'EmployeeListsController@change_password')->name('change.password');

    // LOGS
    // DAILY LOGS
    Route::get('/daily_logs', 'DailyLogsController@daily_logs')->name('daily_logs');
    Route::get('/logs-report', 'DailyLogsController@pdf_logs');
    Route::get('/inout_logs/{txndate}/{empno}', 'DailyLogsController@in_out_logs')->name('inout_logs');

    Route::group(['middleware' => 'DTRAuth'], function(){
        // DTR TRANSACTION
        Route::get('/dtr_transac/{from}/{to}/{empno}', 'DTRController@dtr_trans')->name('logs.dtr');
        Route::patch('/maintenance/update/ob/dtr', 'DTRController@ob_dtr')->name('dtr_update');
        Route::get('/change_shift/{from}/{to}/{empno}', 'DTRController@change_shift')->name('change_shift');
        Route::patch('/maintenance/update/shift', 'DTRController@update_shift')->name('update_shift');
        Route::get('/empno', 'DTRController@empno_list');
    });

    Route::group(['middleware' => 'INQAuth'], function(){
        // INQUIRY ATTENDANCE
        Route::get('/inquiry/attendance_inquiry', 'InquiryController@attendance_inquiry');
        Route::get('/json/attendance_inquiry', 'InquiryController@attendance_inquiry_json');
        // Route::get('/inquiry/branchlog', 'InquiryController@branch_log')->name('branchlog');
        Route::get('/brch_log/{fromdate}/{todate}/{company}/{branch}', 'InquiryController@showBrchlog')->name('brch_log');
        Route::get('/comp_inq/{company}', 'InquiryController@clickComp');
        Route::get('/dist_inq/{company}/{district}', 'InquiryController@clickDist');
        Route::get('/brchlog_inq/{company}', 'InquiryController@clickBrchLog');
        Route::get('/dept_inq/{company}', 'InquiryController@clickDept');

        Route::get('/inquiry/summ_app_lv', 'LeaveSummary@leave_summ_dashboard')->name('summ_appleave');

        // BRANCH LOGS
        Route::get('/inquiry/branch_log', 'InquiryController@branch_log');
        Route::get('/json/branch_attlog', 'InquiryController@branch_log_json');
    });

    // APPROVAL
    // PENDING
    Route::group(['middleware' => ['ApprovalAuth']], function(){
        Route::get('/pending_lv', 'PendingController@pending_leave_page')->name('pending_lv');
        Route::get('/pending_lv/lv_details/{lv_id}/{empno}', 'PendingController@leave_details')->name('leave_details');
        Route::patch('/lv_period/{lv_id}/{empno}', 'PendingController@update_lv_period')->name('update_lvPeriod');
        
        Route::patch('/approve/user/leave/{lv_id}/{empno}', 'PendingController@approve_leave');
        Route::patch('/disapprove/user/leave/{lv_id}/{empno}', 'PendingController@disapprove_leave');
    
        Route::patch('/approve/selected_ot/{refno}/{empno}', 'PendingController@approve_selected_ot')->name('pending.approve_selected_ot');
        Route::patch('/disapprove/selected_ot/{refno}/{empno}', 'PendingController@disapprove_selected_ot')->name('pending.disapprove_selected_ot');
        Route::patch('/edit_hrs/overtime/{refno}/{ot_id}/{empno}', 'PendingController@edit_ot_hours');
        Route::patch('/disapprove/overtime/{refno}/{ot_id}/{empno}', 'PendingController@disapprove_one_overtime');
    
        Route::get('/pending_ot', 'PendingController@pending_ot_page')->name('pending_ot_header');
        Route::get('/pending_ot/ot_lists/{refno}/{empno}', 'PendingController@pending_ot_list')->name('otList_pending');
    });

    // APPROVED LEAVES
    Route::group(['middleware' => 'AdminAuth'], function(){
        Route::get('/approve_leave', 'PendingController@approved_leave_dashboard')->name('approve_leaves');
        Route::get('/approve_leave/details/{lv_id}/{empno}', 'PendingController@approve_leave_details')->name('approve_leaves.details');
        Route::patch('/approve/with_pay/{lv_id}/{empno}', 'PendingController@approve_with_pay');
        Route::patch('/approve/without_pay/{lv_id}/{empno}', 'PendingController@approve_without_pay');
    });

    // STATUS
    Route::get('/status/{otlv_id}', 'StatusController@status');

    // REPORTS
    Route::group(['middleware' => 'ReportsAuth'], function(){
        Route::get('/leave_summary', 'LeaveSummary@leave_summ_dashboard')->name('lv_summary');
        Route::get('/lv_report', 'LeaveSummary@generate_pdf_lv_summary')->name('lv_pdf'); //PDF
        Route::get('/lv_csv', 'LeaveSummary@lv_csv')->name('lv_csv'); //CSV
        Route::get('/overtime_summary', 'OvertimeSummary@ot_summary_dashboard')->name('ot_summary');
        Route::get('/overtime_summary/details/{refno}/{empno}', 'OvertimeSummary@ot_summ_details')->name('ot_summary.details');
        Route::get('/ot_pdf', 'OvertimeSummary@overtime_summary_pdf')->name('ot_summ_pdf'); // PDF
        Route::get('/ot_csv', 'OvertimeSummary@ot_summ_csv')->name('ot_csv'); //CSV
        Route::get('/health_reports', 'HealthDeclaration@health_declaration_report');
        Route::get('/lv_form/{id}/{empno}', 'LeaveController@lv_form'); //Print Leave Form
        Route::get('/ot_form/{refno}/{ot_id}/{empno}', 'OvertimeController@ot_form'); //Print OT Form
    });

    // UTILITIES
    Route::group(['prefix' => 'utilities', 'middleware' => 'UtilitiesAuth'], function(){
        // APPROVING GROUP
        // Route::get('/officers', 'ApprovingController@approving_officers_dropdown');
        Route::get('/appgrp_json_list', 'AjaxController@approving_group_json_list')->name('app_group.json_list');
        Route::post('/approving_group/add/officer/{app_code}', 'ApprovingGroupController@add_new_officer');
        Route::patch('/approving_group/change/officer/{app_code}', 'ApprovingGroupController@change_officer');
        Route::delete('/approving_group/remove/officer/{app_code}', 'ApprovingGroupController@remove_officer');
        Route::post('/approving_group/add/member/{app_code}/{otlv}', 'ApprovingGroupController@add_new_member');
        Route::delete('/approving_group/remove/member/{app_code}', 'ApprovingGroupController@remove_member');
        Route::resource('approving_group', 'ApprovingGroupController'); 

        //DISTRICT & BRANCHES
        Route::get('/district/list', 'District_BranchController@district_list');
        Route::resource('district', 'District_BranchController');

        // DEPARTMENT
        Route::get('/department/list', 'DepartmentController@department_list');
        Route::resource('department', 'DepartmentController');

        // IMPORTING FILES
        Route::post('/import_lvcredits', 'ImportingFilesController@import_lv_credits')->name('import.lv_credits');
        Route::resource('import_files', 'ImportingFilesController');

        // USER LEVEL
        Route::get('/user_menus/{level}', 'UserLevelController@role_menus');
        Route::resource('user_level', 'UserLevelController');
    });
    
    //AJAX
    Route::get('/lv_list', 'LeaveController@leave_list')->name('leave_list');
    Route::get('/ot_header', 'OvertimeController@ot_header')->name('overtime_header');

    Route::get('/lv/pending', 'PendingController@pending_leave_data');
    Route::get('/ot/pending', 'PendingController@pending_ot_data');
    Route::get('/approve_lv', 'PendingController@app_lv_dtls');

    Route::get('/lv_summ', 'LeaveSummary@leave_list');
    Route::get('/ot_summ', 'OvertimeSummary@ot_summ_list');

    Route::get('/ot_attachments/{refno}/{ot_id}/{empno}', 'AjaxController@view_overtime_attachments');
    Route::get('/attachment/display/{url}', 'AjaxController@pdf_display');

    Route::get('/entity01', 'AjaxController@company');
    Route::get('/entity02', 'AjaxController@district');
    Route::get('/entity03', 'AjaxController@branch');
    Route::get('/deptcode', 'AjaxController@department');

    Route::get('/shifts', 'AjaxController@shift');
    Route::get('/shift_today', 'OvertimeController@todayShift')->name('today_shift');
    
    Route::get('/dayoff', 'DailyLogsController@dayoff')->name('dayoff');
    Route::get('/holidays', 'DailyLogsController@holidays')->name('holidays');
    Route::get('/userlevel', 'AjaxController@user_level')->name('userlevel');

    // COMPONENTS
    Route::get('my-components', function(){
        return view('components');
    });

});