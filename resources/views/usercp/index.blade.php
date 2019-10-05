<script> urlRoute.setTitle("TH - Dashboard");</script>
<?php $ForumHelper = new \App\Helpers\ForumHelper; ?>
<?php $UserHelper = new \App\Helpers\UserHelper; ?>

<div class="small-12 column">
    <div class="content-holder">
        <div class="content contentpadding">
            <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
            <span>User Control Panel</span>
        </div>
    </div>
</div>

<div class="medium-4 column">
    @include('usercp.menu')
</div>

<div class="medium-8 column">
    <div class="userInfo">
        <div class="small-3 column end">
            <div class="shop-box">
                <div class="usercpBoxes">
                    <i class="fa fa-ticket fontSize" aria-hidden="true"></i>
                    <br />
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
                    <br />
                    <div class="usercpCurrency">{{ number_format(Auth::user()->diamonds) }}</div>
                    ThisHabboDiamonds
                </div>
            </div>
        </div>
    </div>
    <div class="userInfo">
        <div class="small-3 column end">
            <div class="shop-box">
                <div class="usercpBoxes">
                    <i class="fa fa-gamepad fontSize" aria-hidden="true"></i>
                    <br />
                    <div class="usercpCurrency">{{ $level_name }}</div>
                    Next Level in <b>{{ $level_until }} XP</b>
                </div>
            </div>
        </div>
    </div>
    <div class="userInfo">
        <div class="small-3 column end">
            <div class="shop-box">
                <div class="usercpBoxes">
                    <a href="/usercp/shop/owned" class="web-page" style="color: inherit !important;">
                        <i class="fa fa-shopping-bag fontSize" aria-hidden="true"></i>
                        <br />
                        <div class="usercpCurrency">{{ number_format($shop_owned) }}</div>
                        Shop Items Owned
                    </a>
                </div>
            </div>
        </div>
    </div><br clear="all">

    <!-- <div class="content-holder">
        <div class="content">
            <div class="contentHeader headerRed">
                <span>Daily Quests</span>
            </div>
            <div class="small-12">
                <table class="responsive" style="width: 100%;">
                    <tr>
                        <th>Quest</th>
                        <th>Reward</th>
                    </tr>
                    @foreach($quests as $quest)
                    <tr>
                        <td>{{ $quest['text'] }}</td>
                        <td>{{ $quest['box'] }}</td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div> -->

    <div class="content-holder">
        <div class="content">
            <div class="contentHeader headerRed">
                <span>Account Overview</span>
            </div>
            <div class="small-12">
                <table class="responsive" style="width: 100%;">
                    <tr>
                        <td style="width:50%"><b>Username</b></td>
                        <td>{{ Auth::user()->username }}</td>
                    </tr>
                    <tr>
                        <td><b>UserID</b></td>
                        <td>{{ Auth::user()->userid }}</td>
                    </tr>
                    <tr>
                        <td><b>Last Logon</b></td>
                        <td>{{ $ForumHelper::getTimeInDate(Auth::user()->lastactivity) }}</td>
                    </tr>
                    <tr>
                        <td><b>Member Since</b></td>
                        <td>{{ date('jS F Y', Auth::user()->joindate) }}</td>
                    </tr>
                    <tr>
                        <td><b>Current THC</b></td>
                        <td>{{ number_format(Auth::user()->credits) }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    @if(count($subscriptions) > 0)
    <div class="content-holder">
        <div class="content">
            <div class="contentHeader headerRed">
                <span>Active Subscriptions</span>
            </div>
            <div class="small-12">
                <table class="responsive" style="width: 100%;">
                    <tr>
                        <th style="width:50%">Name</th>
                        <th>Until</th>
                    </tr>
                    @foreach($subscriptions as $subscription)
                    <tr>
                        <td><b>{{ $subscription['name']}}</b></td>
                        <td>{{ $subscription['date']}}</td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- <div class="content-holder">
        <div class="content">
            <div class="contentHeader headerRed">
                <span>Snow</span>
            </div>
            Are you a bit cold? Do you need to turn the snow down? Or perhaps you need more to build a snowman! Here you can control how much snow falls on the site.

            <div class="slider" data-slider data-initial-start="{{ Auth::user()->snow }}" data-start="0" data-end="150">
                <span class="slider-handle headerBlue gradualfader"  data-slider-handle role="slider" tabindex="1"></span>
                <span class="slider-fill" data-slider-fill></span>
                <input id="snowamt" type="hidden" value="">
            </div>
            <button class="pg-green headerRed gradualfader fullWidth topBottom" style="float:right" onclick="saveSnow();">Save</button>
        </div>
    </div> -->
</div>

<script type="text/javascript">
    var saveSnow = function() {
        var snow = $('#snowamt').val();

        $.ajax({
            url: urlRoute.getBaseUrl() + 'usercp/snow',
            type: 'post',
            data: {snow:snow},
            success: function(data) {
                history.go(0);
                urlRoute.ohSnap('Weather conditions altered!', 'green');
            }
        });
    }

    var destroy = function() {
        saveSnow = null;
    }
</script>
