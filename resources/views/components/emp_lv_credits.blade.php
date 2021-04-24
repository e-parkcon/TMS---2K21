<div class="card">
    <div class="card-body">
        <div class="row g-1">
            <label class="form-label text-uppercase text-primary text-sm">Leave Credits</label>

            <div class="table-responsive">
                
                <table class="table table-sm table-borderless m-0">
                    <tbody>
                        @foreach ($lv_credits as $item)
                            <tr>
                                <td>{{ $item['lv_desc'] }} <span class="float-right">:</span></td>
                                <td>{{ $item['lv_balance'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>