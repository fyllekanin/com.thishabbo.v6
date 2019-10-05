<?php $ForumHelper = new \App\Helpers\ForumHelper; ?>
<script> urlRoute.setTitle("TH - Backgrounds");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Manage Backgrounds</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-8 column">
        <div class="contentHeader headerRed">
          <a href="admincp/new/background" class="web-page headerLink white_link">New Background</a>
          <span>Manage Background</span>
        </div>
  <div class="content-holder">
  <div class="content">
        <div class="content-ct">
        <div class="small-12">
          <table class="responsive" style="width: 100%;">
            <tr>
              <th>Name</th>
              <th>Description</th>
              <th>Price</th>
              <th>Background</th>
              <th>Quantity</th>
              <th style="width:20%">Actions</th>
              <th>Edit</th>
            </tr>
            @foreach($backgrounds as $background)
              <tr>
                <td>{{ $background['name'] }}</td>
                <td>{{ $background['desc'] }}</td>
                <td>{{ $background['price'] }}</td>
                <td><img src="{{ $background['background'] }}" /></td>
                <td>{{ $background['limit'] }}</td>
                <td>
                  <select id="backgroundid-{{ $background['backgroundid'] }}">
                    <option value="1">Edit Background</option>
                    <option value="2">Delete Background</option>
                  </select>
                </td>
                <td><a onclick="backgroundAction({{ $background['backgroundid'] }});"><i class="fa fa-cog editcog4" aria-hidden="true"></i></a></td>
              </tr>
            @endforeach
          </table>
      </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  var backgroundAction = function(backgroundid) {
    var action = $('#backgroundid-'+backgroundid).val();

    switch(action) {
      case "1":
        //edit
        urlRoute.loadPage('/admincp/edit/background/'+backgroundid);
      break;
      case "2":
        if(confirm('Are you sure you wanna delete this name background, user that have it will lose it?')) {
          $.ajax({
            url: urlRoute.getBaseUrl() + 'admincp/backgrounds/remove',
            type: 'post',
            data: {backgroundid:backgroundid},
            success: function(data) {
              urlRoute.loadPage('/admincp/list/backgrounds');
              urlRoute.ohSnap('Name background removed!', 'green');
            }
          });
        }
      break;
    }
  }

  var destroy = function() {
    subscriptionAction = null;
  }
</script>
