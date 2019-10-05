<?php $ForumHelper = new \App\Helpers\ForumHelper; ?>
<script> urlRoute.setTitle("TH - Name Icon6");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Manage Name Icon</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-8 column">
        <div class="contentHeader headerRed">
          <a href="admincp/new/nameicon" class="web-page headerLink white_link">New Name Icon</a>
          <span>Manage Name Icon</span>
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
              <th>Icon</th>
              <th>Quantity</th>
              <th style="width:20%">Actions</th>
              <th>Edit</th>
            </tr>
            @foreach($nameicons as $nameicon)
              <tr>
                <td>{{ $nameicon['name'] }}</td>
                <td>{{ $nameicon['desc'] }}</td>
                <td>{{ $nameicon['price'] }}</td>
                <td><img src="{{ $nameicon['icon'] }}" /></td>
                <td>{{ $nameicon['limit'] }}</td>
                <td>
                  <select id="iconid-{{ $nameicon['iconid'] }}">
                    <option value="1">Edit Icon</option>
                    <option value="2">Delete Icon</option>
                  </select>
                </td>
                <td><a onclick="iconAction({{ $nameicon['iconid'] }});"><i class="fa fa-cog editcog4" aria-hidden="true"></i></a></td>
              </tr>
            @endforeach
          </table>
      </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  var iconAction = function(iconid) {
    var action = $('#iconid-'+iconid).val();

    switch(action) {
      case "1":
        //edit
        urlRoute.loadPage('/admincp/edit/nameicon/'+iconid);
      break;
      case "2":
        if(confirm('Are you sure you wanna delete this name icon, user that have it will lose it?')) {
          $.ajax({
            url: urlRoute.getBaseUrl() + 'admincp/nameicons/remove',
            type: 'post',
            data: {iconid:iconid},
            success: function(data) {
              urlRoute.loadPage('/admincp/list/nameicons');
              urlRoute.ohSnap('Name icon removed!', 'green');
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
