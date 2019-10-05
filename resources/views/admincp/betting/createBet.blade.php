<script> urlRoute.setTitle("TH - Create Bet");</script>
<div class="small-12 column">
    <div class="content-holder">
        <div class="content contentpadding">
            <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
            <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
            <span>Create Bet</span>
        </div>
    </div>
</div>

<div class="medium-4 column">
    @include('admincp.menu')
</div>

<div class="medium-8 column">
    <div class="content-holder">
        <div class="content">
            <div class="contentHeader headerRed">
                Create a Bet
                <a href="/admincp/bets/page/1" class="web-page headerLink white_link">Back</a>
            </div>
            <div class="content-ct">
                <label for="bet-form-bet">Bet Name</label>
                <input type="text" id="bet-form-bet" placeholder="Con for Site Owner!" class="login-form-input"/>
                <br />
                <label for="bet-form-odds">Odds</label>
                <input type="text" id="bet-form-odds" placeholder="100/1" class="login-form-input"/>
                <br />
                <label for="bet-form-displayorder">Display Order</label>
                <input type="number" id="bet-form-displayorder" placeholder="1" class="login-form-input"/>
                <br />
                <button class="pg-red headerRed gradualfader fullWidth topBottom topHelp" onclick="createBet();">Create</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var createBet = function() {
        var formData = new FormData();
        formData.append('bet', $('#bet-form-bet').val());
        formData.append('odds', $('#bet-form-odds').val());
        formData.append('displayorder', $('#bet-form-displayorder').val());

        $.ajax({
            url: urlRoute.getBaseUrl() + 'admincp/bets/add',
            type: 'post',
            data: formData,
            processData: false,
            contentType: false,
            success: function(data) {
                if(data['response'] == true) {
                    urlRoute.ohSnap('Bet Created!', 'green');
                    urlRoute.loadPage('admincp/bets/page/1');
                }else {
                    urlRoute.ohSnap(data['message'], 'red');
                }
            }
        });
    }