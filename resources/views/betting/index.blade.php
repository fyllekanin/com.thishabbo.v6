<script> urlRoute.setTitle("TH - Betting Hub");</script>

<div class="reveal" id="place_bet" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
    <div class="modal-header">
        <button class=".modal-header .close" data-close aria-label="Close modal" type="button" style="float:right; font-size: 21px; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; filter: alpha(opacity=20); opacity: .2;">x</button>
        <h4 class="modal-title">Place a Bet</h4>
    </div>
    <div class="modal-body">
        <fieldset>
            <label for="place_bet_amount">Amount Betting</label>
            <input type="number" id="place_bet_amount" placeholder="60000 THC" class="login-form-input"/>
        </fieldset>
    </div>
    <div class="modal-footer">
        <button class="pg-red headerRed gradualfader fullWidth shopbutton" onclick="placeBet();">Place Bet</button>
        <button id="close" class="pg-red headerBlue floatright gradualfader fullWidth" style="padding: 6px 12px;" data-close aria-label="Close modal" type="button">Close</button>
    </div>
</div>

<div class="small-12 column">
    <div class="content-holder">
        <div class="content contentpadding">
            <span>
                <a href="/home" class="bold web-page">Forum Home</a>
                <i class="fa fa-angle-double-right" aria-hidden="true"></i> Betting Hub
            </span>
        </div>
    </div>
</div>

<div class="medium-4 column">
    @include('betting.menu')
</div>

<div class="medium-8 column">
    <div class="userInfo">
        <div class="small-3 column end">
            <div class="shop-box">
                <div class="usercpBoxes">
                    <i class="fa fa-ticket fontSize" aria-hidden="true"></i>
                    <br>
                    <div class="usercpCurrency">{{ number_format(Auth::user()->credits) }}</div>
                    ThisHabboCredits
                </div>
            </div>
        </div>
    </div>

    <div class="userInfo">
        <div class="small-3 column end">
            <div class="shop-box">
                <div class="usercpBoxes">
                    <i class="fa fa-ticket fontSize" aria-hidden="true"></i>
                    <br>
                    <div class="usercpCurrency">{{ number_format(Auth::user()->diamonds) }}</div>
                    Diamonds
                </div>
            </div>
        </div>
    </div>

    <div class="userInfo">
        <div class="small-3 column end">
            <div class="shop-box">
                <div class="usercpBoxes">
                    <i class="fa fa-thumbs-up fontSize" aria-hidden="true"></i>
                    <br>
                    <div class="usercpCurrency">{{ $totalWin }}</div>
                    Bets Won
                </div>
            </div>
        </div>
    </div>

    <div class="userInfo">
        <div class="small-3 column end">
            <div class="shop-box">
                <div class="usercpBoxes">
                    <i class="fa fa-thumbs-down fontSize" aria-hidden="true"></i>
                    <br>
                    <div class="usercpCurrency">{{ $totalLoss }}</div>
                    Bets Lost
                </div>
            </div>
        </div>
    </div>

    <br clear="all">

    <div class="content-holder">
        <div class="content">
            <div class="contentHeader headerPink">
                <span>Top 5 Trending Bets!</span>
            </div>
            <div class="small-12">
                <table style="width: 100%;">
                    <tbody>
                    <tr>
                        <th style="width:40%">Bet</th>
                        <th style="width:20%">Odds</th>
                        <th style="width:20%">Backers</th>
                        <th style="width:20%">Your Bets</th>
                        <th style="width:20%">BET?</th>
                    </tr>
                    @foreach($top5Bets as $bet)
                    <tr>
                        <td>@if($bet['suspended'] == 0)<b>[SUSPENDED]</b> @endif {{ $bet['bet'] }}</td>
                        <td>{{ $bet['odds'] }}</td>
                        <td>{{ $bet['backers'] }}</td>
                        <td>{{ $bet['mybets'] }}</td>
                        <td>@if($bet['suspended'] == 1 AND $bet['finished'] == 0)<button class="pg-red headerBlue gradualfader topBottom" onclick="openBet({{ $bet['betid'] }});" style="float:none !important;">Bet</button>@elseif($bet['suspended'] == 0 OR $bet['finished'] == 1)<button class="pg-red headerRed gradualfader topBottom" disabled style="float:none !important;">Bets Closed</button>@endif</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="content-holder">
        <div class="content">
            <div class="contentHeader headerRed">
                <span>Active Bets!</span>
            </div>
            <div class="small-12">
                <table style="width: 100%;">
                    <tbody>
                    <tr>
                        <th style="width:40%">Bet</th>
                        <th style="width:20%">Odds</th>
                        <th style="width:20%">Backers</th>
                        <th style="width:20%">Your Bets</th>
                        <th style="width:20%">BET?</th>
                    </tr>
                    @foreach($bets as $bet)
                    <tr>
                        <td>@if($bet['suspended'] == 0)<b>[SUSPENDED]</b> @endif {{ $bet['bet'] }}</td>
                        <td>{{ $bet['odds'] }}</td>
                        <td>{{ $bet['backers'] }}</td>
                        <td>{{ $bet['mybets'] }}</td>
                        <td>@if($bet['suspended'] == 1)<button class="pg-red headerBlue gradualfader topBottom" onclick="openBet({{ $bet['betid'] }});" style="float:none !important;">Bet</button>@else<button class="pg-red headerRed gradualfader topBottom" disabled style="float:none !important;">Bets Closed</button>@endif</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).foundation();
    var temp_betid = 0;
    var openBet = function(betid) {
        temp_betid = betid;
        $('#place_bet').foundation('open');
    }

    var placeBet = function() {
        var amount = $('#place_bet_amount').val();
        console.log(temp_betid);
        $.ajax({
            url: urlRoute.getBaseUrl() + 'betting/placeBet',
            type: 'post',
            data: {temp_betid:temp_betid, amount:amount},
            success: function(data) {
                $('#place_bet').foundation('close');
                if(data['response'] == true) {
                    urlRoute.loadPage("/betting");
                    urlRoute.ohSnap('Bet Placed!', 'green');
                } else {
                    urlRoute.ohSnap('Something went wrong', 'red');
                }
            }
        });
    }

    var destroy = function() {
        openCancel = null;
        placeBet = null;
    }
</script>