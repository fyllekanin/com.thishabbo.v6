<div class="content-holder" id="username">
    <div class="content">
        <div class="contentHeader headerRed">
            <span>Username Features</span>
        </div>
        Here you can change your username to your liking. Depending on your subscription, the name will change. If you haven't got a subscription, head over to the shop now to buy one!<br />
        <br />
        <b>Current Username: </b> {!! $username !!}
        <br />
        <br />
        <button class="pg-red headerRed gradualfader fullWidth topBottom" onclick="useDefault();">Reset</button><br />
        <br />
        @if($username_one_color)
        <div class="contentHeader headerRed">
            <a class="web-page headerLink white_link">@if($username_option == 1) <b>ACTIVE</b> @endif</a>
            Custom Username Colour
        </div>
        <i>Change your username colour to a custom one! type in the hex color with the hashtag symbol #</i><br />
        <br />
        <label for="feature-one-username">Colour</label>
        <input type="text" id="feature-one-username" @if($username_option == 1) value="{{ $username_color }}" @else placeholder="#000000" @endif class="login-form-input"/><br />
        <button class="pg-green headerRed gradualfader pull-right" onclick="oneColor();">Save & Activate</button>
        @endif

        @if($username_rainbow_color)
        <div class="contentHeader headerBlue">
            <a class="web-page headerLink white_link">@if($username_option == 2) <b>ACTIVE</b> @endif</a>
            Rainbow Username
        </div>
        <i>Using the rainbow username will make your name into a mix of colors like the rainbow, wahoo!</i><br />
        <br />
        <button class="pg-blue headerBlue gradualfader pull-right" onclick="rainbow();">Save & Activate</button>
        @endif

        @if($username_custom_rainbow_color)
        <div class="contentHeader headerBlue">
            <a class="web-page headerLink white_link">@if($username_option == 3) <b>ACTIVE</b> @endif</a>
            ThisHabboClub
        </div>
        ThisHabboClub can support several colours and a specific colour. See the examples below.<br><br>
        <b>Example:</b> #000000, #333333, #cccccc, #ffffff<br>
        <b>Example:</b> #000000<br><br>
        <label for="feature-rainbow-username">Colors</label>
        <input type="text" id="feature-rainbow-username" @if($username_option == 3) value="{{ $username_color }}" @else placeholder="#000000, #333333, #cccccc, #ffffff..." @endif class="login-form-input"/>
        <br />
        <button class="pg-red headerBlue gradualfader fullWidth topBottom" onclick="customRainbow();">Save & Activate</button>
        @endif
    </div>
</div>
