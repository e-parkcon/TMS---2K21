<form>
    <div class="col-md-12">
        <div class="row g-1">
            <label class="form-label text-uppercase"><small>Approving Group :</small></label>
            <select name="app_group" id="app_group" class="form-control form-control-sm form-select form-select-sm app_group" data-live-search="true">
                <option selected hidden disabled>Choose Approving Group</option>
                @foreach ($app_group as $appgrp)
                    <option class="text-uppercase" value="{{ $appgrp->app_code }}">{{ $appgrp->app_desc }}</option>
                @endforeach
            </select>
        </div>
    </div>
</form>