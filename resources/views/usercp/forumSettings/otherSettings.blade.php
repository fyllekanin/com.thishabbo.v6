<div class="content-holder" id="other">
    <div class="content">
        <div class="contentHeader headerRed">
            <span>Other Settings <i>(Tick = yes)</i></span>
        </div>
        <input type="checkbox" class="extra_activated" @if($extras['BBCODE_MODE']) checked="" @endif value="8"/> Default to BBCode mode in all editors? <br />
        <input type="checkbox" class="extra_activated" @if($extras['HIDE_FORUM_AVS']) checked="" @endif value="16"/> Hide Avatars from forum homepage? <br />
        <input type="checkbox" class="extra_activated" @if($extras['HIDE_FORUM_SIGS']) checked="" @endif value="32"/> Hide user signatures? <br />
        <button class="pg-red headerRed gradualfader fullWidth topBottom" onclick="saveExtras();">Save</button>
    </div>
</div>
