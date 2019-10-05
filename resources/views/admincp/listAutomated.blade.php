<script> urlRoute.setTitle("TH - Automated Threads");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
          <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
           <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
          <span>Manage Automated Threads</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-8 column">
      <div class="contentHeader headerRed">

                    <span>Automated Threads</span>
       <a href="/admincp/settings/new/automated" class="web-page headerLink white_link">New Automated Thread</a>
      </div>
  <div class="content-holder">
  <div class="content">
        <div class="content-ct">
        <div class="small-12">
          <table class="responsive" style="width: 100%;">
            <tr>
              <th>Title</th>
              <th>User</th>
              <th>Day</th>
              <th>Hour</th>
              <th>Minute</th>
              <th>Action</th>
              <th>Edit</th>
            </tr>
            @foreach($automated_threads as $automated_thread)
              <tr>
                <td>{{ $automated_thread['title'] }}</td>
                <td>{{ $automated_thread['postuser'] }}</td>
                <td>{{ $automated_thread['day'] }}</td>
                <td>{{ $automated_thread['hour'] }}</td>
                <td>{{ $automated_thread['min'] }}</td>
                <td>
                  <select id="at-{{ $automated_thread['atid'] }}">
                    <option value="1">Edit</option>
                    <option value="2">Delete</option>
                  </select>
                  <td><a onclick="atAction({{ $automated_thread['atid'] }});"><i class="fa fa-cog editcog4" aria-hidden="true"></i></a>
                </td>
              </tr>
            @endforeach
          </table>
      </div>
      </button>
    </div>
  </div>
</div>

<script type="text/javascript">
  var atAction = function(atid) {

    var option = $('#at-'+atid).val();

    switch(option) {
      case "1":
        urlRoute.loadPage('/admincp/settings/edit/automated/'+atid);
      break;
      case "2":
        $.ajax({
          url: urlRoute.getBaseUrl() + 'admincp/settings/delete/automated',
          type: 'post',
          data: {atid:atid},
          success: function(data) {
            urlRoute.loadPage('/admincp/settings/automated');
          }
        })
      break;
    }
  }

  var destroy = function() {
    atAction = null;
  }
</script>
