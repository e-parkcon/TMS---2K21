@extends('layouts.app')

@section('title', ' - Approving Group')

@section('content')
    
<div class="row g-2 mt-0">

    @component('components.content_header')
        @slot('title')
            Approving Group
        @endslot
    @endcomponent

    <div class="col-md-12">
        
        <div class="card">

            <div class="card-header">
                <a href="#" id="add_new_appr" class="btn btn-sm btn-primary"><small>Add New Approving Group</small></a>
            </div>

            <div class="card-body">
                <div class="row g-3">

                    <div class="table-responsive text-sm">
                        <table class="table table-sm table-hover" id="tbl_approving_group" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Approving Group</th>
                                    <th>Category</th>
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

<script src="{{ asset('js/utilities/approving_group_list.js') }}"></script>

@endsection