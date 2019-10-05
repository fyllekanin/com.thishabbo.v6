<div class="content-holder" id="home">
    <div class="content">
        <div class="contentHeader headerGreen">
            <span>Home Page</span>
        </div>
        Want another page than "Home" to be your default page when you visit ThisHabbo? Type in the url you want below. <br />
        Make sure to type it correctly with help of the examples below.<br /><br />
        <hr /><br />
        <b>Example 1:</b> /home <br />
        <b>Example 2:</b> /forum <br />
        <b>Example 3:</b> /forum/thread/1/page/1 <br />
        <br />
        <label for="login-form-username">Url</label>
        <input type="text" id="homepage-form-name" @if(strlen(Auth::user()->homePage) > 0) value="{{ Auth::user()->homePage }}" @else placeholder="Url..." @endif class="login-form-input"/>
        <br>
        <button class="pg-green headerGreen gradualfader fullWidth topBottom" style="float:right" onclick="saveHomePage();">Save</button>
    </div>
</div>
