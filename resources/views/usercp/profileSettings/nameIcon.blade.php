<div class="content-holder" id="nameicon">
    <div class="content">
        <div class="contentHeader headerRed">
            <span>Name Icons</span>
            <a onclick="saveSelectedIcon();" class="headerLink white_link">Save Selected</a>
        </div>
        <div id="list_badges" style="padding-right: 1.2rem;">
            @for($x = 0; $x < count($icons); $x++)
            <div class="small-3 medium-2 large-1 @if(($x+1) == count($icons[$x])) end @endif column">
                <div class="badge-container hover-box-info @if($icons[$x] == Auth::user()->name_icon) selected-badge @endif" onclick="toggleIcon(this, '{{ $icons[$x] }}');">
                    <img src="{{ asset('_assets/img/nameicons/' . $icons[$x] . '.gif') }}" alt="icon" style="margin-left: 10px; margin-top:10px;"/>
                    <div class="badge-selected">
                        <i class="fa fa-check" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
            @endfor
        </div>
        <br />
        <hr />
        <div class="iconRight"><input type="radio" name="icon_side" value="1" @if(Auth::user()->name_icon_side == 1) checked="checked" @endif > Right</div>
        <div class="iconLeft"><input type="radio" name="icon_side" value="0" @if(Auth::user()->name_icon_side == 0) checked="checked" @endif > Left</div>
    </div>
</div>
