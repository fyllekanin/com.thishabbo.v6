<div class="content-holder" id="display">
    <div class="content">
        <div class="contentHeader headerBlue">
            <span>Change Display Group</span>
        </div>
        <label for="reg-form-password">Usergroups</label>
        <select id="edit-user-display" class="login-form-input">
            <option value="0">Registered Users</option>
            @foreach($groups as $group)
                @if($group['in_it'])
                    <option value="{{ $group['groupid'] }}" @if($group['groupid'] == Auth::user()->displaygroup) selected="" @endif>{{ $group['title'] }}</option>
                @endif
            @endforeach
        </select>
        <br />
        <button class="pg-blue headerBlue gradualfader fullWidth topBottom" onclick="saveDisplayGroup();">Save</button>
    </div>
</div>
