@extends('layouts.app')

@section('title', ' - Import Files')

@section('content')
    
<div class="row g-2 mt-0">

    @component('components.content_header')
        @slot('title')
            Import Files
        @endslot
    @endcomponent


    <div class="col-md-12">
        
        <div class="card">
            <div class="card-body">
                <!-- Pills navs -->
                    <ul class="nav nav-pills mb-3" id="ex1" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="ex1-tab-1" data-mdb-toggle="pill" href="#import_sched" role="tab" aria-controls="import_sched" aria-selected="true">Import Schedule</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="ex1-tab-2" data-mdb-toggle="pill" href="#import_lv_credits" role="tab" aria-controls="import_lv_credits" aria-selected="false">Import Leave Credits</a>
                        </li>
                    </ul>
                <!-- Pills navs -->
  
                <!-- Pills content -->
                <div class="tab-content" id="ex1-content">
                    <div class="tab-pane fade show active" id="import_sched" role="tabpanel" aria-labelledby="ex1-tab-1">
                        <div class="row g-2">
                            <div class="col-12 col-md-12">
                                <form method="post" action="{{ route('import_files.store') }}" enctype="multipart/form-data">
									{{ csrf_field() }}
									<div class="row">
										<div class="col-md-4">
                                            <label for="csv_file_sched" class="form-label text-sm text-uppercase text-primary">Import Schedule </label>
                                            <input class="form-control form-control-sm" name="csv_file_sched" id="csv_file_sched" type="file" />
										</div>
										<div class="col-md-2">
											<button type="submit" class="btn btn-sm btn-primary"><small>Import</small></button>
										</div>
									</div>
								</form>
                            </div>

                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover">
                                        <thead>
                                            <tr>
                                                <th>File name</th>
                                                <th>Uploader</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($imp_schedule as $sched)
                                                <tr>
                                                    <td>{{ $sched->fname }}</td>
                                                    <td>{{ $sched->filename }}</td>
                                                    <td>{{ date('F d, Y', strtotime($sched->date)) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="import_lv_credits" role="tabpanel" aria-labelledby="ex1-tab-2">
                        <div class="row g-2">
                            <div class="col-12 col-md-12">
                                <form method="POST" action="{{ route('import.lv_credits') }}" enctype="multipart/form-data">
									{{ csrf_field() }}
									<div class="row g-2">
										<div class="col-md-4">
                                            <label for="file-leave" class="form-label text-sm text-uppercase text-primary">Import Schedule </label>
											<input type="file" name="file-leave" id="file-leave" class="form-control form-control-sm">
										</div>
										<div class="col-md-2">
											<button type="submit" class="btn btn-sm btn-primary"><small>Import</small></button>
										</div>
									</div>
								</form>
                            </div>

                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover">
                                        <thead>
                                            <tr>
                                                <th>File name</th>
                                                <th>Uploader</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($imp_lv as $lv)
                                                <tr>
                                                    <td>{{ $lv->fname }}</td>
                                                    <td>{{ $lv->filename }}</td>
                                                    <td>{{ date('F d, Y', strtotime($lv->date)) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Pills content -->
            </div>
        </div>

    </div>

</div>

@endsection