<script> urlRoute.setTitle("TH - Betting Hub");</script>

<div class="reveal" id="cancel_bet" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
    <div class="modal-header">
        <button class=".modal-header .close" data-close aria-label="Close modal" type="button" style="float:right; font-size: 21px; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; filter: alpha(opacity=20); opacity: .2;">x</button>
        <h4 class="modal-title">Cancel Bet</h4>
    </div>
    <div class="modal-footer">
        <button class="pg-red headerRed gradualfader fullWidth shopbutton" onclick="cancelBet();">Cancel Bet</button>
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
                <span>Settled Bets!</span>
            </div>
            <div class="small-12">
                <table class="responsive" style="width: 100%;">
                    <tbody>
                        <tr>
                            <th style="width:30%">Bet</th>
                            <th style="width:15%">Odds</th>
                            <th style="width:15%">Amount Placed</th>
                            <th style="width:15%">Expected Return</th>
                            <th>Verdict</th>
                        </tr>
                        @foreach($settledBets as $bet)
                        <tr>
                            <td>{{ $bet['bet'] }}</td>
                            <td>{{ $bet['odds'] }}</td>
                            <td>{{ number_format($bet['amount']) }} THC</td>
                            <td>{{ number_format($bet['return']) }} THC</td>
                            <td>{!! $bet['verdict'] !!}</td>
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
                <span>Bets you have placed!</span>
            </div>
            <div class="small-12">
                <table style="width: 100%;">
                    <tbody>
                        <tr>
                            <th style="width:30%">Bet</th>
                            <th style="width:15%">Odds</th>
                            <th style="width:15%">Amount Placed</th>
                            <th style="width:15%">Expected Return</th>
                            <th>Actions</th>
                        </tr>
                        @foreach($bets as $bet)
                        <tr>
                            <td>{{ $bet['bet'] }}</td>
                            <td>{{ $bet['odds'] }}</td>
                            <td>{{ number_format($bet['amount']) }} THC</td>
                            <td>{{ number_format($bet['return']) }} THC</td>
                            @if($bet['can_cancel'] == 0 AND $bet['suspended'] == 1)
                            <td><button class="pg-red headerBlue gradualfader topBottom" onclick="openCancel({{ $bet['id'] }});" style="float:none !important;">Cancel Bet</button></td>
                            @elseif($bet['can_cancel'] == 1 || $bet['suspended'] == 0)
                            <td><button class="pg-red headerRed gradualfader topBottom" disabled style="float:none !important;">Unable to Cancel</button></td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    var openCancel = function(betid) {
        temp_betid = betid;
        $('#cancel_bet').foundation('open');
    }

    var cancelBet = function() {
        $.ajax({
            url: urlRoute.getBaseUrl() + 'betting/cancelBet',
            type: 'post',
            data: {temp_betid:temp_betid},
            success: function(data) {
                $('#cancel_bet').foundation('close');
                urlRoute.loadPage("/betting/own");
                urlRoute.ohSnap('Bet cancelled!', 'green');
            }
        });
    }

    var destroy = function() {
        openCancel = null;
        cancelBet = null;
    }
</script>