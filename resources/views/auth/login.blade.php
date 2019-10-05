<script> urlRoute.setTitle("TH - Login");</script>

@if(isset($_GET['success']))
<div class="small-12 column">
    <div class="content-holder">
        <div class="content">
            <div class="content-ct">
                <div class="contentHeader headerBlue">
                    <span>Register to ThisHabbo</span>
                </div>
                <b>Welcome here!</b><br />
                <br />
                You're now registered to ThisHabbo, Feel free to login!
            </div>
        </div>
    </div>
</div>
@endif

<div class="small-12 column">
    <div class="content-holder">
        <div class="content">
            <div class="content-ct">
                <div class="contentHeader headerBlue">
                    <span>Login</span>
                </div>
                <div id="loginForm">
                    <form id="GGGGGGGG" method="post" action="#">
                        <label for="login-form-username">Username or Habbo Name</label>
                        <input type="text" id="login-form-username" placeholder="Username..." class="login-form-input"/>
                        <label for="login-form-password">Password</label>
                        <input type="password" id="login-form-password" placeholder="Password..." class="login-form-input" />
                    </form>
                    <br />
                    <button id="signin-now" onclick="loginForm();" class="pg-red headerBlue floatright gradualfader fullWidth topBottom">Login</button>
                    <a href="/auth/forgot/password" class="web-page"> <button id="signin-now" class="pg-red headerRed floatright gradualfader fullWidth topBottom barFix">Forgotten Password?</button></a>
                    <a href="/register" class="web-page"> <button id="signin-now" class="pg-blue headerBlue floatright gradualfader fullWidth topBottom">Register</button></a>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $("#loginForm").keypress(function(e) {
        if(e.which == 13) {
            loginForm();
        }
    });

    var loginForm = function() {
        var username = $('#login-form-username').val();
        var password = $('#login-form-password').val();
        var rememberme = $('#login-form-remember').val();

        $.ajax({
            url: urlRoute.getBaseUrl() + 'auth/login',
            type: 'post',
            data: {username:username, password:password, rememberme:rememberme},
            success: function(data) {
                if(data['response'] == true) {
                    //Call upon reloading the nav parts
                    urlRoute.loadAuthContent();

                    if(data['homePage'] && data['homePage'] !== '') {
                        $('#home_page_button').attr('href', data['homePage']);
                    }
                    var lastPage = urlRoute.checkLastPage();
                    if(lastPage) {
                        urlRoute.loadPage(lastPage);
                        urlRoute.lastPage = '';
                    } else {
                        urlRoute.loadPage(data['homePage']);
                    }
                    urlRoute.ohSnap('<span class=\"alert-title\">Woohooo!</span><br />You are logged in!', 'green');
                } else {
                    urlRoute.ohSnap(data['message'], 'red');
                }
            },
            error: function(data) {
                urlRoute.loadPage('/login');
            }
        });
    }

    var destroy = function() {
        loginForm = null;
    }
</script>
