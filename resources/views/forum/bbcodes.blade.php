<script>urlRoute.setTitle("TH - Current BBCodes");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
          <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
          <span>BBCode List</span>
    </div>
  </div>
</div>

<div class="medium-12 column">

  <div class="content-holder">
      <div class="content">
      <div class="contentHeader headerGreen">
                    <span>Current BBCodes</span>
                  </div>
      <div class="content-ct">
        <div class="small-12">
          <table class="responsive" style="max-width: 100%;">
            <tr>
              <th>Name</th>
              <th>Example</th>
              <th>Preview</th>
            </tr>
            @foreach($bbcodes as $bbcode)
              <tr id="bbcode-{{ $bbcode['bbcodeid'] }}">
                <td>{{ $bbcode['name'] }}</td>
                <td style="max-width: 10rem;">{{ $bbcode['example'] }}</td>
                <td style="max-width: 10rem;">{!! $bbcode['result'] !!}</td>
              </tr>
            @endforeach
          </table>
      </div>
      </div>
    </div>
</div>

<script type="text/javascript">
  var destroy = function() {
    bbcodeAction = null;
  }
</script>
