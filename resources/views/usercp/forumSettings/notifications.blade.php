<div class="content-holder" id="notification">
    <div class="content">
        <div class="contentHeader headerRed">
            <span>Notification Settings <i>(Tick = yes)</i></span>
        </div>
            <input type="checkbox" class="extra_activated" @if($extras['NOTIFICATION_ON_GUIDE']) checked="" @endif value="1"/> Get a notification when a new quest guide has been posted? <br />

            <input type="checkbox" class="extra_activated" @if($extras['NOTIFICATION_ON_FOLLOW']) checked="" @endif value="2"/> Get a notification when somebody follows you? <br />

            <input type="checkbox" class="extra_activated" @if($extras['NOTIFICATION_ON_QUOTE']) checked="" @endif value="4"/> Get a notification when somebody quotes you? <br />

            <button class="pg-red headerRed gradualfader fullWidth topBottom" onclick="saveExtras();">Save</button>
    </div>
</div>
