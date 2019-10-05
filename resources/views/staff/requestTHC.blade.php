<?php $UserHelper = new \App\Helpers\UserHelper; ?>
<script> urlRoute.setTitle("TH - Request THC");</script>

<div class="small-12 column">
    <div class="content-holder">
        <div class="content contentpadding">
            <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
            <span><a href="/staff" class="bold web-page">StaffCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
            Request THC
        </div>
    </div>
</div>

<div class="medium-4 column">
    @include('staff.menu')
</div>

<div class="medium-8 column">
    @if($UserHelper::haveStaffPerm(Auth::user()->userid, 1048576) OR $UserHelper::haveStaffPerm(Auth::user()->userid, 2))
    <div class="alertt alert-danger" id="alert1" role="alertt" style="margin-bottom: 8px;">
        <div>
            <b>Heya Staff!</b><br />
            <br />
            @if($UserHelper::haveStaffPerm(Auth::user()->userid, 1048576))
            - Events Hosts are allowed to issue <b>10 THC per request</b>!<br />
            @endif
            @if($UserHelper::haveStaffPerm(Auth::user()->userid, 2))
            - Radio DJs are allowed to issue a <b>maximum</b> of <b>30 THC per request</b>!<br />
            @endif
            <br />
            Thankyou for your patience!<br />
            <br /> 
            <b>Love,<br />
            Con and Kerri</b><br />
            <i>Community Admininstrators</i>
        </div>
        @endif
    </div>

    <div class="content-holder">
        <div class="content">
            <div class="contentHeader headerBlue">
                <span>Request THC</span>
            </div>
            <label for="requsername">Name</label>
            <input type="text" id="requsername" placeholder="Persons Username or Habbo Name" class="login-form-input"/>
            <label for="reqtype">Name Type</label>
            <select id="reqtype" class="login-form-input">
                <option value="Forum">Forum Name</option>
                <option value="Habbo">Habbo Name</option>
            </select>
            <label for="thcrequest">How much THC?</label>
            <input type="number" id="thcrequest" placeholder="10" class="login-form-input"/>
            <label for="reason">Why?</label>
            <select id="reason" class="login-form-input">
                <option value="Forum Event">Forum Event</option>
                <option value="Habbo Event" selected="">Habbo Event</option>
                <option value="Radio Event">Radio Event</option>
                <option value="Staff Reward">Staff Reward</option>
            </select>
            <label for="furtherreason">Further info (if required)</label>
            <input type="text" id="furtherreason" placeholder="e.g. Andy's On Air Comp" class="login-form-input"/>
            <br />
            <button class="pg-blue headerBlue gradualfader fullWidth topBottom" onclick="saveRegion();">Save</button>
        </div>
    </div>
</div>

<script type="text/javascript">
    var saveRegion = function() {
        var username = $('#requsername').val();
        var thcrequest = $('#thcrequest').val();
        var reason = $('#reason').val();
        var furtherreason = $('#furtherreason').val();
        var reqtype = $('#reqtype').val();

        $.ajax({
            url: urlRoute.getBaseUrl() + 'staff/request/submit',
            type: 'post',
            data: {username:username, thcrequest:thcrequest, reason:reason, furtherreason:furtherreason, reqtype:reqtype},
            success: function(data) {
                if(data['response'] == true) {
                    urlRoute.ohSnap('THC Request Submitted!', 'green');
                    urlRoute.loadPage('/staff/request');
                } else {
                    urlRoute.ohSnap(data['message'], 'red');
                }
            }
        });
    }

    var destroy = function() {
        saveRegion = null;
    }
</script>
