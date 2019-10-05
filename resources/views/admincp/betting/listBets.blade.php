<script> urlRoute.setTitle("TH - Existing Bets");</script>

<div class="reveal" id="end_bet" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
    <div class="modal-header">
        <button class=".modal-header .close" data-close aria-label="Close modal" type="button" style="float:right; font-size: 21px; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; filter: alpha(opacity=20); opacity: .2;">x</button>
        <h4 class="modal-title">End a bet</h4>
    </div>
    <div class="modal-body">
        <fieldset>
            <input type="checkbox" id="keep_bet" /> Keep bet after
            <label for="end_bet_verdict">Bet Result</label>
            <select id="end_bet_verdict" class="login-form-input">
                <option value="1">Win</option>
                <option value="2">Loss</option>
            </select>
        </fieldset>
    </div>

    <div class="modal-footer">
        <button class="pg-red headerRed gradualfader fullWidth shopbutton" onclick="endBet();">End Bet</button>
        <button id="close" class="pg-red headerBlue floatright gradualfader fullWidth" style="padding: 6px 12px;" data-close aria-label="Close modal" type="button">Close</button>
    </div>
</div>

<div class="reveal" id="delete_bet" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
    <div class="modal-header">
        <button class=".modal-header .close" data-close aria-label="Close modal" type="button" style="float:right; font-size: 21px; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; filter: alpha(opacity=20); opacity: .2;">x</button>
        <h4 class="modal-title">Delete a bet</h4>
    </div>
    <div class="modal-footer">
        <button class="pg-red headerRed gradualfader fullWidth shopbutton" onclick="deleteBet();">Delete Bet</button>
        <button id="close" class="pg-red headerBlue floatright gradualfader fullWidth" style="padding: 6px 12px;" data-close aria-label="Close modal" type="button">Close</button>
    </div>
</div>

<div class="reveal" id="suspend_bet" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
    <div class="modal-header">
        <button class=".modal-header .close" data-close aria-label="Close modal" type="button" style="float:right; font-size: 21px; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; filter: alpha(opacity=20); opacity: .2;">x</button>
        <h4 class="modal-title">Suspend a bet</h4>
    </div>
    <div class="modal-footer">
        <button class="pg-red headerRed gradualfader fullWidth shopbutton" onclick="suspendBet();">Suspend Bet</button>
        <button id="close" class="pg-red headerBlue floatright gradualfader fullWidth" style="padding: 6px 12px;" data-close aria-label="Close modal" type="button">Close</button>
    </div>
</div>

<div class="reveal" id="unsuspend_bet" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
    <div class="modal-header">
        <button class=".modal-header .close" data-close aria-label="Close modal" type="button" style="float:right; font-size: 21px; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; filter: alpha(opacity=20); opacity: .2;">x</button>
        <h4 class="modal-title">Unsuspend a bet</h4>
    </div>
    <div class="modal-footer">
        <button class="pg-red headerRed gradualfader fullWidth shopbutton" onclick="unsuspendBet();">Unsuspend Bet</button>
        <button id="close" class="pg-red headerBlue floatright gradualfader fullWidth" style="padding: 6px 12px;" data-close aria-label="Close modal" type="button">Close</button>
    </div>
</div>

