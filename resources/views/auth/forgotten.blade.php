<script> urlRoute.setTitle("TH - Forgot Password");</script>

<div class="small-12 column">
    <div class="content-holder">
        <div class="content">
            <div class="content-ct">
                <div class="contentHeader headerBlue">
                    <span>Reset Password?</span>
                </div>
                <div data-alert class="alert-box alert radius login-form-error" style="display: none;">
                    Username/Password incorrect
                    <a href="#" class="close">&times;</a>
                </div>
                <label for="forgot-form-email">E-Mail</label>
                <input type="email" id="forgot-form-email" placeholder="E-Mail..." class="login-form-input"/><br />
                <button class="pg-red headerRed floatright gradualfader fullWidth topBottom barFix" onclick="forgotPassword();">Send Mail</button><br />
                <a href="/login" class="web-page"> <button id="signin-now" class="pg-green headerGreen floatright gradualfader fullWidth topBottom">Sign in now</button></a>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function forgotPassword() {
        var email = $('#forgot-form-email').val();

        $.ajax({
            url: urlRoute.getBaseUrl() + 'auth/forgotpassword',
            type: 'post',
            data: {email:email},
            success: function(data) {
                urlRoute.ohSnap('<span class=\"alert-title\">Great Success!</span><br />A password reset e-mail has been sent!', 'green');
            }
        });
    }

    var destroy = function() {
        forgotPassword = null;
    }
</script>
