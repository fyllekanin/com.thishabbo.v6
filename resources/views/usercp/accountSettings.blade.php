<?php $ForumHelper = new \App\Helpers\ForumHelper; ?>
<?php $UserHelper = new \App\Helpers\UserHelper; ?>

<script> urlRoute.setTitle("TH - Account Settings");</script>

<div class="small-12 column">
    <div class="content-holder">
            <div class="content contentpadding">
                <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
                <span><a href="/usercp" class="bold web-page">UserCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
                Account Settings
            </div>
    </div>
</div>

<div class="medium-4 column">
    @include('usercp.menu')
</div>

<div class="medium-8 column">
    @include('usercp.accountSettings.menu')
    @include('usercp.accountSettings.password')
    @include('usercp.accountSettings.displayGroup')
    @include('usercp.accountSettings.theme')
    @include('usercp.accountSettings.homepage')
    @include('usercp.accountSettings.countrytime')
    @include('usercp.accountSettings.socialAccounts')
</div>

<script type="text/javascript">
    $("#editpassword").click(function() {
        $('html, body').animate({
            scrollTop: $("#password").offset().top - 60
        }, 1000);
    });
    $("#editdisplay").click(function() {
        $('html, body').animate({
            scrollTop: $("#display").offset().top - 60
        }, 1000);
    });
    $("#edittheme").click(function() {
        $('html, body').animate({
            scrollTop: $("#theme").offset().top - 60
        }, 1000);
    });
    $("#edithome").click(function() {
        $('html, body').animate({
            scrollTop: $("#home").offset().top - 60
        }, 1000);
    });
    $("#editcountry").click(function() {
        $('html, body').animate({
            scrollTop: $("#country").offset().top - 60
        }, 1000);
    });
    $("#editsocial").click(function() {
        $('html, body').animate({
            scrollTop: $("#social").offset().top - 60
        }, 1000);
    });

    var saveHomePage = function() {
        var homepage = $('#homepage-form-name').val();
        $.ajax({
            url: urlRoute.getBaseUrl() + 'usercp/homepage',
            type: 'post',
            data: {homepage:homepage},
            success: function(data) {
                history.go(0);
                urlRoute.ohSnap('Home page edited for your account!', 'green');
            }
        });
    }

    var saveTheme = function() {
        var theme = $('#theme-form').val();
        $.ajax({
            url: urlRoute.getBaseUrl() + 'usercp/theme',
            type: 'post',
            data: {theme:theme},
            success: function(data) {
                urlRoute.ohSnap('Theme saved!', 'green');
                history.go(0);
            },
            error: function(data) {
                urlRoute.loadPage('/usercp/settings/account');
                urlRoute.ohSnap('Error!', 'red');
            }
        });
    }

    var saveCountryTime = function() {
        var country = $('#reg-form-country').val();
        var timezone = $('#reg-form-timezone').val();
        $.ajax({
            url: urlRoute.getBaseUrl() + 'usercp/save/countrytime',
            type: 'post',
            data: {country:country, timezone:timezone},
            success: function(data) {
                if(data['response'] == true) {
                    urlRoute.ohSnap('Country & Timezone saved!', 'green');
                } else {
                    urlRoute.ohSnap('Something went wrong', 'red');
                }
            }
        });
    }

    var savePass = function() {
        var pass = $('#edit-form-pass').val();
        var repass = $('#edit-form-repass').val();
        $.ajax({
            url: urlRoute.getBaseUrl() + 'usercp/save/pass',
            type: 'post',
            data: {pass:pass, repass:repass},
            success: function(data) {
                if(data['response'] == true) {
                    urlRoute.loadPage('/usercp/settings/account');
                    urlRoute.ohSnap('Password saved!', 'green');
                } else {
                    $('#'+data['field']).addClass('form-reg-error');
                    urlRoute.ohSnap(data['message'], 'red');
                }
            }
        });
    }

    var saveDisplayGroup = function() {
        var displaygroup = $('#edit-user-display').val();
        $.ajax({
            url: urlRoute.getBaseUrl() + 'usercp/save/displaygroup',
            type: 'post',
            data: {displaygroup:displaygroup},
            success: function(data) {
                if(data['response'] == true) {
                    urlRoute.ohSnap('Display Group changed!', 'green');
                    urlRoute.loadPage('/usercp/settings/account');
                } else {
                    urlRoute.ohSnap(data['message'], 'red');
                }
            }
        });
    }

    var saveSocial = function() {
        var discord = $('#edit-form-discord').val();
        var twitter = $('#edit-form-twitter').val();
        var instagram = $('#edit-form-instagram').val();
        var kik = $('#edit-form-kik').val();
        var lastfm = $('#edit-form-lastfm').val();
        var snapchat = $('#edit-form-snapchat').val();
        var soundcloud = $('#edit-form-soundcloud').val();
        var tumblr = $('#edit-form-tumblr').val();
        $.ajax({
            url: urlRoute.getBaseUrl() + 'usercp/save/social',
            type: 'post',
            data: {discord:discord, twitter:twitter, instagram:instagram, kik:kik, lastfm:lastfm, snapchat:snapchat, soundcloud:soundcloud, tumblr:tumblr},
            success: function(data) {
                if(data['response'] == true) {
                    urlRoute.loadPage('/usercp/settings/account');
                    urlRoute.ohSnap('Social Networking Accounts saved!', 'green');
                } else {
                    $('#'+data['field']).addClass('form-reg-error');
                    urlRoute.ohSnap(data['message'], 'red');
                }
            }
        });
    }

    var verifyHabbo = function() {
        var habbo = $('#edit-form-habbo').val();
        $.ajax({
            url: urlRoute.getBaseUrl() + 'usercp/verify/habbo',
            type: 'post',
            data: {habbo:habbo},
            success: function(data) {
                if(data['response'] == true) {
                    urlRoute.loadPage('/usercp/settings/account');
                    urlRoute.ohSnap('You are now verified!', 'green');
                } else {
                    urlRoute.ohSnap(data['message'], 'red');
                }
            }
        });
    }

    var destroy = function() {
        saveHomePage = null;
        saveTheme = null;
        saveCountryTime = null;
        savePass = null;
        saveDisplayGroup = null;
        saveSocial = null;
        verifyHabbo = null;
    }
</script>
