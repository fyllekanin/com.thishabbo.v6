<script> urlRoute.setTitle("TH - Statistics");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Statistics</span>
    </div></div>
  </div>
</div>

<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-8 column">
                  <div class="contentHeader headerRed">
                    <span>Todays Statistics - Current Server Time: {{ date('Y-m-d H:i') }}</span>
                  </div>
      <div class="content-holder">
        <div class="content">
            <b>Amount of posts:</b> {{ number_format($todayStats['posts']) }} <br />
            <b>Amount of threads:</b> {{ number_format($todayStats['threads']) }} <br />
            <b>Amount of visitor messages:</b> {{ number_format($todayStats['visitor_messages']) }} <br />
            <hr />
            <b>Amount of creations uploaded:</b> {{ number_format($todayStats['creations']) }} <br />
            <b>Amount of creation comments:</b> {{ number_format($todayStats['creation_comments']) }} <br />
            <hr />
            <b>Amount of articles:</b> {{ number_format($todayStats['articles']) }} <br />
            <b>Amount of article comments:</b> {{ number_format($todayStats['article_comments']) }} <br />
          </div>
      </div>

  <div class="contentHeader headerRed">
    <span>This Week</span>
  </div>
  <div class="content-holder">
        <div class="content">
          <div class="content-ct">
            <table class="responsive" style="width: 100%;">
              <tr>
                <th>Day</th>
                <th>Posts</th>
                <th>Threads</th>
                <th>Visitor Messages</th>
                <th>Creations</th>
                <th>Creation Comments</th>
                <th>Articles</th>
                <th>Article Comments</th>
              </tr>
              @foreach($statsLog as $stat)
                <tr>
                  <td> {{ date('D', $stat->dateline )}} </td>
                  <td> {{ number_format($stat->posts) }} </td>
                  <td> {{ number_format($stat->threads) }} </td>
                  <td> {{ number_format($stat->visitor_messages) }} </td>
                  <td> {{ number_format($stat->creations) }} </td>
                  <td> {{ number_format($stat->creation_comments) }} </td>
                  <td> {{ number_format($stat->articles) }} </td>
                  <td> {{ number_format($stat->article_comments) }} </td>
                </tr>
              @endforeach
            </table>
          </div>
      </div>
    </div>
</div>
