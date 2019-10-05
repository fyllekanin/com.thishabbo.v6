<div class="content-holder" id="userbar">
    <div class="content">
        <div class="contentHeader headerRed">
            <span>Edit Userbar</span>
        </div>
        Got a usergroup which has features (like donator) which can be edited? Then just select it here and edit it with ease!
        <br /><br />
        <select class="login-form-input" id="userbarfeature-group">
        @foreach($userbarusergroups as $userbarusergroup)
            <option value="{{ $userbarusergroup['groupid'] }}">{{ $userbarusergroup['title'] }}</option>
        @endforeach
        </select><br />
        <div style="margin-bottom: 10px;">
            <button class="pg-red headerRed gradualfader fullWidth topBottom" onclick="grabData();">Edit</button>
        </div>
        <div>
            <div id="group_specific_info"></div>
        </div>
    </div>
</div>
