<div class="content-holder" id="social">
    <div class="content">
        <div class="contentHeader headerRed">
            <span>Edit Social Networking Accounts</span>
        </div>
        <div class="medium-6 column">
            <label for="edit-form-discord"><i class="fa fa-comments" aria-hidden="true"></i> Discord (e.g. ThisHabbo#0000)</label>
            <input type="text" id="edit-form-discord" value="{{ Auth::user()->discord }}" class="login-form-input" />
        </div>
        <div class="medium-6 column">
            <label for="edit-form-twitter"><i class="fa fa-comments" aria-hidden="true"></i> Twitter (e.g. ThisHabbo)</label>
            <input type="text" id="edit-form-twitter" value="{{ Auth::user()->twitter }}" class="login-form-input" />
        </div>
        <div class="medium-6 column">
            <label for="edit-form-instagram"><i class="fa fa-instagram" aria-hidden="true"></i> Instagram</label>
            <input type="text" id="edit-form-instagram" value="{{ Auth::user()->instagram }}" class="login-form-input" />
        </div>
        <div class="medium-6 column">
            <label for="edit-form-kik"><i class="fa fa-comments" aria-hidden="true"></i> KIK</label>
            <input type="text" id="edit-form-kik" value="{{ Auth::user()->kik }}" class="login-form-input" />
        </div>
        <div class="medium-6 column">
            <label for="edit-form-lastfm"><i class="fa fa-lastfm-square" aria-hidden="true"></i> LastFM</label>
            <input type="text" id="edit-form-lastfm" value="{{ Auth::user()->lastfm }}" class="login-form-input" />
        </div>
        <div class="medium-6 column">
            <label for="edit-form-snapchat"><i class="fa fa-snapchat-square" aria-hidden="true"></i> Snapchat</label>
            <input type="text" id="edit-form-snapchat" value="{{ Auth::user()->snapchat }}" class="login-form-input" />
        </div>
        <div class="medium-6 column">
            <label for="edit-form-soundcloud"><i class="fa fa-soundcloud" aria-hidden="true"></i> Soundcloud</label>
            <input type="text" id="edit-form-soundcloud" value="{{ Auth::user()->soundcloud }}" class="login-form-input" />
        </div>
        <div class="medium-6 column">
            <label for="edit-form-tumblr"><i class="fa fa-tumblr-square" aria-hidden="true"></i> Tumblr</label>
            <input type="text" id="edit-form-tumblr" value="{{ Auth::user()->tumblr }}" class="login-form-input" />
        </div>
        <div class="small-12 medium-12 column"><br>
            <button class="pg-red headerRed gradualfader fullWidth topBottom" onclick="saveSocial();">Save</button>
        </div>
    </div>
</div>
