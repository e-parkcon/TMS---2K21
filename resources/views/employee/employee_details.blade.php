@extends('layouts.app')

@section('title', ' - Employee Details')

@section('content')

<div class="row g-2 mt-0">

    @component('components.content_header')
        @slot('title')
            Employee Details
        @endslot
    @endcomponent

    <div class="col-md-12">

        <div class="row g-3">

            <div class="col-md-4">
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-12 col-md-12">
                                        <div class="text-center img_with_btn">
                                            {{-- <img src="{{ asset('user_avatars/' . $user->avatar_user ) }}" class="img-thumbnail" alt="User Image" width="150px"> --}}
                                            <img src="{{ route('fetch.image', [$user->empno]) }}" class="img-thumbnail" alt="User Image" width="150px">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-12 text-center">
                                        <a href="#" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#upload_photo_modal"><small>Edit Photo</small></a>
                                    </div>
                                    <div class="col-12 col-md-12 text-center">
                                        <div class="row">
                                            <div class="col-12 col-md-12">
                                                <h4 class="form-label font-weight-bold">{{ $user->empno }}</h4>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label text-sm">{{ $user->lname }}, {{ $user->fname }} {{ $user->mname }}</label>
                                            </div>
                                            <div class="col-md-6">
                                                <a href="#" id="reset_pass" class="btn btn-sm btn-link"><small>Reset Password</small></a>
                                            </div>
                                            <div class="col-md-6">
                                                <select name="emp_status" id="emp_status" class="form-control form-control-sm form-select form-select-sm">
                                                    <option value="{{ $user->active }}" selected hidden>{{ $user->active == 'Y' ? 'Active' : 'Inactive' }}</option>
                                                    <option value="Y">Active</option>
                                                    <option value="N">Inactive</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <!-- Tab navs -->
                                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                            <a class="nav-link active" id="v-pills-home-tab" data-mdb-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true" >User Information</a>
                                            <a class="nav-link" id="v-pills-profile-tab" data-mdb-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false" 
                                                    empno="{{ $user->empno }}" lv="L" ot="O">
                                                Approving Group <br><br>
                                                <small>Leave & Overtime</small>
                                            </a>
                                            <a class="nav-link" id="v-pills-messages-tab" data-mdb-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false" >Leave Credits</a>
                                        </div>
                                        <!-- Tab navs -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <!-- Tab content -->
                                    <div class="tab-content" id="v-pills-tabContent">
                                        <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">  {{-- PERSONAL INFORMATION --}}
                                            <form action="{{ route('employees.update', $user->empno) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PATCH')
                                                <div class="row g-3">

                                                    <div class="col-md-12">
                                                        <div class="row g-3">
                                                            
                                                            <div class="col-sm-8 col-md-8">
                                                                <label class="form-label text-uppercase text-primary">Personal Information</label>
                                                            </div>
                                                            <div class="col-sm-4 col-md-4">
                                                                <button type="submit" class="btn btn-sm btn-block btn-primary mb-2"><small>Update Details</small></button>
                                                            </div>

                                                            <div class="col-sm-6">
                                                                <select name="user_level" id="user_level" class="selectpicker form-control form-control-sm" required>
                                                                </select>
                                                                <input type="hidden" class="form-control form-control-sm" name="level" id="level" value="{{ $user->level }}" readonly>
                                                            </div>
                                                            
                                                            <div class="col-md-12">
                                                                <div class="form-outline">
                                                                    <input type="text" name="fname" id="fname" class="form-control form-control-sm" value="{{ $user->fname }}"  />
                                                                    <label class="form-label" for="fname">First Name</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-outline">
                                                                    <input type="text" name="mname" id="mname" class="form-control form-control-sm" value="{{ $user->mname }}" required />
                                                                    <label class="form-label" for="mname">Middle Name</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="form-outline">
                                                                    <input type="text" name="lname" id="lname" class="form-control form-control-sm" value="{{ $user->lname }}" required />
                                                                    <label class="form-label" for="lname">Last Name</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-outline">
                                                                    <input type="text" name="suffix" id="suffix" class="form-control form-control-sm" value="{{ Request::old('suffix') }}" />
                                                                    <label class="form-label" for="suffix">Suffix</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-outline">
                                                                    <input type="number" name="phoneNum" id="phoneNum" class="form-control form-control-sm" value="{{ $user->user_mobile_no() }}" required />
                                                                    <label class="form-label" for="phoneNum">Phone Number</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-outline">
                                                                    <input type="email" name="email" id="email" class="form-control form-control-sm" value="{{ $user->email }}"  />
                                                                    <label class="form-label" for="email">Email</label>
                                                                </div>
                                                            </div>

                                                            <div class="col-sm-12 col-md-12">
                                                                <label class="form-label text-uppercase text-primary">Company Information</label>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="row g-2">

                                                                    <div class="col-md-12">
                                                                        <select name="entity01" id="entity01" class="selectpicker form-control form-control-sm" required>
        
                                                                        </select>
                                                                        <input type="hidden" name="cocode" id="cocode" class="form-control form-control-sm" value="{{ $user->entity01 }}" readonly>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <select name="entity02" id="entity02" class="selectpicker form-control form-control-sm" required>
        
                                                                        </select>
                                                                        <input type="hidden" name="distcode" id="distcode" class="form-control form-control-sm" value="{{ $user->entity02 }}" readonly>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <select name="entity03" id="entity03" class="selectpicker form-control form-control-sm"data-dropup-auto="false" data-live-search="true" data-size="5" required>
                                                                            
                                                                        </select>
                                                                        <input type="hidden" name="brchcode" id="brchcode" class="form-control form-control-sm" value="{{ $user->entity03 }}" readonly>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <select name="deptcode" id="deptcode" class="selectpicker form-control form-control-sm" data-dropup-auto="false" data-live-search="true" data-size="5" required>
        
                                                                        </select>
                                                                        <input type="hidden" name="dept_code" id="dept_code" class="form-control form-control-sm" value="{{ $user->deptcode }}" readonly>
                                                                    </div>

                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="row g-2">

                                                                    <div class="col-md-12">
                                                                        <select name="shifts" id="shifts" class="selectpicker form-control form-control-sm" data-live-search="true" data-dropup-auto="false" data-live-search="true" data-size="5">
                                                                        </select>
                                                                        <input type="hidden" name="shift_code" id="shift_code" class="form-control form-control-sm" value="{{ $user->shift }}" readonly>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <div class="form-outline">
                                                                            <input type="text" name="birth_date" id="birth_date" class="form-control form-control-sm" value="{{ $user->birth_date }}"  />
                                                                            <label class="form-label" for="birth_date">Birth date</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <div class="form-outline">
                                                                            <input type="text" name="empl_date" id="empl_date" class="form-control form-control-sm" value="{{ $user->empl_date }}"  />
                                                                            <label class="form-label" for="empl_date">Hired date</label>
                                                                        </div>
                                                                    </div>
                                                                    @if($user->active != 'Y')
                                                                        <div class="col-md-12" id="term">
                                                                            <div class="form-outline">
                                                                                <input type="text" name="term_date" id="term_date" class="form-control form-control-sm" value="{{ $user->term_date }}"  />
                                                                                <label class="form-label" for="term_date">Term date</label>
                                                                            </div>
                                                                        </div>
                                                                    @endif

                                                                </div>
                                                            </div>

                                                        </div>
                                                        
                                                    </div>

                                                </div>
                                                {{-- <div class="row">
                                                    <div class="col-sm-8 col-md-8">
                                                        <label class="form-label text-uppercase text-primary">Personal Information</label>
                                                    </div>
                                                    <div class="col-sm-4 col-md-4">
                                                        <button type="submit" class="btn btn-sm btn-block btn-primary mb-2"><small>Update Details</small></button>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="row g-3">
                                                            <div class="col-sm-6">
                                                                <select name="user_level" id="user_level" class="selectpicker form-control form-control-sm" required>
                                                                </select>
                                                                <input type="hidden" class="form-control form-control-sm" name="level" id="level" value="{{ $user->level }}" readonly>
                                                            </div>
                            
                                                            <div class="col-md-12">
                                                                <div class="form-outline">
                                                                    <input type="text" name="fname" id="fname" class="form-control form-control-sm" value="{{ $user->fname }}"  />
                                                                    <label class="form-label" for="fname">First Name</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-outline">
                                                                    <input type="text" name="mname" id="mname" class="form-control form-control-sm" value="{{ $user->mname }}" required />
                                                                    <label class="form-label" for="mname">Middle Name</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="form-outline">
                                                                    <input type="text" name="lname" id="lname" class="form-control form-control-sm" value="{{ $user->lname }}" required />
                                                                    <label class="form-label" for="lname">Last Name</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-outline">
                                                                    <input type="text" name="suffix" id="suffix" class="form-control form-control-sm" value="{{ Request::old('suffix') }}" />
                                                                    <label class="form-label" for="suffix">Suffix</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-outline">
                                                                    <input type="number" name="phoneNum" id="phoneNum" class="form-control form-control-sm" value="{{ $user->user_mobile_no() }}" required />
                                                                    <label class="form-label" for="phoneNum">Phone Number</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-outline">
                                                                    <input type="email" name="email" id="email" class="form-control form-control-sm" value="{{ $user->email }}"  />
                                                                    <label class="form-label" for="email">Email</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12 mt-4">
                                                        <label class="form-label text-uppercase text-primary">Company Information</label>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="row g-2">
                                                            <div class="col-md-12">
                                                                <select name="entity01" id="entity01" class="selectpicker form-control form-control-sm" required>

                                                                </select>
                                                                <input type="hidden" name="cocode" id="cocode" class="form-control form-control-sm" value="{{ $user->entity01 }}" readonly>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <select name="entity02" id="entity02" class="selectpicker form-control form-control-sm" required>

                                                                </select>
                                                                <input type="hidden" name="distcode" id="distcode" class="form-control form-control-sm" value="{{ $user->entity02 }}" readonly>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <select name="entity03" id="entity03" class="selectpicker form-control form-control-sm" required>
                                                                    
                                                                </select>
                                                                <input type="hidden" name="brchcode" id="brchcode" class="form-control form-control-sm" value="{{ $user->entity03 }}" readonly>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <select name="deptcode" id="deptcode" class="selectpicker form-control form-control-sm" required>

                                                                </select>
                                                                <input type="hidden" name="dept_code" id="dept_code" class="form-control form-control-sm" value="{{ $user->deptcode }}" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="row g-3">
                                                            <div class="col-md-12">
                                                                <select name="shifts" id="shifts" class="selectpicker form-control form-control-sm" data-live-search="true" >
                                                                </select>
                                                                <input type="hidden" name="shift_code" id="shift_code" class="form-control form-control-sm" value="{{ $user->shift }}" readonly>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-outline">
                                                                    <input type="text" name="birth_date" id="birth_date" class="form-control form-control-sm" value="{{ $user->birth_date }}"  />
                                                                    <label class="form-label" for="birth_date">Birth date</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-outline">
                                                                    <input type="text" name="empl_date" id="empl_date" class="form-control form-control-sm" value="{{ $user->empl_date }}"  />
                                                                    <label class="form-label" for="empl_date">Hired date</label>
                                                                </div>
                                                            </div>
                                                            @if($user->active != 'Y')
                                                            <div class="col-md-12" id="term">
                                                                <div class="form-outline">
                                                                    <input type="text" name="term_date" id="term_date" class="form-control form-control-sm" value="{{ $user->term_date }}"  />
                                                                    <label class="form-label" for="term_date">Term date</label>
                                                                </div>
                                                            </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div> --}}
                                            </form>
                                        </div> {{-- PERSONAL INFORMATION --}}

                                        <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab" > {{-- APPROVING GROUP --}}
                                            <!-- <form action=""> -->
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-12">
                                                        <label class="form-label text-uppercase text-primary">Approving Group</label>
                                                        {{-- <hr> --}}
                                                    </div>
                                                    
                                                    <div class="col-md-12">
                                                        <div class="row g-3">
                                                            <div class="col-sm-8 col-md-8 p-2">
                                                                <label class="form-label text-uppercase">Leave Approving Group</label>
                                                            </div>
                                                            <div class="col-sm-4 col-md-4">
                                                                <a href="#" class="btn btn-sm btn-block btn-primary" empno="{{ $user->empno }}" id="lv_app_group"><small>Set Approving Group</small></a>
                                                            </div>
                                                            <div class="col-md-12 m-0">
                                                                <div class="table-responsive" id="lv_appgrp_div">
                                                                    <div class="lv_appgrp_div_2">
                                                                        
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-sm-8 col-md-8 p-2">
                                                                <label class="form-label text-uppercase">Overtime Approving Group</label>
                                                            </div>
                                                            <div class="col-sm-4 col-md-4">
                                                                <a href="#" class="btn btn-sm btn-block btn-primary" empno="{{ $user->empno }}" id="ot_app_group"><small>Set Approving Group</small></a>
                                                            </div>
                                                            <div class="col-md-12 m-0">
                                                                <div class="table-responsive" id="ot_appgrp_div">
                                                                    <div class="ot_appgrp_div_2">
                                                                        
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <!-- </form> -->
                                        </div> {{-- APPROVING GROUP --}}

                                        <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab" > {{-- LEAVE CREDITS --}}
                                            <div class="row">
                                                <div class="col-sm-12 col-md-12">
                                                    <label class="form-label text-uppercase text-primary">Leave Credits</label>
                                                </div>
                                                <div class="col-6 col-md-6">
                                                    <select name="leave_type" id="leave_type" class="form-control form-control-sm form-select form-select-sm">
                                                        <option selected hidden disabled>Choose Leave Type</option>
                                                        @foreach($leave_types as $lv_type)
                                                            <option value="{{ $lv_type->lv_code }}" no_days="{{ $lv_type->no_days }}">{{ $lv_type->lv_desc }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-3 col-md-3">
                                                    <input type="text" name="no_days" id="no_days" class="form-control form-control-sm text-center" readonly>
                                                </div>
                                                <div class="col-3 col-md-3">
                                                    <a href="#" name="btn_save_lv_type" id="btn_save_lv_type" class="btn btn-sm btn-block btn-primary"><small>Add Leave</small></a>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-sm text-uppercase mt-3" id="tbl_user_lv" style="width: 100%;">
                                                            <thead>
                                                                <tr>
                                                                    <th>Leave Type</th>
                                                                    <th># of days</th>
                                                                    <th>Leave Balance</th>
                                                                    <th class="text-center">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>

                                            </div>
                                        </div> {{-- LEAVE CREDITS --}}
                                    </div>
                                    <!-- Tab content -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<div class="modal fade" id="upload_photo_modal" tabindex="-1" aria-labelledby="uploadPhotoForm" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <form action="{{ route('uploadPhoto.empno', [$user->empno]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            {{-- @method('PATCH') --}}
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-sm font-weight-bold" id="uploadPhotoForm">Change Photo</h5>
                    <button type="button" class="close text-sm" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <img class="user-pic" id="blah" src="{{ asset('images/user.png') }}" class="img-fluid rounded" alt="User Image" width="150px">
                        </div>
                        <div class="col-md-12 mt-3">
                            <input type="file" name="avatar" id="avatar" class="form-control form-control-sm" onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">
                            {{-- <label for="avatar">Choose Photo</label> --}}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row g-1">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-sm btn-primary"><small>Upload Photo</small></button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    var empno = '{{ $user->empno }}';
</script>
<script src="{{ asset('js/user_details.js') }}"></script>
<script src="{{ asset('js/approving_group.js') }}"></script>

@endsection