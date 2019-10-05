<?php $avatar = \App\Helpers\UserHelper::getAvatar(Auth::user()->userid); ?>
<script> urlRoute.setTitle("TH - Habbo");</script>

<div class="small-12 column">
    <div class="content-holder">
        <div class="content contentpadding">
            <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
            <span><a href="/usercp" class="bold web-page">UserCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
            Verify Habbo
        </div>
    </div>
</div>

<div class="medium-4 column">
    @include('usercp.menu')
</div>

<div class="medium-8 column">
<div class="content-holder">
    <div class="content">
        <div class="contentHeader headerRed">
            <span>Verify Habbo</span>
        </div>
        <span>
            <div class="alertt alert-danger" role="alertt"><b>Potential problems with verifying!</b>
            <br/>We have received reports that some users are unable to verify their Habbo accounts! If this happens to you, please submit a ticket via our <b>Contact Us</b> page, located <a href="/contact" class="web-page"> here</a> with the Contact Reason of <b>Account Issues</b>.</div><br />
            Verifying your habbo account is a way to prove who you are on habbo on the website, it also stops other users from using your habbo name on the website pretending to be you. Having a verified habbo account can come in handy with habbo or on-site competitions here on ThisHabbo so we can correctly match who is supposed to get the prize!
            <br />
            <br />
            The way to verify your habbo account is easy. Change your motto to the sentence below, then you type your habbo name into the input and click verify! Remember it can take up to 1 - 30 minutes for habbo to update your motto so we can correctly verify your account. If it takes longer, please make sure to ask a staff member so we can look into it!
            <br />
            <br />
            <b>Motto:</b> TH-user{{ Auth::user()->userid }}
        </span>
        <div class="small-12 medium-7 large-12 column">
            @if(Auth::user()->habbo_verified == 0)
                <br />
                <div class="alertt alert-danger" role="alertt"><b>Habbo Not Verified!</b>
                <br/>You currently haven't verified your Habbo. Please do so below as some features may be disabled for you due to this!</div>
            @else
                <br />
                <div class="alertt alert-success" role="alertt"><b>Habbo is verified!</b>
                <br/>You have currently verified the Habbo account: <b>{{ Auth::user()->habbo }}</b></div>
            @endif
        </div>
    </div>
</div>

    <div class="content-holder">
        <div class="content">
            <div class="contentHeader headerBlue">
                <span>Verify Habbo</span>
            </div>
            <label for="reg-form-password">Habbo Name</label>
            <input type="text" id="edit-form-habbo" @if(Auth::user()->habbo == "") placeholder="Habbo Name..." @else value="{{ Auth::user()->habbo }}" @endif class="login-form-input" />
            <br />
            <button class="pg-blue headerBlue gradualfader fullWidth topBottom" onclick="verifyHabbo();">Verify</button>
        </div>
    </div>
</div>

<script type="text/javascript">
    var verifyHabbo = function() {
        var habbo = $('#edit-form-habbo').val();

        $.ajax({
            url: urlRoute.getBaseUrl() + 'usercp/verify/habbo',
            type: 'post',
            data: {habbo:habbo},
            success: function(data) {
                if(data['response'] == true) {
                    urlRoute.loadPage('/usercp/habbo');
                    urlRoute.ohSnap('You are now verified!', 'green');
                } else {
                    urlRoute.ohSnap(data['message'], 'red');
                }
            }
        });
    }

    var destroy = function() {
        verifyHabbo = null;
    }
</script>
