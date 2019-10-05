<script> urlRoute.setTitle("TH - Live Radio Statistics");</script>

<div class="small-12 column">
    <div class="content-holder">
            <div class="content contentpadding">
                <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
                <span><a href="/staff" class="bold web-page">StaffCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
                Live Radio Statistics
            </div>
    </div>
</div>

<div class="medium-4 column">
  @include('staff.menu')
</div>

<div class="medium-8 column">
  <div class="content-holder">
    <div class="content">
    <div class="contentHeader headerBlue">
    <span>Live Radio Statistics</span>
  </div>
      <div class="content-ct">
        <table class="responsive" style="width: 100%;">
          <tr>
            <th>Item</th>
            <th>Statistics</th>
          </tr>
          <tr>
            <td><b>Stream Status</b></td>
            <td>{!! $streamstatus !!}</td>
          </tr>
          <tr>
            <td><b>Bitrate</b></td>
            <td>{{ $bitrate }}kbps</td>
          </tr>
          <!--<tr>
            <td><b>Listener Count</b></td>
            <td>{{ $listeners }} listeners</td>
          </tr>-->
          <tr>
            <td><b>Unique Listener Count</b></td>
            <td>{{ $uniquelisteners }} unique listeners</td>
          </tr>
          <!--<tr>
            <td><b>Listener Peak</b></td>
            <td>{{ $listenerpeak }} listener peak</td>
          </tr>-->
          <tr>
            <td><b>Stream Title</b></td>
            <td>{{ $dj }}</td>
          </tr>
          <tr>
            <td><b>Genre</b></td>
            <td>{{ $genre }}</td>
          </tr>
          <tr>
            <td><b>Song</b></td>
            <td>{{ $song }}</td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>
