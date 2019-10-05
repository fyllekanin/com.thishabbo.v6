<script> urlRoute.setTitle("TH - Change Password");</script>

<div class="small-12 column">
    <div class="content-holder">
        <div class="content">
            <div class="content-ct">
                <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
                <span>Change Password</span>
            </div>
        </div>
    </div>
</div>

<div class="small-12 medium-12 large-12 column">
    <div class="content-holder">
        <div class="content">
            <div class="content-ct">
                <div class="contentHeader headerBlue">
                    <span>Change Password</span>
                </div>
                <div class="small-12 column">
                    <label for="forgot-form-code">Code</label>
                    <input type="text" id="forgot-form-code" @if(isset($_GET['code'])) value="{{ $_GET['code'] }}" @else placeholder="Code..." @endif class="login-form-input"/>
                    <label for="forgot-form-pw">New Password</label>
                    <input type="password" id="forgot-form-pw" placeholder="Password..." class="login-form-input"/>
                    <label for="forgot-form-repw">Password again</label>
                    <input type="password" id="forgot-form-repw" placeholder="Again..." class="login-form-input"/><br />
                    <br />
                    <button class="pg-red headerRed floatright gradualfader fullWidth topBottom barFix" onclick="changePassword();">Change</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function changePassword() {
        var code = $('#forgot-form-code').val();
        var pw = $('#forgot-form-pw').val();
        var repw = $('#forgot-form-repw').val();

        $.ajax({
            url: urlRoute.getBaseUrl() + 'auth/change',
            type: 'post',
            data: {code:code, pw:pw, repw:repw},
            success: function(data) {
                if(data['response'] === true) {
                    urlRoute.ohSnap('<span class=\"alert-title\">Legend!</span><br />Your password has been changed!', 'green');
                    urlRoute.loadPage('/login');
                } else {
                    urlRoute.ohSnap(data['message'], 'red');
                }
            }
        });
    }

    var destroy = function() {
        changePassword = null;
    }
</script>
