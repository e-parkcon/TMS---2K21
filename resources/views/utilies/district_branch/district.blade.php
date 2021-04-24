@extends('layouts.app')

@section('title', ' - Company District')

@section('content')
    
<div class="row g-2 mt-0">

    @component('components.content_header')
        @slot('title')
            Company District
        @endslot
    @endcomponent


    <div class="col-md-12">
        
        <div class="card">

            <div class="card-body">
                <div class="table-responsive text-sm">
                    <table class="table table-sm table-hover" style="width: 100%;" id="tbl_district_list">
                        <thead>
                            <tr>
                                <th width="10%">Company</th>
                                <th width="30%">District Name</th>
                                <th width="10%">Branches</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

        </div>

    </div>

</div>

<script src="{{ asset('js/utilities/district_branch.js') }}"></script>
@endsection