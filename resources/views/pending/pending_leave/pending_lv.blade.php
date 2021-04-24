@extends('layouts.app')

@section('title', ' - Pending Leaves')

@section('content')
    
<div class="row g-2 mt-0">

    @component('components.content_header')
        @slot('title')
            Pending Leave
        @endslot
    @endcomponent
    
    <div class="col-md-12">
    
        <div class="card">

            <div class="card-body">
                <div class="row g-3">

                    <div class="table-responsive">

                        <table class="table table-hover" id="tbl_pending_lv" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Leave Period</th>
                                    <th>Leave Type</th>
                                    <th>Reason</th>
                                    <th>Day(s)</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>

                    </div>
                    
                </div>
            </div>

        </div>

    </div>

</div>

<script src="{{ asset('js/pending_lv.js') }}"></script>

@endsection