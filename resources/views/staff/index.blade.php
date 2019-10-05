<script> urlRoute.setTitle("TH - Staff Panel");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>StaffCP</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('staff.menu')
</div>
<div class="medium-8 column">

<div class="userInfo">

        <div class="small-3 column end">
            <div class="shop-box">
                <div class="usercpBoxes" title="">
                    <center>
                        <i class="fa fa-calendar fontSize" aria-hidden="true"></i>
                        <br />
                        <div class="usercpCurrency">{{ $events_booked }} / 168</div>
                        Events Booked
                    </center>
                </div>
            </div>
        </div>

        <div class="small-3 column end">
            <div class="shop-box">
                <div class="usercpBoxes" title="">
                    <center>
                        <i class="fa fa-user fontSize" aria-hidden="true"></i>
                        <br />
                        <div class="usercpCurrency">{{ $slots_booked }}</div>
                        Slots booked by {{ Auth::user()->username }}
                    </center>
                </div>
            </div>
        </div>

        <div class="small-3 column end">
            <div class="shop-box">
                <div class="usercpBoxes" title="">
                    <center>
                        <i class="fa fa-user fontSize" aria-hidden="true"></i>
                        <br />
                        <div class="usercpCurrency">{{ $quest_guides }}</div>
                        Quest Guides in 2019
                    </center>
                </div>
            </div>
        </div>

        <div class="small-3 column end">
            <div class="shop-box">
                <div class="usercpBoxes" title="">
                    <center>
                        <i class="fa fa-headphones fontSize" aria-hidden="true"></i>
                        <br />
                        <div class="usercpCurrency">{{ $radio_booked }} / 168</div>
                        Radio Slots Booked
                    </center>
                </div>
            </div>
        </div>

        <div class="small-4 column end">
            <div class="shop-box" style="height:auto !important; padding: 12px !important;">
                    <center>
                        <img src="{{ asset("_assets/img/website/award_1.gif") }}" style="width: 23%;"><br />
                        <div class="usercpCurrency">{{ $radiopoints[0]['region'] }}</div>
                        {{ $radiopoints[0]['points'] }}
                    </center>
            </div>
        </div>

        <div class="small-4 column end">
            <div class="shop-box" style="height:auto !important; padding: 12px !important;">
                    <center>
                        <img src="{{ asset("_assets/img/website/award_2.gif") }}" style="width: 23%;"><br />
                        <div class="usercpCurrency">{{ $radiopoints[1]['region'] }}</div>
                        {{ $radiopoints[1]['points'] }}
                    </center>
            </div>
        </div>

        <div class="small-4 column end">
            <div class="shop-box" style="height:auto !important; padding: 12px !important;">
                    <center>
                        <img src="{{ asset("_assets/img/website/award_3.gif") }}" style="width: 23%;"><br />
                        <div class="usercpCurrency">{{ $radiopoints[2]['region'] }}</div>
                        {{ $radiopoints[2]['points'] }}
                    </center>
            </div>
        </div>

    </div>
<br clear="all">
  <div class="content-holder">
  <div class="content">
  <div class="contentHeader headerRed">
            Staff Control Panel
      </div>
    <div class="content-ct">
        Welcome to the brand new ThisHabbo V6 Staff Panel. Unlike Version 5's Staff Panel which was built on vBulletin, this has been completely recoded and redesigned. Everything you see, has no link with THV5 at all. This is a permission ran panel - therefore, if you don't have access to the forums, you don't have access to the panel!<br />
        <br>
        If you're new - Welcome!
        <br><br>
        <b>ThisHabbo Admins</b>
    </div>
    </div>
  </div>


</div>
