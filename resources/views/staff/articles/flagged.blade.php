<script> urlRoute.setTitle("TH - List Articles");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/staff" class="bold web-page">StaffCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Flagged Articles</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('staff.menu')
</div>
<div class="medium-8 column">

  <div class="content-holder"><div class="content">
                <div class="contentHeader headerRed">
                Flagged Articles
              </div>
            <div class="content-ct">
                <div class="small-12">
                    <table class="responsive" style="width: 100%;">
                        <tr>
                            <th>Title</th>
                            <th>Availability</th>
                            <th>Reason</th>
                            <th>Change Availability</th>
                            <th>Close</th>
                        </tr>
                        @foreach($flagged_articles as $flagged_article)
                            <tr id="flagid-{{ $flagged_article['flagid'] }}">
                                <td><a href="/article/{{ $flagged_article['articleid'] }}/page/1" class="web-page">{{ $flagged_article['title'] }}</a></td>
                                <td>{{ $flagged_article['type'] == 1 ? 'Available' : 'Unavailable' }}</td>
                                <td>{{ $flagged_article['reason'] }}</td>
                                <td><i class="fa fa-check-circle-o editcog4" aria-hidden="true" onclick="handleFlagged({{ $flagged_article['flagid'] }});"></i></td>
                                <td><i class="fa fa-times editcog4" aria-hidden="true" onclick="closeFlagged({{ $flagged_article['flagid'] }});"></i></td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function closeFlagged(flagid) {
        $('#flagid-'+flagid).fadeOut();
        $.ajax({
            url: urlRoute.getBaseUrl() + 'staff/article/flagged/close',
            type: 'post',
            data: {flagid:flagid},
            success: function(data) {
                urlRoute.ohSnap('Closed!', 'green');
            }
        });
    }

    function handleFlagged(flagid) {
        $('#flagid-'+flagid).fadeOut();
        $.ajax({
            url: urlRoute.getBaseUrl() + 'staff/article/flagged/handle',
            type: 'post',
            data: {flagid:flagid},
            success: function(data) {
                urlRoute.ohSnap('Handled!', 'green');
            }
        });
    }
</script>
