<div class="content-holder" id="background">
    <div class="content">
        <div class="contentHeader headerRed">
            <span>Backgrounds</span>
            <a onclick="saveSelectedBackground();" class="headerLink white_link">Save Selected</a>
        </div>
        <div id="list_badges" style="padding-right: 1.2rem;">
            @for($x = 0; $x < count($backgrounds); $x++)
            <div class="small-3 medium-2 large-1 @if(($x+1) == count($backgrounds[$x])) end @endif column">
                <div class="badge-container hover-box-info @if($backgrounds[$x] == Auth::user()->background) selected-badge @endif" onclick="toggleBackground(this, {{ $backgrounds[$x] }});">
                    <img src="{{ asset('_assets/img/backgrounds/' . $backgrounds[$x] . '.gif') }}" alt="background"/>
                    <div class="badge-selected">
                        <i class="fa fa-check" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
            @endfor
        </div>
    </div>
</div>
