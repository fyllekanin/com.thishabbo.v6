<script> urlRoute.setTitle("TH - Betting History");</script>

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
                <span>The History of Bets!</span>
            </div>
            <div class="small-12">
                <table style="width: 100%;">
                    <tbody><tr>
                        <th style="width:50%">Bet</th>
                        <th style="width:25%">Backers</th>
                        <th>Odds</th>
                    </tr>
                    @foreach($bets as $bet)
                    <tr>
                        <td>{{ $bet['bet'] }}</td>
                        <td>{{ $bet['backers'] }}</td>
                        <td>{{ $bet['odds'] }}</td>
                    </tr>
                    @endforeach
                    </tbody></table>
            </div>
        </div>
    </div>
</div>