<div class="small-12 column">
    <div class="content-holder">
        <div class="content contentpadding">
            <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
            <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
            <span>List Bets</span>
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
                <span>Betting Hub</span>
                <a href="/admincp/bets/create" class="web-page headerLink white_link">Create Bet</a>
            </div>
            <div class="small-12">
                <table class="responsive" style="width: 100%;">
                    <tbody><tr>
                        <th>Bet</th>
                        <th>Status</th>
                        <th>Odds</th>
                        <th>Order</th>
                        <th>Actions</th>
                        <th>Edit</th>
                    </tr>
                    @foreach($bets as $bet)
                    <tr>
                        <td>{{ $bet['bet'] }}</td>
                        <td>
                            @if($bet['finished'] == 0)
                            <b>Ongoing</b>
                            @else
                            Finished
                            @endif
                            @if($bet['suspended'] == 1)
                            - Suspended
                            @endif
                        </td>
                        <td>{{ $bet['odds'] }}</td>
                        <td>{{ $bet['displayorder'] }}</td>
                        <td>
                            <select id="betid-{{ $bet['betid'] }}">
                                <option value="1">Edit Bet</option>
                                <option value="2" data-toggle="#delete_bet">Delete Bet</option>
                                @if($bet['finished'] == 0)
                                @if($bet['suspended'] == 0)
                                <option value="3" data-toggle="#end_bet">End Bet</option>
                                <option value="4" data-toggle="#suspend_bet">Suspend Bet</option>
                                @else
                                <option value="5" data-toggle="#unsuspend_bet">Unsuspend Bet</option>
                                @endif
                                @endif
                            </select>
                        <td><a onclick="betAction({{ $bet['betid'] }});"><i class="fa fa-cog editcog4" aria-hidden="true"></i></a></td>
                        </td>
                    </tr>
                    @endforeach
                    </tbody></table>
            </div>
        </div>
    </div>
    <div class="content-holder">
        <div class="content">
            {!! $pagi !!}
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).foundation();
    var temp_betid = 0;

    var endBet = function() {
        var verdict = $('#end_bet_verdict').val();
        var keep = $('#keep_bet').is(":checked");
        $.ajax({
            url: urlRoute.getBaseUrl() + 'admincp/bets/end',
            type: 'post',
            data: { temp_betid:temp_betid, verdict:verdict, keep: keep },
            success: function(data) {
                $('#end_bet').foundation('close');
                if(data['response'] == true) {
                    urlRoute.loadPage("/admincp/bets/page/{{ $current_page }}");
                    urlRoute.ohSnap('Bet Ended!', 'green');
                } else {
                    urlRoute.ohSnap('Something went wrong', 'red');
                }
            }
        });
    }

    var suspendBet = function() {
        $.ajax({
            url: urlRoute.getBaseUrl() + 'admincp/bets/suspend',
            type: 'post',
            data: {temp_betid:temp_betid},
            success: function(data) {
                $('#suspend_bet').foundation('close');
                if(data['response'] == true) {
                    urlRoute.loadPage("/admincp/bets/page/{{ $current_page }}");
                    urlRoute.ohSnap('Bet Suspended!', 'green');
                } else {
                    urlRoute.ohSnap('Something went wrong', 'red');
                }
            }
        });
    }

    var unsuspendBet = function() {
        $.ajax({
            url: urlRoute.getBaseUrl() + 'admincp/bets/unsuspend',
            type: 'post',
            data: {temp_betid:temp_betid},
            success: function(data) {
                $('#unsuspend_bet').foundation('close');
                if(data['response'] == true) {
                    urlRoute.loadPage("/admincp/bets/page/{{ $current_page }}");
                    urlRoute.ohSnap('Bet Unsuspended!', 'green');
                } else {
                    urlRoute.ohSnap('Something went wrong', 'red');
                }
            }
        });
    }

    var deleteBet = function() {
        $.ajax({
            url: urlRoute.getBaseUrl() + 'admincp/bets/delete',
            type: 'post',
            data: {temp_betid:temp_betid},
            success: function(data) {
                $('#delete_bet').foundation('close');
                if(data['response'] == true) {
                    urlRoute.loadPage("/admincp/bets/page/{{ $current_page }}");
                    urlRoute.ohSnap('Bet Deleted!', 'green');
                } else {
                    urlRoute.ohSnap('Something went wrong', 'red');
                }
            }
        });
    }

    var betAction = function(betid) {
        var action = $('#betid-'+betid).val();
        switch(action) {
            case "1":
                urlRoute.loadPage('/admincp/bets/edit/'+betid);
                break;
            case "2":
                temp_betid = betid;
                $('#delete_bet').foundation('open');
                break;
            case "3":
                temp_betid = betid;
                $('#end_bet').foundation('open');
                break;
            case "4":
                temp_betid = betid;
                $('#suspend_bet').foundation('open');
                break;
            case "5":
                temp_betid = betid;
                $('#unsuspend_bet').foundation('open');
                break;
        }
    }

    var destroy = function() {
        betAction = null;
        deleteBet = null;
        unsuspendBet = null;
        suspendBet = null;
        endBet = null;
    }
</script>
