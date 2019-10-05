<script> urlRoute.setTitle("TH - Perm Shows");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/staff" class="bold web-page">StaffCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Perm Shows</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('staff.menu')
</div>
<div class="medium-8 column">
  <div class="content-holder"><div class="content">
  <div class="contentHeader headerRed">
                Perm Shows
                <a href="/staff/perm/add" class="headerLink white_link web-page">Add Perm Show</a>
              </div>
            <div class="content-ct">
                <div class="small-12">
                    <table class="responsive" style="width: 100%;">
                        <tr>
                            <th>Username</th>
                            <th>Type</th>
                            <th>Day</th>
                            <th>Time</th>
                            <th>Actions</th>
                        </tr>
                        @foreach($perm_shows as $perm_show)
                            <tr id="show-{{ $perm_show['timetableid'] }}">
                                <td>{!! $perm_show['username'] !!}</td>
                                <td>{{ $perm_show['type'] }}</td>
                                <td>{{ $perm_show['day'] }}</td>
                                <td>{{ $perm_show['time'] }}</td>
                                <td>
                                    <select id="timetableid-{{ $perm_show['timetableid'] }}">
                                        <option value="1">Edit Show</option>
                                        <option value="2">Delete Show</option>
                                    </select>
                                    <a onclick="showAction({{ $perm_show['timetableid'] }});"><i class="fa fa-cog editcog2" aria-hidden="true"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
  var showAction = function(timetableid) {
    var action = $('#timetableid-'+timetableid).val();

    switch(action) {
      case "1":
        urlRoute.loadPage('/staff/perm/edit/'+timetableid);
      break;
      case "2":
        //Remove the show
        $.ajax({
          url: urlRoute.getBaseUrl() + 'staff/manager/remove/perm',
          type: 'post',
          data: {timetableid:timetableid},
          success: function(data) {
            $('#show-'+timetableid).fadeOut();
            urlRoute.ohSnap('Perm show removed!', 'greed');
          }
        });
      break;
    }
  }

  var destroy = function() {
    showAction = null;
  }
</script>
