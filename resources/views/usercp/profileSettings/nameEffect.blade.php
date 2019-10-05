<div class="content-holder" id="nameeffect">
    <div class="content">
        <div class="contentHeader headerRed">
            <span>Name Effects</span>
            <a onclick="saveSelectedEffect();" class="headerLink white_link">Save Selected</a>
        </div>
        <div id="list_badges" style="padding-right: 1.2rem;">
            @for($x = 0; $x < count($effects); $x++)
            <div class="small-3 medium-2 large-1 @if(($x+1) == count($effects[$x])) end @endif column">
                <div class="badge-container hover-box-info @if($effects[$x] == Auth::user()->name_effect) selected-badge @endif" onclick="toggleEffect(this, '{{ $effects[$x] }}');">
                    <img src="{{ asset('_assets/img/nameeffects/' . $effects[$x] . '.gif') }}" alt="effect"/>
                    <div class="badge-selected">
                        <i class="fa fa-check" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
            @endfor
        </div>
    </div>
</div>
