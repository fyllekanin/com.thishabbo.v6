<script> urlRoute.setTitle("TH - Betting Log");</script>

<div class="small-12 column">
    <div class="content-holder">
        <div class="content contentpadding">
            <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
            <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
            <span>Betting Logs</span>
        </div>
    </div>
</div>

<div class="medium-4 column">
    @include('admincp.menu')
</div>

<div class="medium-8 column">
    <div class="content-holder">
        <div class="content">
            <div class="contentHeader headerPink">
                <span>Betting Logs</span>
            </div>
            <div class="small-12">
                <table class="responsive" style="width: 100%;">
                    <tbody>
                        <tr>
                            <th>Bet User</th>
                            <th>Bet</th>
                            <th>Amount</th>
                            <th>Date</th>
                        </tr>
                        @foreach($bets as $bet)
                        <tr>
                            <td>{!! $bet['betuser'] !!}</td>
                            <td>{{ $bet['bet'] }}</td>
                            <td>{{ $bet['amount'] }}</td>
                            <td>{{ $bet['dateline'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="content-holder">
        <div class="content">
            {!! $pagi !!}
        </div>
    </div>
</div>
