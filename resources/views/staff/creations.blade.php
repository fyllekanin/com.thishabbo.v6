<script> urlRoute.setTitle("TH - Creations");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/staff" class="bold web-page">StaffCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Creations</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('staff.menu')
</div>
<div class="medium-8 column">
  <div class="content-holder"><div class="content">
  <div class="contentHeader headerRed">
                Creations Awaiting Approval
              </div>
            <div class="content-ct">
      <div class="small-12">
        <table class="responsive" style="width: 100%;">
          <tr>
            <th>Creation</th>
            <th>Name</th>
            <th>User</th>
            <th>Actions</th>
            <th>Edit</th>
          </tr>
          @foreach($creations as $creation)
            <tr>
              <td>
                <img src="{{ $creation['image'] }}" alt="thumbnail" />
              </td>
              <td>{{ $creation['name'] }}</td>
              <td>{{ $creation['username'] }}</td>
              <td>
                <select id="creationid-{{ $creation['creationid'] }}">
                  <option value="1">Approve Creation</option>
                  <option value="2">Delete Creation</option>
                </select>
              </td>
              <td><a onclick="creationAction({{ $creation['creationid'] }});"><i class="fa fa-cog editcog4" aria-hidden="true"></i></a></td>
            </tr>
          @endforeach
        </table>
      </div>
    </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  var creationAction = function(creationid) {
    var action = $('#creationid-'+creationid).val();

    switch(action) {
      case "1":
        $.ajax({
          url: urlRoute.getBaseUrl() + 'staff/mod/creation/approve',
          type: 'post',
          data: {creationid:creationid},
          success: function(data) {
            urlRoute.ohSnap('Creation Approved!', 'green');
            urlRoute.loadPage('/staff/mod/creations');
          }
        });
      break;
      case "2":
        $.ajax({
          url: urlRoute.getBaseUrl() + 'staff/mod/creation/delete',
          type: 'post',
          data: {creationid:creationid},
          success: function(data) {
            urlRoute.ohSnap('Creation deleted!', 'green');
            urlRoute.loadPage('/staff/mod/creations');
          }
        });
      break;
    }
  }

  var destroy = function() {
    creationAction = null;
  }
</script>
