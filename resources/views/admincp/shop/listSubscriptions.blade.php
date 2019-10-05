<?php $ForumHelper = new \App\Helpers\ForumHelper; ?>
<script> urlRoute.setTitle("TH - Subscription Packages");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>    
      <span>Manage Subscription</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-8 column">
                  <div class="contentHeader headerRed">
                    <span>Manage Subscription</span>
                    <a href="/admincp/subscription/new" class="web-page headerLink white_link">New Subscription</a>
                  </div>
      <div class="content-holder">
        <div class="content">     
        <div class="small-12">
          <table class="responsive" style="width: 100%;">
            <tr>
              <th>Name</th>
              <th>Description</th>
              <th>Usergroup</th>
              <th>Diamonds Price</th>
              <th>Userbar Text</th>
              <th>Actions</th>
              <th>Edit</th>
            </tr>
            @foreach($subscriptions as $subscription)
              <tr>
                <td>{{ $subscription['name'] }}</td>
                <td>{{ $subscription['desc'] }}</td>
                <td>{{ $subscription['usergroup'] }}</td>
                <td>{{ $subscription['dprice'] }}</td>
                <td>{{ $subscription['userbar_text'] }}</td>
                <td>
                  <select id="packageid-{{ $subscription['packageid'] }}">
                    <option value="1">Edit Subscription</option>
                    <option value="2">Delete Subscription</option>
                  </select>
                </td>
                <th>                  
                  <a onclick="subscriptionAction({{ $subscription['packageid'] }});"><i class="fa fa-cog editcog4" aria-hidden="true"></i></a>
                </th>
              </tr>
            @endforeach
          </table>
        </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  var subscriptionAction = function(packageid) {
    var action = $('#packageid-'+packageid).val();

    switch(action) {
      case "1":
        //edit
        urlRoute.loadPage('/admincp/subscription/edit/'+packageid);
      break;
      case "2":
        if(confirm('Are you sure you wanna delete this subscription?')) {
          $.ajax({
            url: urlRoute.getBaseUrl() + 'admincp/subscription/remove',
            type: 'post',
            data: {packageid:packageid},
            success: function(data) {
              urlRoute.loadPage('/admincp/list/subscriptions');
              urlRoute.ohSnap('Subscription removed!', 'green');
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
