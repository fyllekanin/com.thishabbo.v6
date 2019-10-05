<?php $ForumHelper = new \App\Helpers\ForumHelper; ?>
<script> urlRoute.setTitle("TH - Boxes");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Manage Boxes</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-8 column">
        <div class="contentHeader headerRed">
          <a href="admincp/new/box" class="web-page headerLink white_link">New Box</a>
          <span>Manage Box</span>
        </div>
  <div class="content-holder">
  <div class="content">
        <div class="content-ct">
        <div class="small-12">
          <table class="responsive" style="width: 100%;">
            <tr>
              <th>Name</th>
              <th>Price</th>
              <th>Box</th>
              <th style="width:20%">Actions</th>
              <th>Edit</th>
            </tr>
            @foreach($boxes as $box)
              <tr>
                <td>{{ $box['name'] }}</td>
                <td>{{ $box['price'] }}</td>
                <td><img src="{{ $box['box'] }}" /></td>
                <td>
                  <select id="boxid-{{ $box['boxid'] }}">
                    <option value="1">Edit Box</option>
                    <option value="2">Delete Box</option>
                  </select>
                </td>
                <td><a onclick="boxAction({{ $box['boxid'] }});"><i class="fa fa-cog editcog4" aria-hidden="true"></i></a></td>
              </tr>
            @endforeach
          </table>
      </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  var boxAction = function(boxid) {
    var action = $('#boxid-'+boxid).val();

    switch(action) {
      case "1":
        //edit
        urlRoute.loadPage('/admincp/edit/box/'+boxid);
      break;
      case "2":
        if(confirm('Are you sure you wanna delete this box, user that have it will lose it?')) {
          $.ajax({
            url: urlRoute.getBaseUrl() + 'admincp/boxes/remove',
            type: 'post',
            data: {boxid:boxid},
            success: function(data) {
              urlRoute.loadPage('/admincp/list/boxes');
              urlRoute.ohSnap('Box removed!', 'green');
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
