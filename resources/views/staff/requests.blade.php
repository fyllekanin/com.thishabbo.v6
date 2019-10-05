<?php $UserHelper = new \App\Helpers\UserHelper; ?>
<script> urlRoute.setTitle("TH - Requests");</script>

<div class="small-12 column">
    <div class="content-holder">
        <div class="content contentpadding">
            <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
            <span><a href="/staff" class="bold web-page">StaffCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
            <span>View Requests</span>
        </div>
    </div>
</div>

<div class="medium-4 column">
    @include('staff.menu')
</div>

<div class="medium-8 column">
    <div class="content-holder" id="manage-requests">
        <div class="content">
            <div class="contentHeader headerRed" id="manage-requests" style="margin-bottom:0;">
            @if($UserHelper::haveStaffPerm(Auth::user()->userid, 8192))
            <a onclick="clearAllRequests();" class="web-page headerLink white_link" style="margin-left: 20px;">Clear All</a>
            @endif
            <a onclick="clearMyRequests();" class="web-page headerLink white_link">Clear My Requests</a>
            </div>
        </div>
    </div>

    @foreach($requests as $request)
    <div class="content-holder" id="requestid-{{ $request['requestid'] }}">
    <div class="content">
        <div class="contentHeader headerRed" id="requestids-{{ $request['requestid'] }}">
            <span>Sent by {{ $request['username'] }} {{ $request['time'] }} to {{ $request['djname'] }}: </span>
        </div>
        <div class="content-ct">
            <div class="small-12 column">
                <div class="request_avatar" style="background-image: url('{{ $request['avatar'] }}');"></div>
                    <div class="request_text">
                        {{ $request['message'] }}
                    </div>
                </div>
                <div class="small-12 column">
                    <i class="fa fa-trash-o" aria-hidden="true" style="cursor: pointer;float: right; font-size: 0.8rem;" title="Delete Request" onclick="deleteRequest({{ $request['requestid'] }});"></i>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    <div class="small-12">
        <div class="content-holder"><div class="content">
        {!! $pagi !!}
        </div>
    </div>
</div>

<script type="text/javascript">
    var clearAllRequests = function() {
        $.ajax({
            url: urlRoute.getBaseUrl() + 'staff/radio/request/remove/all',
            type: 'post',
            data: {},
            success: function(data) {
                if(data['response'] == true) {
                    urlRoute.loadPage('/staff/radio/request/page/1');
                    urlRoute.ohSnap('All requests have been deleted!','green');
                } else {
                    urlRoute.ohSnap(data['message'],'red');
                }
            }
        });
    };

    var clearMyRequests = function() {
        $.ajax({
            url: urlRoute.getBaseUrl() + 'staff/radio/request/remove/mine',
            type: 'post',
            data: {},
            success: function(data) {
                if(data['response'] == true) {
                    urlRoute.loadPage('/staff/radio/request/page/1');
                    urlRoute.ohSnap('Your request have been deleted!','green');
                } else {
                    urlRoute.ohSnap(data['message'],'red');
                }
            }
        });
    }
    
    var deleteRequest = function(requestid) {
        $.ajax({
            url: urlRoute.getBaseUrl() + 'staff/radio/request/remove',
            type: 'post',
            data: {requestid:requestid},
            success: function(data) {
                if(data['response'] == true) {
                    $('#requestids-'+requestid).fadeOut();
                    $('#requestid-'+requestid).fadeOut();
                    urlRoute.ohSnap('Request removed!','green');
                } else {
                    urlRoute.ohSnap(data['message'],'red');
                }
            }
        });
    }

    var destroy = function() {
        clearAllRequests = null;
        clearMyRequests = null;
        deleteRequest = null;
    }
</script>
