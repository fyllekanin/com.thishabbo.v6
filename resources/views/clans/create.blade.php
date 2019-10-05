<script> urlRoute.setTitle("TH - Create Clan");</script>
<div class="small-12 column">
    <div class="content-holder">
        <div class="content contentpadding">
            <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
            <span><a href="/usercp" class="bold web-page">UserCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
            <span>Create Clan</span>
        </div>
    </div>
</div>

<div class="medium-4 column">
    @include('usercp.menu')
</div>

<div class="medium-8 column">
    <div class="content-holder">
        <div class="content">
            <div class="contentHeader headerRed">
                Create a Clan
            </div>
            <div class="content-ct">
                <label for="clan-form-name">Clan Name</label>
                <input type="text" id="clan-form-name" placeholder="Intuition!" class="login-form-input"/>
                <br />
                <button class="pg-red headerRed gradualfader fullWidth topBottom topHelp" onclick="createClan();">Create (500 THC)</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var createClan = function() {
        var formData = new FormData();
        formData.append('name', $('#clan-form-name').val());

        $.ajax({
            url: urlRoute.getBaseUrl() + 'usercp/create/clan',
            type: 'post',
            data: formData,
            processData: false,
            contentType: false,
            success: function(data) {
                if(data['response'] == true) {
                    urlRoute.ohSnap('Clan Created!', 'green');
                    urlRoute.loadPage('clans');
                }else {
                    urlRoute.ohSnap(data['message'], 'red');
                }
            }
        });
    }