<script>urlRoute.setTitle("TH - Manage BBCodes");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
          <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
           <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
          <span>Manage BBCode</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-8 column">

  <div class="content-holder">
                <div class="content">
                                  <div class="contentHeader headerGreen">
                    <a href="/admincp/settings/bbcode/add" class="web-page headerLink white_link">Add New BBCode</a>
                    <span>Manage BBCodes</span>
                  </div>
      <div class="content-ct">
        <div class="small-12">
          <table class="responsive" style="max-width: 100%;">
            <tr>
              <th>Name</th>
              <th>Example</th>
              <th>Actions</th>
              <th>Edit</th>
            </tr>
            @foreach($bbcodes as $bbcode)
              <tr id="bbcode-{{ $bbcode['bbcodeid'] }}">
                <td>{{ $bbcode['name'] }}</td>
                <td style="overflow-x: scroll; max-width: 10rem;">{{ $bbcode['example'] }}</td>
                <td>
                  <select id="bbcodeid-{{ $bbcode['bbcodeid'] }}">
                    <option value="1">Edit BBCode</option>
                    <option value="2">Remove BBCode</option>
                  </select>
                  <td><a onclick="bbcodeAction({{ $bbcode['bbcodeid']}});"><i class="fa fa-cog editcog4" aria-hidden="true"></i></a></td>
                </td>
              </tr>
            @endforeach
          </table>
      </div>
      </div>
    </div>
</div>

<script type="text/javascript">
  var bbcodeAction = function(bbcodeid) {
    var action = $('#bbcodeid-'+bbcodeid).val();

    switch(action) {
      case "1":
        urlRoute.loadPage('admincp/settings/bbcode/edit/'+bbcodeid);
      break;
      case "2":
        if(confirm('Are you sure you want to delete this BBCode?')) {
          $.ajax({
            url: urlRoute.getBaseUrl() + 'admincp/settings/remove/bbcode',
            type: 'post',
            data: {bbcodeid:bbcodeid},
            success: function(data) {
              $('#bbcode-'+bbcodeid).fadeOut();
            }
          });
        }
      break;
    }
  }

  var destroy = function() {
    bbcodeAction = null;
  }
</script>
