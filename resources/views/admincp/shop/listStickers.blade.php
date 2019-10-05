<?php $ForumHelper = new \App\Helpers\ForumHelper; ?>
<script> urlRoute.setTitle("TH - Stickers");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
          <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
           <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
          <span>Manage Stickers</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-8 column">
        <div class="contentHeader headerRed">
          <a href="admincp/new/sticker" class="web-page headerLink white_link">New Sticker</a>
          <span>Manage Stickers</span>
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
              <th>Sticker</th>
              <th>Quantity</th>
              <th>Actions</th>
              <th>Edit</th>
            </tr>
            @foreach($stickers as $sticker)
              <tr>
                <td>{{ $sticker['name'] }}</td>
                <td>{{ $sticker['desc'] }}</td>
                <td>{{ $sticker['price'] }}</td>
                <td><img src="{{ $sticker['sticker'] }}" /></td>
                <td>{{ $sticker['limit'] }}</td>
                <td>
                  <select id="stickerid-{{ $sticker['stickerid'] }}">
                    <option value="1">Edit Sticker</option>
                    <option value="2">Delete Sticker</option>
                  </select>
                   <td><a onclick="stickerAction({{ $sticker['stickerid'] }});"><i class="fa fa-cog editcog4" aria-hidden="true"></i></a></td>
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
  var stickerAction = function(stickerid) {
    var action = $('#stickerid-'+stickerid).val();

    switch(action) {
      case "1":
        //edit
        urlRoute.loadPage('/admincp/edit/sticker/'+stickerid);
      break;
      case "2":
        if(confirm('Are you sure you want to delete this sticker? the users that have it will lose it!')) {
          $.ajax({
            url: urlRoute.getBaseUrl() + 'admincp/stickers/remove',
            type: 'post',
            data: {stickerid:stickerid},
            success: function(data) {
              urlRoute.loadPage('/admincp/list/stickers');
              urlRoute.ohSnap('Sticker removed!', 'green');
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
