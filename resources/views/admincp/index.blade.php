<script> urlRoute.setTitle("TH - AdminCP");</script>
<?php $last_online = \App\Helpers\ForumHelper::getOnlineAdmins(); ?>
<?php $ForumHelper = new \App\Helpers\ForumHelper; ?>
<?php $ten_newest_users = \App\Helpers\ForumHelper::getNewestUsers(); ?>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>AdminCP</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-8 column">

<div class="userInfo">
        <div class="small-3 column end">
            <div class="shop-box">
                <div class="usercpBoxes" title="">
                    <center>
                        <i class="fa fa-commenting fontSize" aria-hidden="true"></i>
                        <br />
                        <div class="usercpCurrency">{{ number_format($posts_today) }}</div>
                        Posts Today
                    </center>
                </div>
            </div>
        </div>

        <div class="small-3 column end">
            <div class="shop-box">
                <div class="usercpBoxes" title="">
                    <center>
                        <i class="fa fa-commenting fontSize" aria-hidden="true"></i>
                        <br />
                        <div class="usercpCurrency">{{ number_format($posts_this_week) }}</div>
                        Posts in last 7 days
                    </center>
                </div>
            </div>
        </div>

        <div class="small-3 column end">
            <div class="shop-box">
                <div class="usercpBoxes" title="">
                    <center>
                        <i class="fa fa-commenting fontSize" aria-hidden="true"></i>
                        <br />
                        <div class="usercpCurrency">{{ number_format($posts_this_month) }}</div>
                        Posts in last 30 days
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
                        <div class="usercpCurrency">{{ number_format($thcb_subscriptions) }}</div>
                        Active THCB Purchases
                    </center>
                </div>
            </div>
        </div>

        <div class="small-3 column end">
            <div class="shop-box">
                <div class="usercpBoxes" title="">
                    <center>
                        <i class="fa fa-ticket fontSize" aria-hidden="true"></i>
                        <br />
                        <div class="usercpCurrency">{{ number_format($thc_count) }}</div>
                        THC in Economy!
                    </center>
                </div>
            </div>
        </div>

        <div class="small-3 column end">
            <div class="shop-box">
                <div class="usercpBoxes" title="">
                    <center>
                        <i class="fa fa-diamond fontSize" aria-hidden="true"></i>
                        <br />
                        <div class="usercpCurrency">{{ number_format($thd_count) }}</div>
                        THD in Economy!
                    </center>
                </div>
            </div>
        </div>

        <div class="small-3 column end">
            <div class="shop-box">
                <div class="usercpBoxes" title="">
                    <center>
                        <i class="fa fa-shopping-bag fontSize" aria-hidden="true"></i>
                        <br />
                        <div class="usercpCurrency">{{ number_format($shop_sold) }}</div>
                        Shop items sold!
                    </center>
                </div>
            </div>
        </div>

        <div class="small-3 column end">
            <div class="shop-box">
                <div class="usercpBoxes" title="">
                    <center>
                        <i class="fa fa-question-circle-o fontSize" aria-hidden="true"></i>
                        <br />
                        <div class="usercpCurrency">0</div>
                        Something Here?
                    </center>
                </div>
            </div>
        </div>
</div><br clear="all">

  <div class="content-holder">
  <div class="content">
  <div class="contentHeader headerRed">
            Administration Control Panel
      </div>
  <div class="content-ct">
        <div class="small-12">
          <table class="responsive" style="width: 100%;">
              <tr>
                <td><b>Total Number of Users</b></td>
                <td>{{ number_format($users) }} (Newest User: <a href="/profile/{{ $userlatest }}" class="web-page">{{ $userlatest }}</a>)</td>
              </tr>
              <tr>
                <td><b>Total Number of Threads</b></td>
                <td>{{ number_format($threads) }}</td>
              </tr>
              <tr>
                <td><b>Total Number of Posts</b></td>
                <td>{{ number_format($posts) }} ({{ number_format($posts_today) }} posts today)</td>
              </tr>
              <tr>
                <td><b>Most Replied to Thread</b></td>
                <td><a href="/forum/thread/{{ $replies_id }}/page/1" class="web-page">{{ $replies_title }}</a> with {{ number_format($replies) }} replies</td>
              </tr>
              <tr>
                <td><b>Most Active Forum</b></td>
                <td>{!! $most_active_frm !!}</td>
              </tr>
              <tr>
                <td><b>Andy's IP Check:</b></td>
                <td><? echo $_SERVER['REMOTE_ADDR']; ?></td>
              </tr>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="content-holder">
  <div class="content">
   <div class="contentHeader headerRed">
            Online Administrators
      </div>
	  		<div class="content-ct ct-center">
	  				@foreach($last_online as $last)
	    					<a href="/profile/{{ $last['username'] }}/page/1" class="web-page">
	    						<div class="last_online tooltip_box hover-box-info" style="background-image: url('{{ $last['avatar'] }}');" title="{{ $last['username'] }}"></div>
	    					</a>
	   				@endforeach
	  		</div>
		</div>
	</div>
  <div class="content-holder">
    <div class="content">
      <div class="content-ct">
        <div class="contentHeader headerGreen">
          <span>Top 10 Newest Users</span>
        </div>
        <div class="small-12">
          <table class="responsive" style="max-width: 100%;">
            <tr>
              <th style="width: 25%;">#</th>
              <th style="width: 25%;">Username</th>
              <th style="width: 25%;">Habbo</th>
              <th style="width: 25%;">Actions</th>
            </tr>
            @foreach($ten_newest_users as $newest)
              <tr id="bbcode-1">
                <td>{{ $newest['userid'] }}</td>
                <td>{{ $newest['username'] }}</td>
                <td>{{ $newest['habbo'] }}</td>
                <td>
                  <button class="pg-red headerRed gradualfader topBottom" onclick='window.open("/profile/{{ $newest['username'] }}");' style="float:none !important;">Visit Profile</button>
                </td>
              </tr>
            @endforeach
          </table>
      </div>
      </div>
    </div>
</div>
