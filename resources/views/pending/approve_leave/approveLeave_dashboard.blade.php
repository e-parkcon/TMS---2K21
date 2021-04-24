@extends('layouts.app')

@section('title', ' - Approved Leaves')

@section('content')
    
<div class="row g-2 mt-0">

    @component('components.content_header')
        @slot('title')
            Approved Leave
        @endslot
    @endcomponent

    <div class="col-md-12">
        
        <div class="card">

            <div class="card-body">

                <div class="table-responsive text-sm">
                    <table class="table table-sm table-hover" id="tbl_app_lv" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Leave Period</th>
                                <th>Leave Type</th>
                                <th>Reason</th>
                                <th>Days</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>

            </div>

        </div>

    </div>

</div>

<script src="{{ asset('js/approve_lv.js') }}"></script>

@endsection