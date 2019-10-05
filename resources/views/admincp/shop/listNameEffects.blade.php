<?php $ForumHelper = new \App\Helpers\ForumHelper; ?>
<script> urlRoute.setTitle("TH - Name Effects");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>    
      <span>Manage Name Effects</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-8 column">
        <div class="contentHeader headerRed">
          <a href="admincp/new/nameeffect" class="web-page headerLink white_link">New Name Effect</a>
          <span>Manage Name Effects</span>
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
              <th>Effect</th>
              <th>Quantity</th>
              <th>Actions</th>
              <th>Edit</th>
            </tr>
            @foreach($nameeffects as $nameeffect)
              <tr>
                <td>{{ $nameeffect['name'] }}</td>
                <td>{{ $nameeffect['desc'] }}</td>
                <td>{{ $nameeffect['price'] }}</td>
                <td><span style="background-image: url({{ $nameeffect['effect'] }});" class="nameEffectClass">Username</span></td>
                <td>{{ $nameeffect['limit'] }}</td>
                <td>
                  <select id="effectid-{{ $nameeffect['effectid'] }}">
                    <option value="1">Edit Effect</option>
                    <option value="2">Delete Effect</option>
                  </select>
                </td>
                <td><a onclick="effectAction({{ $nameeffect['effectid'] }});"><i class="fa fa-cog editcog4" aria-hidden="true"></i></a></td>                
              </tr>
            @endforeach
          </table>
      </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  var effectAction = function(effectid) {
    var action = $('#effectid-'+effectid).val();

    switch(action) {
      case "1":
        //edit
        urlRoute.loadPage('/admincp/edit/nameeffect/'+effectid);
      break;
      case "2":
        if(confirm('Are you sure you wanna delete this name icon, user that have it will lose it?')) {
          $.ajax({
            url: urlRoute.getBaseUrl() + 'admincp/nameeffects/remove',
            type: 'post',
            data: {effectid:effectid},
            success: function(data) {
              urlRoute.loadPage('/admincp/list/nameeffects');
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
