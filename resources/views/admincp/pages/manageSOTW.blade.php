<?php $UserHelper = new \App\Helpers\UserHelper; ?>
<script> urlRoute.setTitle("TH - Staff of the Week");</script>

<div class="small-12 column">
    <div class="content-holder">
        <div class="content contentpadding">
            <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
            <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
            <span>Manage Staff of the Week</span>
        </div>
    </div>
</div>

<div class="medium-4 column">
    @include('admincp.menu')
</div>

<div class="medium-8 column">
    <div class="content-holder">
        <div class="content">
            <div class="contentHeader headerBlue">
                <span>Previous Management Awards</span>
            </div>
            <b>Global Management:</b> {!! $global_management !!}<br />
            <b>EU Management:</b> {!! $eu_management !!}<br />
            <b>NA Management:</b> {!! $na_management !!}<br />
            <b>OC Management:</b> {!! $oc_management !!}
        </div>
    </div>
    <div class="content-holder">
        <div class="content">
            <div class="contentHeader headerBlue">
                <span>Previous Events Awards</span>
            </div>
            <b>EU Events:</b> {!! $eu_events !!}<br />
            <b>NA Events:</b> {!! $na_events !!}<br />
            <b>OC Events:</b> {!! $oc_events !!}
        </div>
    </div>
    <div class="content-holder">
        <div class="content">
            <div class="contentHeader headerBlue">
                <span>Previous Radio Awards</span>
            </div>
            <b>EU Radio:</b> {!! $eu_radio !!}<br />
            <b>NA Radio:</b> {!! $na_radio !!}<br />
            <b>OC Radio:</b> {!! $oc_radio !!}
        </div>
    </div>
    <div class="content-holder">
        <div class="content">
            <div class="contentHeader headerBlue">
                <span>Previous Misc Awards</span>
            </div>
            <b>Media:</b> {!! $media !!}<br />
            <b>Moderation:</b> {!! $moderation !!}<br />
            <b>Quests:</b> {!! $quests !!}<br />
            <b>Graphics:</b> {!! $graphics !!}<br />
            <b>Audio Production:</b> {!! $audioprod !!}<br />
            <b>Recruitment:</b> {!! $recruitment !!}
        </div>
    </div>

    <div class="content-holder">
        <div class="content">
            <div class="contentHeader headerRed">
                <span>Change Staff of the Week</span>
            </div>
            <label for="edit-form-startdate">Start Date (e.g. 8th):</label>
            <input type="login-form-input" id="edit-form-startdate" placeholder="DDth/st/nd/rd..." class="login-form-input" />
            <label for="edit-form-enddate">End Date (e.g. 14th):</label>
            <input type="login-form-input" id="edit-form-enddate" placeholder="DDth/st/nd/rd..." class="login-form-input" />
            <label for="edit-form-month">Month (e.g. December):</label>
            <input type="login-form-input" id="edit-form-month" placeholder="Month..." class="login-form-input" />
            <br /><br/ >
            <label for="edit-form-global-management">Global Management</label>
            <input type="login-form-input" id="edit-form-global-management" placeholder="Username..." class="login-form-input" />
            <br /><br/ >
            <label for="edit-form-eu-management">EU Management</label>
            <input type="login-form-input" id="edit-form-eu-management" placeholder="Username..." class="login-form-input" />
            <label for="edit-form-eu-events">EU Events</label>
            <input type="login-form-input" id="edit-form-eu-events" placeholder="Username..." class="login-form-input" />
            <label for="edit-form-eu-radio">EU Radio</label>
            <input type="login-form-input" id="edit-form-eu-radio" placeholder="Username..." class="login-form-input" />
            <br /><br/ >
            <label for="edit-form-na-management">NA Management</label>
            <input type="login-form-input" id="edit-form-na-management" placeholder="Username..." class="login-form-input" />
            <label for="edit-form-na-events">NA Events</label>
            <input type="login-form-input" id="edit-form-na-events" placeholder="Username..." class="login-form-input" />
            <label for="edit-form-na-radio">NA Radio</label>
            <input type="login-form-input" id="edit-form-na-radio" placeholder="Username..." class="login-form-input" />
            <br /><br/ >
            <label for="edit-form-oc-management">OC Management</label>
            <input type="login-form-input" id="edit-form-oc-management" placeholder="Username..." class="login-form-input" />
            <label for="edit-form-oc-events">OC Events</label>
            <input type="login-form-input" id="edit-form-oc-events" placeholder="Username..." class="login-form-input" />
            <label for="edit-form-oc-radio">OC Radio</label>
            <input type="login-form-input" id="edit-form-oc-radio" placeholder="Username..." class="login-form-input" />
            <br /><br/ >
            <label for="edit-form-media">Media</label>
            <input type="login-form-input" id="edit-form-media" placeholder="Username..." class="login-form-input" />
            <label for="edit-form-moderation">Moderation</label>
            <input type="login-form-input" id="edit-form-moderation" placeholder="Username..." class="login-form-input" />
            <label for="edit-form-quests">Quests</label>
            <input type="login-form-input" id="edit-form-quests" placeholder="Username..." class="login-form-input" />
            <label for="edit-form-graphics">Graphics</label>
            <input type="login-form-input" id="edit-form-graphics" placeholder="Username..." class="login-form-input" />
            <label for="edit-form-audioprod">Audio Producers</label>
            <input type="login-form-input" id="edit-form-audioprod" placeholder="Username..." class="login-form-input" />
            <label for="edit-form-recruitment">Recruitment</label>
            <input type="login-form-input" id="edit-form-recruitment" placeholder="Username..." class="login-form-input" /><br/>
            <br />
            <button class="pg-red headerRed gradualfader fullWidth topBottom" onclick="saveSOTW();">Submit SOTW</button>
        </div>
    </div>
