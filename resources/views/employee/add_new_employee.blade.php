@extends('layouts.app')

@section('title', ' - New Employee')

@section('content')

<div class="row g-2 mt-0">

    @component('components.content_header')
        @slot('title')
            Add New Employee
        @endslot
    @endcomponent

    <div class="col-md-12">

        <div class="row g-3">

            <div class="col-md-12">

                <div class="card">

                    <div class="card-header">
                        <a href="{{ route('employees.index') }}" class="btn btn-sm btn-danger"><small>Back</small></a>
                        <a class="btn btn-sm btn-primary" href="{{ route('employees.store') }}"
                            onclick="event.preventDefault();
                                            document.getElementById('employee_form').submit();">
                            <small>Save New Employee</small>
                        </a>
                    </div>

                    <div class="card-body">

                        <form action="{{ route('employees.store') }}" method="POST" id="employee_form" enctype="multipart/form-data">
                            @csrf
        
                            <div class="row g-3">

                                <div class="col-md-6">
                                    <div class="row g-2">
                                        
                                        <div class="col-md-12">
                                            <h6 class="form-label text-uppercase text-primary">Personal Information</h6>
                                        </div>
                                        
                                        <div class="col-sm-6 col-md-6">
                                            <div class="form-outline">
                                                <input type="text" name="empno" id="empno" class="form-control form-control-sm" value="{{ Request::old('empno') }}" required />
                                                <label class="form-label" for="empno">ID #</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-6">
                                            <select name="user_level" id="user_level" class="selectpicker form-control form-control-sm" required>
                                                
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-12">
                                            <div class="form-outline">
                                                <input type="text" name="fname" id="fname" class="form-control form-control-sm" value="{{ Request::old('fname') }}" required />
                                                <label class="form-label" for="fname">First Name</label>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-outline">
                                                <input type="text" name="mname" id="mname" class="form-control form-control-sm" value="{{ Request::old('mname') }}" required />
                                                <label class="form-label" for="mname">Middle Name</label>
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="form-outline">
                                                <input type="text" name="lname" id="lname" class="form-control form-control-sm" value="{{ Request::old('lname') }}" required />
                                                <label class="form-label" for="lname">Last Name</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-outline">
                                                <input type="text" name="suffix" id="suffix" class="form-control form-control-sm" value="{{ Request::old('suffix') }}" required />
                                                <label class="form-label" for="suffix">Suffix</label>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-outline">
                                                <input type="number" name="phoneNum" id="phoneNum" class="form-control form-control-sm" value="{{ Request::old('phoneNum') }}" required />
                                                <label class="form-label" for="phoneNum">Phone Number</label>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-outline">
                                                <input type="email" name="email" id="email" class="form-control form-control-sm" value="{{ Request::old('email') }}"  />
                                                <label class="form-label" for="email">Email</label>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-outline">
                                                <input type="password" name="password" id="password" class="form-control form-control-sm    " value="{{ Request::old('password') }}" required />
                                                <label class="form-label" for="password">Password</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
        
                                <div class="col-md-6">
                                    <div class="row g-2">

                                        <div class="col-md-12">
                                            <h6 class="form-label text-uppercase text-primary">Company Information</h6>
                                        </div>

                                        <div class="col-md-12">
                                            <select name="entity01" id="entity01" class="selectpicker form-control form-control-sm" required>
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <select name="entity02" id="entity02" class="selectpicker form-control form-control-sm" required>
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <select name="entity03" id="entity03" class="selectpicker form-control form-control-sm" data-live-search="true" data-size="5" required>
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <select name="deptcode" id="deptcode" class="selectpicker form-control form-control-sm" data-live-search="true" data-size="5" required>
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <select name="shifts" id="shifts" class=" selectpicker form-control form-control-sm" data-live-search="true" data-size="10" required>
                                                <option selected disabled>Select Shift</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-outline">
                                                <input type="text" name="birth_date" id="birth_date" class="form-control form-control-sm" value="{{ Request::old('birth_date') }}"  />
                                                <label class="form-label" for="birth_date">Birth date</label>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-outline">
                                                <input type="text" name="empl_date" id="empl_date" class="form-control form-control-sm" value="{{ Request::old('empl_date') }}"  />
                                                <label class="form-label" for="empl_date">Hired date</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
        
                            </div>
                            
                            {{-- <div class="row">
                                
                                <div class="col-md-12">
                                    <h6 class="form-label text-uppercase text-primary">Personal Information</h6>
                                </div>
        
                                <div class="col-md-6">
                                    <div class="row g-3">
                                        <div class="col-sm-6 col-md-6">
                                            <div class="form-outline">
                                                <input type="text" name="empno" id="empno" class="form-control form-control-sm" value="{{ Request::old('empno') }}" required />
                                                <label class="form-label" for="empno">ID #</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-6">
                                            <select name="user_level" id="user_level" class="selectpicker form-control form-control-sm" required>
                                                <option selected disabled>User Level</option>
                                            </select>
                                        </div>
        
                                        <div class="col-md-12">
                                            <div class="form-outline">
                                                <input type="text" name="fname" id="fname" class="form-control form-control-sm" value="{{ Request::old('fname') }}" required />
                                                <label class="form-label" for="fname">First Name</label>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-outline">
                                                <input type="text" name="mname" id="mname" class="form-control form-control-sm" value="{{ Request::old('mname') }}" required />
                                                <label class="form-label" for="mname">Middle Name</label>
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="form-outline">
                                                <input type="text" name="lname" id="lname" class="form-control form-control-sm" value="{{ Request::old('lname') }}" required />
                                                <label class="form-label" for="lname">Last Name</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-outline">
                                                <input type="text" name="suffix" id="suffix" class="form-control form-control-sm" value="{{ Request::old('suffix') }}" required />
                                                <label class="form-label" for="suffix">Suffix</label>
                                            </div>
                                        </div>
        
                                    </div>
                                </div>
        
                                <div class="col-md-6">
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <div class="form-outline">
                                                <input type="number" name="phoneNum" id="phoneNum" class="form-control form-control-sm" value="{{ Request::old('phoneNum') }}" required />
                                                <label class="form-label" for="phoneNum">Phone Number</label>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-outline">
                                                <input type="email" name="email" id="email" class="form-control form-control-sm" value="{{ Request::old('email') }}"  />
                                                <label class="form-label" for="email">Email</label>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-outline">
                                                <input type="password" name="password" id="password" class="form-control form-control-sm    " value="{{ Request::old('password') }}" required />
                                                <label class="form-label" for="password">Password</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <h6 class="form-label text-uppercase text-primary">Company Information</h6>
                                </div>
                                <div class="col-md-6">
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <select name="entity01" id="entity01" class="selectpicker form-control form-control-sm" required>
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <select name="entity02" id="entity02" class="selectpicker form-control form-control-sm" required>
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <select name="entity03" id="entity03" class="selectpicker form-control form-control-sm" data-live-search="true" required>
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <select name="deptcode" id="deptcode" class="selectpicker form-control form-control-sm" data-live-search="true" required>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <select name="shifts" id="shifts" class=" selectpicker form-control form-control-sm" data-live-search="true" required>
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-outline">
                                                <input type="text" name="birth_date" id="birth_date" class="form-control form-control-sm" value="{{ Request::old('birth_date') }}"  />
                                                <label class="form-label" for="birth_date">Birth date</label>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-outline">
                                                <input type="text" name="empl_date" id="empl_date" class="form-control form-control-sm" value="{{ Request::old('empl_date') }}"  />
                                                <label class="form-label" for="empl_date">Hired date</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
        
                            </div> --}}
                        </form>
        
                    </div>
                </div>

            </div>

        </div>


    </div>
    
</div>

<script src="{{ asset('js/add_new_employee.js') }}"></script>
<script src="{{ asset('js/company_district_branch.js') }}"></script>
<script src="{{ asset('js/shifts.js') }}"></script>

@endsection