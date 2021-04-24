@extends('layouts.app')

@section('title', ' - Branch Log')

@section('content')
    
<style>
	span {
		/*margin-top: 2px;*/
		font-size: 14px;
		display: inline-block;
  		vertical-align: middle;
  		text-align: left;
	}

	.squareRed {
		
		margin-top: 2px;
  		height: 10px;
  		width: 10px;
  		background-color: red;
  		border-radius: 50%;
  		/*display: inline-block;*/
	}

	.squareBlue {
		margin-top: 2px;
  		height: 10px;
  		width: 10px;
  		background-color: blue;
  		border-radius: 50%;
  		/*display: inline-block;*/
	}

	.squareGreen {
		margin-top: 2px;
  		height: 10px;
  		width: 10px;
  		background-color: #29d64e;
  		border-radius: 50%;
  		/*display: inline-block;*/
	}

	.squareYellow {
		margin-top: 2px;
  		height: 10px;
  		width: 10px;
  		background-color: yellow;
  		border-radius: 50%;
  		/*display: inline-block;*/
	}

	.squareWhite {
  		height: 10px;
  		width: 10px;
  		background-color: white;
	}
</style>
<div class="row g-2 mt-0">

    @component('components.content_header')
        @slot('title')
            Branch Log
        @endslot
    @endcomponent


    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-4 col-md-4">
                        <label class="form-label" for="fromdate"><small>From date</small></label><span class="float-right">:</span>
                    </div>
                    <div class="col-8 col-md-8">
                        <input type="text" name="fromdate" id="fromdate" class="form-control text-center form-control-sm" placeholder="Date" autocomplete="off" value="{{ Request::GET('txndate') }}" required>
                    </div>
                    <div class="col-4 col-md-4">
                        <label class="form-label" for="todate"><small>To date</small></label><span class="float-right">:</span>
                    </div>
                    <div class="col-8 col-md-8">
                        <input type="text" name="todate" id="todate" class="form-control text-center form-control-sm" placeholder="Date" autocomplete="off" value="{{ Request::GET('txndate') }}" required>
                    </div>

                    <div class="col-4 col-md-4">
                        <label class="form-label" for="company"><small>Company</small></label><span class="float-right">:</span>
                    </div>
                    <div class="col-8 col-md-8">
                        <select name="company" type="text" id="company" class="selectpicker form-control form-control-sm" data-live-search="true">
                            <option selected value="%"> ALL </option>
                            @foreach($company as $company)
                                <option value="{{ $company->entity01 }}"> {{ $company->entity01_desc }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-4 col-md-4">
                        <label class="form-label" for="branch"><small>Branch</small></label><span class="float-right">:</span>
                    </div>
                    <div class="col-8 col-md-8">
                        <select name="branch" type="text" id="branch" class="selectpicker form-control form-control-sm" data-live-search="true" data-size="10">
                            <option value="%"> ALL </option>
                            @foreach($branch as $branch)
                                <option value="{{ $branch->entity03_desc }}"> {{ $branch->entity03_desc }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <a href="#" id="search_branch_attlog" class="btn btn-primary btn-sm btn-block"><small>Search</small></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive text-sm">
                    <table class="table table-sm table-hover" id="tbl_brch_attlog" width="100%">
                        <thead>
                            <tr>
                                <th width="20%">Branch</th>
                                <th width="15%">No. of Transactions</th>
                                <th width="10%">Logs</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="{{ asset('js/logs/branch_attlog.js') }}"></script>
@endsection