</div>

<script type="text/javascript">
    var saveSOTW = function() {
        var startdate = $('#edit-form-startdate').val();
        var enddate = $('#edit-form-enddate').val();
        var month = $('#edit-form-month').val();
        var global_management = $('#edit-form-global-management').val();
        var eu_management = $('#edit-form-eu-management').val();
        var eu_events = $('#edit-form-eu-events').val();
        var eu_radio = $('#edit-form-eu-radio').val();
        var na_management = $('#edit-form-na-management').val();
        var na_events = $('#edit-form-na-events').val();
        var na_radio = $('#edit-form-na-radio').val();
        var oc_management = $('#edit-form-oc-management').val();
        var oc_events = $('#edit-form-oc-events').val();
        var oc_radio = $('#edit-form-oc-radio').val();
        var media = $('#edit-form-media').val();
        var moderation = $('#edit-form-moderation').val();
        var quests = $('#edit-form-quests').val();
        var graphics = $('#edit-form-graphics').val();
        var audioprod = $('#edit-form-audioprod').val();
        var recruitment = $('#edit-form-recruitment').val();

        $.ajax({
            url: urlRoute.getBaseUrl() + 'admincp/site/sotw/submit',
            type: 'post',
            data: {startdate:startdate, enddate:enddate, month:month, global_management:global_management, eu_management:eu_management, eu_events:eu_events, eu_radio:eu_radio, na_management:na_management, na_events:na_events, na_radio:na_radio, oc_management:oc_management, oc_events:oc_events, oc_radio:oc_radio, media:media, moderation:moderation, quests:quests, graphics:graphics, audioprod:audioprod, recruitment:recruitment},
            success: function(data) {
                if(data['response'] == true) {
                    urlRoute.loadPage('/admincp/site/sotw');
                    urlRoute.ohSnap('SOTW Submitted and Posted!', 'green');
                } else {
                    $('#'+data['field']).addClass('form-reg-error');
                    urlRoute.ohSnap(data['message'], 'red');
                }
            }
        });
    }

    var destroy = function() {
        saveSOTW = null;
    }
</script>
