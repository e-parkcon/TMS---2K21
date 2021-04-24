@extends('layouts.app')

@section('title', ' - Employee Lists')

@section('content')

<div class="row g-2 mt-0">

    @component('components.content_header')
        @slot('title')
            Employee Lists
        @endslot
    @endcomponent

    <div class="col-md-12">

        <div class="card">
            <div class="card-header">
                <a href="{{ route('employees.create') }}" class="btn btn-sm btn-primary"><small>Add New Employee</small></a>
            </div>
            <div class="card-body">

                <div class="row g-3">

                    <div class="table-responsive">

                        <table class="table table-hover" id="tbl_emp_lists" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th width="23%">Name</th>
                                    <th width="10%">ID #</th>
                                    <th width="15%">Company</th>
                                    <th width="10%">District</th>
                                    <th width="15%">Branch</th>
                                    <th width="10%">Status</th>
                                    <th width="5%">Action</th>
                                </tr>
                            </thead>
                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>


</div>

<script src="{{ asset('js/employeeLists.js') }}"></script>
@endsection