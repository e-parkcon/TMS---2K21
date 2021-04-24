@extends('layouts.app')

@section('title', ' - User Information')

@section('content')
    
<div class="row g-2 mt-0">

    @component('components.content_header')
        @slot('title')
            User Information
        @endslot
    @endcomponent

    <div class="col-md-12">
        <div class="row g-3">

            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <!-- Tab navs -->
                                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                            <a class="nav-link active" id="v-pills-home-tab" data-mdb-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true" >User Information</a>
                                            <a class="nav-link" id="v-pills-profile-tab" data-mdb-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false" 
                                                    empno="{{ Auth::user()->empno }}" lv="L" ot="O">
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
                                    <div class="col-3 col-md-3">
                                        <div class="row">
                                            <div class="col-md-12 text-center">
                                                <img src="{{ route('fetch.image', [Auth::user()->empno]) }}" class="img-thumbnail" alt="User Image" width="100px">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-9 col-md-9">
                                        <div class="row">
                                            <div class="col-12 col-md-12">
                                                <h4 class="form-label font-weight-bold">{{ Auth::user()->empno }}</h4>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label text-sm">{{ Auth::user()->lname }}, {{ Auth::user()->fname }} {{ Auth::user()->mname }}</label>
                                            </div>
                                            <div class="col-md-12">
                                                {{-- <a href="#" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#upload_photo_modal"><small>Edit Photo</small></a> --}}
                                                <a href="#" class="btn btn-sm btn-link" data-toggle="modal" data-target="#changePass"><small>Change Password</small></a>
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
                                    <!-- Tab content -->
                                    <div class="tab-content" id="v-pills-tabContent">
                                        <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">  {{-- PERSONAL INFORMATION --}}
                                            <form action="{{ route('update.information') }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <div class="row g-2">
                                                    <div class="col-sm-8 col-md-8">
                                                        <label class="form-label text-uppercase text-primary">Personal Information</label>
                                                    </div>
                                                    <div class="col-sm-4 col-md-4">
                                                        {{-- <a href="#" class="btn btn-sm btn-block btn-primary"><small>Update Details</small></a> --}}
                                                        <button type="submit" class="btn btn-sm btn-block btn-primary mb-2"><small>Update Details</small></button>
                                                    </div>
                                                    <div class="col-md-12 m-0">
                                                        <div class="table-responsive text-sm">
                                                            <table class="table table-sm table-borderless" style="width: 100%">
                                                                <tr>
                                                                    <td width="25%" class="text-uppercase font-weight-bold">Full Name <span class="float-right">:</span></td>
                                                                    <td class="text-uppercase">{{ $user['fullname'] }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td width="25%" class="text-uppercase font-weight-bold">Email <span class="float-right">:</span></td>
                                                                    <td class="">
                                                                        {{-- {{ $user['email'] }} --}}
                                                                        <input type="email" name="email" id="email" class="form-control form-control-sm" value="{{ $user['email'] }}"  />
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td width="25%" class="text-uppercase font-weight-bold">Mobile # <span class="float-right">:</span></td>
                                                                    <td class="text-uppercase">
                                                                        {{-- {{ $user['mobile_no'] }} --}}
                                                                        <input type="number" name="phoneNum" id="phoneNum" class="form-control form-control-sm" value="{{ $user['mobile_no'] }}" required />
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <label class="form-label text-uppercase text-primary">Company Information</label>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <div class="row g-2">
                                                            <div class="table-responsive text-sm">
                                                                <table class="table table-sm table-borderless" style="width: 100%">
                                                                    <tr>
                                                                        <td width="45%" class="text-uppercase font-weight-bold">Company <span class="float-right">:</span></td>
                                                                        <td class="text-uppercase">{{ $user['company'] }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td width="45%" class="text-uppercase font-weight-bold">District <span class="float-right">:</span></td>
                                                                        <td class="text-uppercase">{{ $user['district'] }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td width="45%" class="text-uppercase font-weight-bold">Branch <span class="float-right">:</span></td>
                                                                        <td class="text-uppercase">{{ $user['branch'] }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td width="45%" class="text-uppercase font-weight-bold">Department <span class="float-right">:</span></td>
                                                                        <td class="text-uppercase">{{ $user['department'] }}</td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <div class="row g-2">
                                                            <div class="table-responsive text-sm">
                                                                <table class="table table-sm table-borderless" style="width: 100%">
                                                                    <tr>
                                                                        <td width="30%" class="text-uppercase font-weight-bold">Shift <span class="float-right">:</span></td>
                                                                        <td class="text-uppercase">{{ $user['shift'] }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td width="30%" class="text-uppercase font-weight-bold">Hired Date <span class="float-right">:</span></td>
                                                                        <td class="text-uppercase">{{ date('F d, Y', strtotime($user['hired_date'])) }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td width="30%" class="text-uppercase font-weight-bold">Birth date <span class="float-right">:</span></td>
                                                                        <td class="text-uppercase">{{ date('F d, Y', strtotime($user['birth_date'])) }}</td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab" > {{-- APPROVING GROUP --}}
                                            <div class="row">
                                                <div class="col-sm-12 col-md-12">
                                                    <label class="form-label text-uppercase text-primary">Approving Group</label>
                                                    {{-- <hr> --}}
                                                </div>
                                                
                                                <div class="col-md-12">
                                                    <div class="row g-2">
                                                        <div class="col-sm-8 col-md-8 p-2">
                                                            <label class="form-label text-uppercase">Leave Approving Group</label>
                                                        </div>
                                                        <div class="col-md-12 mt-0">
                                                            <div class="table-responsive" id="lv_appgrp_div">
                                                                <div class="lv_appgrp_div_2">
                                                                    
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-8 col-md-8 p-2">
                                                            <label class="form-label text-uppercase">Overtime Approving Group</label>
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
                                        </div>
                                        <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab" > {{-- LEAVE CREDITS --}}
                                            <div class="row">
                                                <div class="col-sm-12 col-md-12">
                                                    <label class="form-label text-uppercase text-primary">Leave Credits</label>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-sm text-uppercase mt-3" id="tbl_user_lv" style="width: 100%;">
                                                            <thead>
                                                                <tr>
                                                                    <th>Leave Type</th>
                                                                    <th># of days</th>
                                                                    <th>Leave Balance</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>


        </div>
    </div>

</div>

<div class="modal fade" id="changePass">
	<div class="modal-dialog modal-sm modal-dialog-centered" role="dialog">
		<div class="modal-content">

			<form method="post" action="{{ route('change.password') }}">
				{{ csrf_field() }}
				{{ method_field('patch') }}

				<div class="modal-header">
					<h6 class="modal-title text-sm font-weight-bold">Change Password</h6>
					<button type="button" class="close text-sm" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				</div>

				<div class="modal-body">
					<div class="row g-1">
						<div class="col-md-12">
							<label class="form-label"><small>New Password :</small></label>
							<input type="password" name="password" id="password" class="form-control form-control-sm">
							<input type="hidden" name="pass_empno" id="pass_empno" class="form-control form-control-sm">
						</div>
						<div class="col-md-12">
							<label class="form-label"><small>Confirm Password :</small></label>
							<input type="password" name="password_confirmation" id="password_confirmation" class="form-control form-control-sm">
						</div>
						<div class="col-md-12 text-right">
                            <p style="font-size: 12px;">Note: Minimum of six(6) characters</p>
                        </div>
					</div>
				</div>

				<div class="modal-footer">
					<div class="row g-1">
						<div class="col-12">
                            <button type="submit" class="btn btn-sm btn-sm btn-primary"><small>Change Password</small></button>
							<button type="button" class="btn btn-sm btn-sm btn-danger" data-dismiss="modal"><small>Close</small></button>
						</div>
					</div>
				</div>

			</form>

		</div>
	</div>
</div>

<div class="modal fade" id="upload_photo_modal" tabindex="-1" aria-labelledby="uploadPhotoForm" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <form action="{{ route('uploadPhoto.empno', [Auth::user()->empno]) }}" method="POST" enctype="multipart/form-data">
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
                            <img class="user-pic" id="blah" src="{{ asset('images/user.png') }}" class="img-fluid rounded" alt="User Image" height="150px" width="150px">
                        </div>
                        <div class="col-md-12 mt-3">
                            <input type="file" name="avatar" id="avatar" class="form-control form-control-sm" onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">
                        </div>
                        <div class="col-md-12 text-right">
                            <p class="text-danger" style="font-size: 12px;"><i>Note: Please upload a decent photo.</i></p>
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
    var empno = '{{ $user['empno'] }}';
</script>
<script src="{{ asset('js/approving_group.js') }}"></script>
<script src="{{ asset('js/user_details.js') }}"></script>
@endsection