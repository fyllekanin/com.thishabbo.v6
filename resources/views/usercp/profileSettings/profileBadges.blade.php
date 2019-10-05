<div class="content-holder" id="profilebadges">
    <div class="content">
        <div class="contentHeader headerRed">
            <span>Edit Profile Displayed Badges</span>
            <a onclick="saveSelectedBadges();" class="headerLink white_link">Save Selected</a>
        </div>
        <div id="list_badges">
            @for($x = 0; $x < count($profilebadges); $x++)
            <div class="small-3 medium-2 large-1 @if(($x+1) == count($profilebadges)) end @endif column">
                <div class="badge-container hover-box-info @if($profilebadges[$x]['selected'] == 1) selected-badge @endif" title="<b>{{ $profilebadges[$x]['name'] }}:</b> {{ $profilebadges[$x]['description'] }}" onclick="toggleBadge(this, '{{ $profilebadges[$x]['badgeid'] }}');">
                    <img src="{{ asset('_assets/img/website/badges/' . $profilebadges[$x]['badgeid'] . '.gif') }}" alt="badge" />
                    <div class="badge-selected">
                        <i class="fa fa-check" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
            @endfor
        </div>
    </div>
</div>
