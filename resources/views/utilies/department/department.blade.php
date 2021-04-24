@extends('layouts.app')

@section('title', ' - Department')

@section('content')
    
<div class="row g-2 mt-0">

    @component('components.content_header')
        @slot('title')
            Department
        @endslot
    @endcomponent


    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive text-sm">
                    <table class="table table-sm table-hover" style="width: 100%;" id="tbl_department_list">
                        <thead>
                            <tr>
                                <th width="15%">Company</th>
                                <th>Department</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

    </div>

</div>

<script src="{{ asset('js/utilities/department.js') }}"></script>

@endsection