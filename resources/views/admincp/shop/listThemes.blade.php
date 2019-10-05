<?php $ForumHelper = new \App\Helpers\ForumHelper; ?>
<script> urlRoute.setTitle("TH - Themes");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>    
      <span>Manage Themes</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-8 column">
                  <div class="contentHeader headerRed">
                    <span>Manage Themes</span>
                    <a href="/admincp/theme/new" class="web-page headerLink white_link">New Theme</a>
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
              <th>Default Theme</th>
              <th>Actions</th>
              <th>Edit</th>              
            </tr>
            @foreach($themes as $theme)
              <tr>
                <td>{{ $theme->name }}</td>
                <td>{{ $theme->description }}</td>
                <td>{{ $theme->price }}</td>
                <td>@if($theme->default_theme == 1) Default @else None default @endif</td>
                <td>
                  <select id="themeid-{{ $theme->themeid }}">
                    <option value="1">Edit Theme</option>
                    <option value="2">Delete Theme</option>
                    @if($theme->default_theme == 1)
                      <option value="4">Remove Style as Default</option>
                    @else
                      <option value="3">Make Default</option>
                    @endif
                  </select>
                <td><a onclick="themeAction({{ $theme->themeid }});"><i class="fa fa-cog editcog4" aria-hidden="true"></i></a></td>                  
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
  var themeAction = function(themeid) {
    var action = $('#themeid-'+themeid).val();

    switch(action) {
      case "1":
        //edit
        urlRoute.loadPage('/admincp/theme/edit/'+themeid);
      break;
      case "2":
        if(confirm('Are you sure you wanna delete this theme?')) {
          $.ajax({
            url: urlRoute.getBaseUrl() + 'admincp/theme/remove',
            type: 'post',
            data: {themeid:themeid},
            success: function(data) {
              urlRoute.loadPage('/admincp/list/themes');
              urlRoute.ohSnap('Theme removed!', 'green');
            }
          });
        }
      break;
      case "3":
        if(confirm('Are you sure you want to make this theme default? (it will make all other default themes non-default automatically.)')) {
          $.ajax({
            url: urlRoute.getBaseUrl() + 'admincp/theme/default',
            type: 'post',
            data: {themeid:themeid, mode:1},
            success: function(data) {
              urlRoute.loadPage('/admincp/list/themes');
              urlRoute.ohSnap('Theme made default!', 'green');
            }
          });
        }
      break;
      case "4":
        if(confirm('Are you sure you wanna make this theme none default again?')) {
          $.ajax({
            url: urlRoute.getBaseUrl() + 'admincp/theme/default',
            type: 'post',
            data: {themeid:themeid, mode:0},
            success: function(data) {
              urlRoute.loadPage('/admincp/list/themes');
              urlRoute.ohSnap('Theme made none default!', 'green');
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
