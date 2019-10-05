<script> urlRoute.setTitle("TH - Posting Fest Log");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>    
      <span>Posting Fest Log</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-8 column">

      <div class="content-holder">
        <div class="content">
                          <div class="contentHeader headerRed">
                    <span>Posting Fest Log</span>
                  </div>
          <table class="responsive" style="width: 100%;">
            <tr>
              <th>Username</th>
              <th>Region</th>
              <th>Threads</th>
              <th>Posts</th>
              <th>Articles</th>
            </tr>
              @foreach($postfest as $pf)
                <tr>
                  <td> {{ $pf->username }} </td>
                  <td> {{ $pf->region }} </td>
                  <td> {{ number_format($pf->threads) }} </td>
                  <td> {{ number_format($pf->posts) }} </td>
                  <td> {{ number_format($pf->articles) }} </td>
                </tr>
              @endforeach
          </table>
        </div>
      </div>
</div>
