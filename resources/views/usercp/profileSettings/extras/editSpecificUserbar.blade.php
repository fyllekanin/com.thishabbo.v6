<script type="text/javascript"> urlRoute.setTitle("TH - Edit Userbar");</script>
@if($found)
<style type="text/css">
@if(isset($userbar['css'])){!! $userbar['css'] !!}@endif
</style>
<div class="contentHeader headerBlue">
    @if($userbar_option == 0) <a class="web-page headerLink white_link"> <b>ACTIVE</b> </a> @endif
    Userbar Features
</div>
<b>Current Userbar: </b>
<br />
@if(isset($userbar['html'])){!! $userbar['html'] !!}@endif
<br />
<div style="margin-bottom: 10px;">
<button class="pg-red headerBlue gradualfader fullWidth topBottom" onclick="useDefault();">Use Default</button>
</div>

@if($userbar_one_color)
<div class="contentHeader headerRed">
    @if($userbar_option == 16) <a class="web-page headerLink white_link"> <b>ACTIVE</b> </a> @endif
    Custom Userbar Color
</div>
<label for="feature-one-userbar">Color</label>
<input type="text" id="feature-one-userbar" value="{{ $userbar_color }}" class="login-form-input"/><br />
<button class="pg-green headerRed gradualfader pull-right" onclick="oneColor();">Activate</button>
@endif

@if($userbar_rainbow)
<div class="contentHeader headerBlack">
    @if($userbar_option == 32) <a class="web-page headerLink white_link"> <b>ACTIVE</b> </a> @endif
    Rainbow Userbar
</div>
<i>Using the rainbow userbar will make your bar into a mix of colours like the a rainbow, wahoo!</i><br />
<br />
<button class="pg-red headerRed gradualfader fullWidth topBottom" onclick="rainbow();">Activate</button>
@endif

@if($userbar_custom_rainbow)
<div class="contentHeader headerRed">
    @if($userbar_option == 64) <a class="web-page headerLink white_link"> <b>ACTIVE</b> </a> @endif
    ThisHabboClub
</div>
ThisHabboClub can support several colours and a specific colour. See the examples below.<br><br>
<b>Example:</b> #000000, #333333, #cccccc, #ffffff<br>
<b>Example:</b> #000000<br><br>
<label for="feature-rainbow-userbar">Colors</label>
<input type="text" id="feature-rainbow-userbar" value="{{ $userbar_color }}" class="login-form-input"/><br />
<button class="pg-red headerRed gradualfader fullWidth topBottom" onclick="useCustomRainbow();">Activate</button>
@endif

<script type="text/javascript">
var groupid = "{{ $groupid }}";
var useDefault = function() {
    $.ajax({
        url: urlRoute.getBaseUrl() + 'usercp/userbar/default',
        type: 'post',
        data: {groupid:groupid},
        success: function(data) {
            grabData();
            urlRoute.ohSnap('Userbar updated!', 'green');
        }
    });
}

var oneColor = function() {
    var color = $('#feature-one-userbar').val();
    $.ajax({
        url: urlRoute.getBaseUrl() + 'usercp/userbar/one',
        type: 'post',
        data: {groupid:groupid, color:color},
        success: function(data) {
            grabData();
            urlRoute.ohSnap('Userbar updated!', 'green');
        }
    });
}

var rainbow = function() {
    $.ajax({
        url: urlRoute.getBaseUrl() + 'usercp/userbar/rainbow',
        type: 'post',
        data: {groupid:groupid},
        success: function(data) {
            grabData();
            urlRoute.ohSnap('Userbar updated!', 'green');
        }
    });
}

var useCustomRainbow = function() {
    var colors = $('#feature-rainbow-userbar').val();
    $.ajax({
        url: urlRoute.getBaseUrl() + 'usercp/userbar/customrainbow',
        type: 'post',
        data: {groupid:groupid, colors:colors},
        success: function(data) {
            grabData();
            urlRoute.ohSnap('Userbar updated!', 'green');
        }
    });
}

var destroy = function() {
    useDefault = null;
    oneColor = null;
    rainbow = null;
    useCustomRainbow = null;
}
</script>

@else
<div class="contentHeader headerRed">
    Error 404
</div>
<div class="content">
    You are not a part of this group and can there for not access it!
</div>
@endif
