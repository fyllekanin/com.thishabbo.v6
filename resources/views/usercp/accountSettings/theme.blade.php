<div class="content-holder" id="theme">
    <div class="content">
        <div class="contentHeader headerRed">
            <span>Theme</span>
        </div>
        Themes are bought in the ThisHabboShop to adjust your style of layout chosen. Sometimes you might not like the default styling and may opt for something else!<i style="font-size: 0.7rem;">(Click <a href="/usercp/shop/themes/page/1" class="web-page">here</a> to buy a theme!)</i><br /><br />
        <hr>
        <label for="login-form-username">Theme</label>
        <select id="theme-form" class="login-form-input">
        <option value="0" @if(Auth::user()->theme == 0) selected @endif>Default Theme</option>
        @if($UserHelper::haveAdminPerm(Auth::user()->userid, 4194304))
            @foreach(DB::table('themes')->get() as $row)
                <option value="{{ $row->themeid }}" @if(Auth::user()->theme == $row->themeid) selected @endif>{{ $row->name }} (Admin Given)</option>
            @endforeach
        @else
            @foreach(DB::table('theme_users')->where('userid',Auth::user()->userid)->get() as $row)
                <option value="{{ $row->themeid }}" @if(Auth::user()->theme == $row->themeid) selected @endif>{{ DB::table('themes')->where('themeid',$row->themeid)->value('name') }}</option>
            @endforeach
        @endif
        </select><br>
        <button class="pg-red headerRed gradualfader fullWidth topBottom" style="float:right" onclick="saveTheme();">Save</button>
    </div>
</div>
