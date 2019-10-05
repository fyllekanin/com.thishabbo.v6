<script> urlRoute.setTitle("TH - Edit Bet");</script>
<div class="small-12 column">
    <div class="content-holder">
        <div class="content contentpadding">
            <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
            <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
            <span>Edit Bet</span>
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
                Edit Bet
                <a href="/admincp/bets/page/1" class="web-page headerLink white_link">Back</a>
            </div>
            <div class="content-ct">
                <label for="bet-form-bet">Bet Name</label>
                <input type="text" id="bet-form-bet" value="{{ $bet }}" class="login-form-input"/>
                <br />
                <label for="bet-form-odds">Odds</label>
                <input type="text" id="bet-form-odds" value="{{ $odds }}" class="login-form-input"/>
                <br />
                <label for="bet-form-displayorder">Display Order</label>
                <input type="number" id="bet-form-displayorder" value="{{ $displayorder }}" class="login-form-input"/>
                <br />
                <button class="pg-red headerRed gradualfader fullWidth topBottom topHelp" onclick="saveBet();">Save</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var saveBet = function() {
        var formData = new FormData();
        formData.append('bet', $('#bet-form-bet').val());
        formData.append('odds', $('#bet-form-odds').val());
        formData.append('displayorder', $('#bet-form-displayorder').val());
        formData.append('betid', {{ $betid }});

        $.ajax({
            url: urlRoute.getBaseUrl() + 'admincp/bets/update',
            type: 'post',
            data: formData,
            processData: false,
            contentType: false,
            success: function(data) {
                if(data['response'] == true) {
                    urlRoute.ohSnap('Bet edited!', 'green');
                    urlRoute.loadPage('admincp/bets/page/1');
                }else {
                    urlRoute.ohSnap(data['message'], 'red');
                }
            }
        });
    }