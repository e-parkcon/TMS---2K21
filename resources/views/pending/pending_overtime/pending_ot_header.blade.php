@extends('layouts.app')

@section('title', ' - Pending Overtime')

@section('content')
    
<div class="row g-2 mt-0">

    @component('components.content_header')
        @slot('title')
            Pending Overtime
        @endslot
    @endcomponent
    
    <div class="col-md-12">
    
        <div class="card">

            <div class="card-body">
                
                <div class="table-responsive">
                    <table class="table table-sm table-hover" id="tbl_pending_ot" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Reference #</th>
                                <th>Date File</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>

            </div>

        </div>

    </div>

</div>

<script src="{{ asset('js/pending_ot.js') }}"></script>
@endsection