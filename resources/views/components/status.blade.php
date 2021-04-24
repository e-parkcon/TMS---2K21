<style>
/* body {
    background-color: #eee
}

.mt-70 {
    margin-top: 70px
}

.mb-70 {
    margin-bottom: 70px
}

.card {
    box-shadow: 0 0.46875rem 2.1875rem rgba(4, 9, 20, 0.03), 0 0.9375rem 1.40625rem rgba(4, 9, 20, 0.03), 0 0.25rem 0.53125rem rgba(4, 9, 20, 0.05), 0 0.125rem 0.1875rem rgba(4, 9, 20, 0.03);
    border-width: 0;
    transition: all .2s
}

.card {
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 1px solid rgba(26, 54, 126, 0.125);
    border-radius: .25rem
}

.card-body {
    flex: 1 1 auto;
    padding: 1.25rem
} */

.vertical-timeline {
    width: 100%;
    position: relative;
    /* padding: 1.5rem 0 1rem */
}

.vertical-timeline::before {
    content: '';
    position: absolute;
    top: 0;
    left: 67px;
    height: 100%;
    width: 4px;
    background: #e9ecef;
    border-radius: .25rem
}

.vertical-timeline-element {
    position: relative;
    margin: 0 0 1rem
}

.vertical-timeline--animate .vertical-timeline-element-icon.bounce-in {
    visibility: visible;
    animation: cd-bounce-1 .8s
}

.vertical-timeline-element-icon {
    position: absolute;
    top: 0;
    left: 60px
}

.vertical-timeline-element-icon .badge-dot-xl {
    box-shadow: 0 0 0 5px #fff
}

.badge-dot-xl {
    width: 18px;
    height: 18px;
    position: relative
}

.badge:empty {
    display: none
}

.badge-dot-xl::before {
    content: '';
    width: 10px;
    height: 10px;
    border-radius: .25rem;
    position: absolute;
    left: 50%;
    top: 50%;
    margin: -5px 0 0 -5px;
    background: #fff
}

.vertical-timeline-element-content {
    position: relative;
    margin-left: 90px;
    font-size: .8rem
}

.vertical-timeline-element-content .timeline-title {
    font-size: .8rem;
    text-transform: uppercase;
    margin: 0 0 .5rem;
    padding: 2px 0 0;
    font-weight: bold
}

.vertical-timeline-element-content .vertical-timeline-element-date {
    display: block;
    position: absolute;
    left: -90px;
    top: 0;
    padding-right: 10px;
    text-align: right;
    color: #aaaaaa;
    font-size: .7619rem;
    white-space: nowrap
}

.vertical-timeline-element-content:after {
    content: "";
    display: table;
    clear: both
}
</style>

@if (count($otlv_status) == 0)
    <label class="form-label text-center text-uppercase">No History Yet</label>
@else
<label class="form-label">History</label>
    <div class="col-md-12">
        <div class="vertical-timeline vertical-timeline--animate vertical-timeline--one-column">
            @foreach ($otlv_status as $status)
                <div class="vertical-timeline-item vertical-timeline-element">
                    <div> <span class="vertical-timeline-element-icon bounce-in"> <i class="badge badge-dot badge-dot-xl badge-success"></i> </span>
                        <div class="vertical-timeline-element-content bounce-in">
                            <h4 class="timeline-title">
                                
                                {{ $status->approver == '---' ? '---' : $status->approver }}

                                @if ($status->status == 'A')
                                    | <i class="text-primary">Approved</i>
                                @elseif($status->status == 'D')
                                    | <i class="text-danger">Disapproved</i>
                                @elseif($status->status == 'E')
                                    | <i class="text-warning">Edit</i>
                                @elseif($status->status == 'AWP')
                                    | <i class="text-success">Approve with Pay</i>
                                @elseif($status->status == 'AWOP')
                                    | <i class="text-success">Approve without Pay</i>
                                @endif
                                    <i> @ {{ date('h:i A', strtotime($status->txntime)) }}</i>
                            </h4>
                            
                            <i style="color:#aaaaaa;">Reason : {{ $status->remarks == '' ? '--' : $status->remarks }}</i> <span class="vertical-timeline-element-date">{{ $status->txndate }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif