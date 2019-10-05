<div class="content-holder" id="postbit">
    <div class="content">
        <div class="contentHeader headerRed">
            <span>Edit Postbit</span>
        </div>
        <div class="small-12 medium-6 column">
            <b>What do you want to hide on posts?:</b> <i style="font-size: 0.7rem;">(Checked = hide)</i> <br /> <br />
            <input type="checkbox" value="1" id="hideJoined" @if($jn == true) checked @endif> Joined<br />
            <input type="checkbox" value="1" id="hidePosts" @if($ps == true) checked @endif> Posts<br />
            <input type="checkbox" value="1" id="hideLikes" @if($lk == true) checked @endif> Likes<br />
            <input type="checkbox" value="1" id="hidesa" @if($sa == true) checked @endif> Social Accounts (Twitter, KIK etc)<br />
            <input type="checkbox" value="1" id="hideLb" @if($lb == true) checked @endif> XP Levels<br />
            <input type="checkbox" value="1" id="hideHh" @if($hh == true) checked @endif> Habbo Name<br />
        </div>
        <div class="small-12 medium-6 column">
            <b>What userbars do you wish to show?:</b> <i style="font-size: 0.7rem;">(Checked = show)</i><br /><br />
            @foreach($usergroups as $usergroup)
            <input type="checkbox" value="{{ $usergroup['groupid'] }}" class="userbar-check" @if($usergroup['checked'] == 1) checked="" @endif /> {{ $usergroup['title'] }} <br />
            @endforeach
        </div>
        <div class="small-12 column" style="margin-bottom: 10px;">
            <label for="postbit_avatar_style">Postbit Style</label>
            <select id="postbit_avatar_style" class="login-form-input">
                <option value="1" @if(Auth::user()->post_avatar == 1) selected="selected" @endif >Name outside top</option>
                <option value="4" @if(Auth::user()->post_avatar == 4) selected="selected" @endif>Name outside bottom</option>
                <option value="2" @if(Auth::user()->post_avatar == 2) selected="selected" @endif>Name inside top</option>
                <option value="3" @if(Auth::user()->post_avatar == 3) selected="selected" @endif>Name inside bottom</option>
                <option value="5" @if(Auth::user()->post_avatar == 5) selected="selected" @endif>Name on top, bars below</option>
            </select>
        </div>
        <div class="small-12 medium-6 column" style="margin-bottom: 10px;">
            <label for="postbit_badge_style">Postbit Badges</label>
            <select id="postbit_badge_style" class="login-form-input">
            @foreach($postbitbadges as $postbitbadge)
                <option id="badge-select-{{ $postbitbadge['badgeid'] }}" value="{{ $postbitbadge['badgeid'] }}&&{{ $postbitbadge['description'] }}">{{ $postbitbadge['description'] }}</option>
            @endforeach
            </select><br />
            <button onclick="addBadge();" class="pg-blue headerBlue gradualfader fullWidth topBottom" style="float:right">Add Badge</button>
        </div>
        <div class="small-12 medium-6 column" style="margin-bottom: 10px;">
            <label>Current Postbit Badges (click to remove)</label>
            <div id="selected-badges">
            @foreach($slBadges as $slBadge)
                <img class="hover-box-info" src="{{ asset('_assets/img/website/badges/' . $slBadge['badgeid'] . '.gif') }}" alt="{{ $slBadge['description'] }}" id="{{ $slBadge['badgeid'] }}"/ style="cursor:pointer;margin-right:4px" data-toggle="tooltip" data-placement="top" data-original-title="<b>{{ $slBadge['name'] }}:</b> {{ $slBadge['description'] }}">
            @endforeach
            </div>
        </div>
        <div>
            <button class="pg-red headerRed gradualfader fullWidth topBottom" onclick="savePostbit();">Save</button>
        </div>
    </div>
</div>
