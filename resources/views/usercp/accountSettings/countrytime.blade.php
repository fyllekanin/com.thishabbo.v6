<div class="content-holder" id="country">
    <div class="content">
        <div class="contentHeader headerRed">
            <span>Edit Country & Time</span>
        </div>
        <label for="reg-form-country">Country</label>
        <select id="reg-form-country" class="login-form-input">
            @foreach($countrys as $country)
                <option value="{{ $country->countryid }}" @if($country->countryid == Auth::user()->country) selected="" @endif >{{ $country->name }}</option>
            @endforeach
        </select>
        <label for="reg-form-timezone">Timezone</label>
        <select id="reg-form-timezone" class="login-form-input">
            @foreach($timezones as $timezone)
                <option value="{{ $timezone->timezoneid }}" @if($timezone->timezoneid == Auth::user()->timezone) selected="" @endif >{{ $timezone->name }}</option>
            @endforeach
        </select>
        <br />
        <button class="pg-red headerRed gradualfader fullWidth topBottom" onclick="saveCountryTime();">Save</button>
    </div>
</div>
