<script>urlRoute.setTitle("TH - Register");</script>

<!-- <div class="small-12 medium-7 large-12 column">
    <div class="content-holder">
        <div class="content">
            <div class="content-ct">
                <div class="contentHeader headerBlue">
                    Registration Disabled
                </div>
                We have currently disabled registrations while we perform some routine maintenance, apologies for any inconvenience.
            </div>
        </div>
    </div>
</div> -->

<div class="small-12 medium-7 large-12 column">
    <div class="content-holder">
        <div class="content">
            <div class="content-ct">
                <div class="contentHeader headerBlue">
                    Register Form - It'll take no longer than 1 minute!
                </div>
                <div class="small-12 column">
                    <label for="reg-form-username">Username (A-Z a-z 0-9, no special characters)</label>
                    <input type="text" id="reg-form-username" placeholder="Username..." class="login-form-input"/>
                </div>
                <div class="small-6 column">
                    <label for="reg-form-password">Password <i>(8 characters min)</i></label>
                    <input type="password" id="reg-form-password" placeholder="Password..." class="login-form-input" />
                </div>
                <div class="small-6 column">
                    <label for="reg-form-repassword">Password Again</label>
                    <input type="password" id="reg-form-repassword" placeholder="Password Again..." class="login-form-input" />
                </div>
                <div class="medium-6 column">
                    <label for="reg-form-country">Country</label>
                    <select id="reg-form-country" class="login-form-input">
                        @foreach($countrys as $country)
                            <option value="{{ $country->countryid }}">{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="medium-6 column">
                    <label for="reg-form-timezone">Timezone</label>
                    <select id="reg-form-timezone" class="login-form-input">
                        @foreach($timezones as $timezone)
                            <option value="{{ $timezone->timezoneid }}">{{ $timezone->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="small-12 column">
                    <label for="reg-form-email">Referral <em>(Forum username of person who referred you)</em></label>
                    <input type="email" id="reg-form-referd" placeholder="Username" class="login-form-input" />
                </div>
                <div class="small-12 column">
                    <i class="terms-service"><br />Of course, like any other website we have some nitty gritty <a class="special-word" id="show-terms">terms and conditions</a> and rules you must follow. Give them a read (lol) and register!</i>

                    <div id="terms">
                        <b>Forum Rules</b><br />
                        <br />
                        Registration to this forum is free! We do insist that you abide by the rules and policies detailed below. If you agree to the terms, please check the 'I agree' checkbox and press the 'Complete Registration' button below. If you would like to cancel the registration, navigation back to the home page.<br />
                        <br />
                        Although the administrators and moderators of ThisHabbo Forum will attempt to keep all objectionable messages off this site, it is impossible for us to review all messages. All messages express the views of the author, and neither the owners of ThisHabbo Forum will be held responsible for the content of any message.
                    </div>
                    <button id="signup-now" class="pg-red headerRed floatright gradualfader fullWidth barFix">Sign Up</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('#show-terms').click(function() {
        $('#terms').slideToggle();
    });

    $('#signup-now').click(function() {
        var username = $('#reg-form-username').val();
        var pass = $('#reg-form-password').val();
        var repass = $('#reg-form-repassword').val();
        var country = $('#reg-form-country').val();
        var timezone = $('#reg-form-timezone').val();
        var referd = $('#reg-form-referd').val();
        var run = true;

        if(run) {
            $.ajax({
                url: urlRoute.getBaseUrl() + 'auth/register',
                type: 'post',
                data: {username:username, pass:pass, repass:repass, country:country, timezone:timezone, referd: referd},
                success: function(data) {
                    if(data['error'] == 1) {
                        $('#reg-form-username').removeClass('form-reg-error');
                        $('#reg-form-password').removeClass('form-reg-error');
                        $('#reg-form-repassword').removeClass('form-reg-error');
                        $('#reg-form-email').removeClass('form-reg-error');
                        $('#reg-form-country').removeClass('form-reg-error');
                        $('#reg-form-timezone').removeClass('form-reg-error');
                        if(data['field'] != "all") {
                            $('#'+data['field']).addClass('form-reg-error');
                        }
                        urlRoute.ohSnap(data['message'], 'red');
                    } else {
                        urlRoute.loadPage('login?success=1');
                    }
                }
            });
        }
    });
</script>
