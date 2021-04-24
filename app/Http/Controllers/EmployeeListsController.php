<?php

namespace App\Http\Controllers;

use App\Http\Controllers\traits\Employee;
use App\Http\Controllers\traits\Leave_Type;
use App\Http\Requests\Employee as RequestsEmployee;
use App\Http\Requests\ImageValidation;
use App\Http\Requests\UpdateEmployee;
use App\Models\ApprovingGroup;
use App\Models\ApprovingOfficers;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Department;
use App\Models\District;
use App\Models\Employee_Image;
use App\Models\LeaveCredits;
use App\Models\PIF;
use App\Models\Shift;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManagerStatic as Image;
use Session;
use Auth;
use COM;
use Response;

class EmployeeListsController extends Controller
{

    use Employee;
    use Leave_Type;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){

        return view('employee.employee_lists');
    }

    public function employeeListResponse(){
        
        return response()->json(array('data'=>$this->employeeLists()));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('employee.add_new_employee');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RequestsEmployee $request)
    {
        //
        try{
            $user           =   new User();
            $user->empno    =   $request->empno;
            $user->shift    =   $request->shifts;
            $user->level    =   $request->user_level;
            $user->fname    =   $request->fname;
            $user->mname    =   $request->mname;
            $user->lname    =   $request->lname;
            $user->email    =   $request->email;
            $user->password =   Hash::make($request->password);
            $user->active    =   'Y';
            $user->entity01      =   $request->entity01;
            $user->entity02      =   $request->entity02;
            $user->entity03      =   $request->entity03;
            $user->deptcode      =   $request->deptcode;
            $user->birth_date    =   $request->birth_date;
            $user->empl_date     =   $request->empl_date;
            $user->crypt    =   $this->user_crypt($request->empno);
            $user->save();
    
            $pif                =   new PIF();
            $pif->empno         =   $request->empno;
            $pif->fname         =   $request->fname;
            $pif->mname         =   $request->mname;
            $pif->lname         =   $request->lname;
            $pif->namesuffix    =   !empty($request->suffix) ? $request->suffix : '';
            $pif->no            =   '';
            $pif->street        =   '';
            $pif->brgy_village  =   '';
            $pif->city_municipality =   '';
            $pif->province      =   '';
            $pif->region        =   '';
            $pif->zipcode       =   '';
            $pif->sex           =   '';
            $pif->civil_status  =   '';
            $pif->emp_type      =   '';
            $pif->active        =   'Y';
            $pif->birth_date    =   $request->birth_date; 
            $pif->home_no       =   ''; 
            $pif->mobile_no     =   $request->phoneNum;
            $pif->email         =   '';
            $pif->otp           =   !empty($request->phoneNum) ? 'Y' : 'N';
            $pif->blood_type    =   '';
            $pif->nationality   =   '';
            $pif->empl_date     =   $request->empl_date;
            $pif->term_date     =   '';
            $pif->username      =   $request->empno;
            $pif->password      =   Hash::make($request->password);
            $pif->cocode        =   $request->entity01;
            $pif->brchcode      =   $request->entity02;
            $pif->deptcode      =   $request->deptcode;
            $pif->save();

            Session::flash('success', 'New employee successfully added.');

        }catch(Exception $e){
            
            return back()->withErrors('Something went wrong.');
        }

        return redirect()->route('employees.show', ['empno' => $request->empno]);
    }

    private function user_crypt($empno){ //FOR APPROVING ID
        return  $empno . '-' . uniqid();
    }

    public function user_change_status(Request $request, $empno){

        try {

            $user   =   User::find($empno);
            $user->active   =   $request->status;
            $user->update();
    
            $user   =   PIF::find($empno);
            $user->active   =   $request->status;
            $user->update();
    
            return response()->json('success');

        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function reset_password($empno){ 

        try {
            $password_string    =   '123456';

            $user   =   User::find($empno);
            $user->password =   Hash::make($password_string);
            $user->update();
    
            $user_pif   =   PIF::find($empno);
            $user_pif->password =   Hash::make($password_string);
            $user_pif->update();

            return response()->json('success');
        } catch (\Exception $e) {
            //throw $th;
            return response()->json($e->getMessage());
        }
    }

    public function user_lv_credits($empno){
        
        $lv_credits =   $this->empl_lv_credits($empno);

        return response()->json([$lv_credits, Auth::user()->level]);
    }

    public function save_leave_credits(Request $request, $empno){

        try {
            
            LeaveCredits::insert([
                'empno' =>  $empno,
                'lv_code'   =>  $request->lv_type,
                'no_days'   =>  $request->no_days,
            ]);

        } catch (\Exception $e) {
            // dd($e->getMessage());
            return response()->json('Duplicate Entry!');
        }

        return response()->json('success');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($empno)
    {
        //
        $user   =   User::find($empno);

        $leave_types    =   $this->leave_type();

        return view('employee.employee_details', ['user' => $user, 'leave_types' => $leave_types]);
    }

    public function auth_user_details(){
        
        $user   =   User::find(Auth::user()->empno);

        $user_dtl   =   [];
        $user_dtl['empno']      =   $user->empno;
        $user_dtl['fullname']   =   $user->lname . ', ' . $user->fname . $user->namesuffix . ' ' . $user->mname;
        $user_dtl['email']      =   $user->email;
        $user_dtl['mobile_no']  =   $user->user_mobile_no();
        $user_dtl['company']    =   Company::where('entity01', $user->entity01)->first()->entity01_desc;
        $user_dtl['district']   =   District::where('entity01', $user->entity01)->where('entity02', $user->entity02)->first()->entity02_desc;
        $user_dtl['branch']     =   Branch::where('entity01', $user->entity01)->where('entity02', $user->entity02)->where('entity03', $user->entity03)->first()->entity03_desc;
        $user_dtl['department'] =   Department::where('entity01', $user->entity01)->where('deptcode', $user->deptcode)->first()->deptdesc;
        $user_dtl['shift']      =   Shift::where('shift', $user->shift)->first()->desc;
        $user_dtl['hired_date'] =   $user->empl_date;
        $user_dtl['birth_date'] =   $user->birth_date;

        return view('employee.user_details', ['user' => $user_dtl]);
    }

    public function update_info(Request $request){

        $this->validate($request, [
            'email'     =>  'required|email',
            'phoneNum' =>  'required|numeric',
        ]);

        $empno  =   Auth::user()->empno;
        $user           =   User::find($empno);
        $user->email    =   $request->email;
        $user->update();

        PIF::updateOrCreate(['empno' =>  $empno], 
            [
                'mobile_no' =>  $request->phoneNum,
            ]);

        Session::flash('success', 'Information successfully updated.');
        return back();
    }

    public function change_password(Request $request){
        $this->validate($request, [
            'password' => 'required|confirmed|min:6',
        ]);

        $user   =   User::findOrFail(Auth::user()->empno);
        $user->password =   Hash::make($request->password);
        $user->update();


        Session::flash('success', 'Password Successfully Updated!');
        return back();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEmployee $request, $empno)
    {
        //
        try{

            $user           =   User::find($empno);
            $user->fname    =   $request->fname;
            $user->mname    =   $request->mname;
            $user->lname    =   $request->lname;
            $user->level    =   $request->user_level;
            $user->email    =   $request->email;
            $user->entity01 =   $request->entity01;
            $user->entity02 =   $request->entity02;
            $user->entity03 =   $request->entity03;
            $user->deptcode =   $request->deptcode;
            $user->shift    =   $request->shifts;
            $user->birth_date =   $request->birth_date;
            $user->empl_date =   $request->empl_date;
            $user->update();

            PIF::updateOrCreate(['empno' =>  $empno], 
                [
                    'empno'     =>  $empno,
                    'fname'     =>  $request->fname,
                    'mname'     =>  $request->mname,
                    'lname'     =>  $request->lname,
                    'namesuffix'    =>  !empty($request->suffix) ? $request->suffix : '',
                    'email'     =>  $request->email,
                    'mobile_no' =>  $request->phoneNum,
                    'cocode'    =>  $request->entity01,
                    'brchcode'  =>  $request->entity02,
                    'deptcode'  =>  $request->deptcode,
                    'username'  =>  $empno,
                    'active'    =>  'Y',
                    'birth_date'=>  $request->birth_date,
                    'empl_date' =>  $request->empl_date,
                ]);

        }catch(Exception $e){

            return back()->withErrors('Something went wrong.');
        }
        
        Session::flash('success', 'Information successfully updated.');
        return back();
    }

    public function upload_photo(ImageValidation $reqImg, $empno){

        if($reqImg->has('avatar')){

            // Update avatar column in emp_master
            $user   =   User::find($empno); // Find User empno

            $image  =   $reqImg->file('avatar');
            $file_ext   =   $image->getClientOriginalExtension();

            $filename  = $empno . '.' . $file_ext; // Get file extension
            $dir_path = public_path('user_avatars').'/'.$filename; // Directory path
            
            $img    =   Image::make($image->getRealPath())->resize(200, 200, function($constraint){
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        })->save($dir_path); // Image resize & save to path

            Response::make($img->encode($file_ext));

            // Update or Insert Data to DB
            Employee_Image::updateOrCreate(['empno' => $empno],['emp_image' =>  $img]);

            // Update avatar column in emp_master
            $user->avatar_user  =   $filename;
            $user->update();

            Session::flash('success', 'Image uploaded.');
        }

        return back();
    }

    public function fetch_image($empno){
        $img    =   Employee_Image::findOrFail($empno);

        $image  =   Image::make($img->emp_image);

        $response   =   Response::make($image->encode('jpeg'));

        $response->header('Content-Type', 'image/jpeg');

        return $response;
    }

    public function edit_lv_balance(Request $request, $empno){

        LeaveCredits::where('empno', $empno)->where('lv_code', $request->lv_code)
                    ->update([
                        'lv_balance'    =>  $request->lv_balance
                    ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
