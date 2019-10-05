<?php $ForumHelper = new \App\Helpers\ForumHelper; ?>
<script> urlRoute.setTitle("TH - Edit {{ $group['title'] }}");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>    
      <span>Add New Moderation Forum</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-8 column">
                  <div class="contentHeader headerRed">
        <span>Edit Usergroup: {{ $group['title'] }}</span>
          <a href="/admincp/usergroups" class="web-page headerLink white_link">Back</a>   
                  </div>
      <div class="content-holder">
        <div class="content">
          <label for="group-edit-title">Title</label>
          <input type="text" id="group-edit-title" value="{{ $group['title'] }}" class="login-form-input"/>
          <label for="group-edit-opentag">Name Color <i style="font-size: 0.7rem;">(Only the css)</i></label>
          <input type="text" id="group-edit-opentag" value="{{ $group['opentag'] }}" class="login-form-input"/>
          <label for="group-edit-height">Avatar Height</label>
          <input type="number" id="group-edit-height" value="{{ $group['height'] }}" class="login-form-input"/>
          <label for="group-edit-width">Avatar Width <i style="font-size: 0.7rem;">(Prefered: 200)</i></label>
          <input type="number" id="group-edit-width" value="{{ $group['width'] }}" class="login-form-input"/>
          <label for="group-add-immunity">Immunity <i style="font-size: 0.7rem;">(0 to {{ $immunity }})</i></label>
          <input type="number" id="group-add-immunity" value="{{ $group['immunity'] }}" class="login-form-input" max="100" min="1"/>
        @if($is_super)
            <label for="group-edit-editable">Group editable? <i style="font-size: 0.7rem;">If no, ONLY super admins can control and edit this in the future.</i></label>
            <select id="group-edit-editable" class="login-form-input">
              <option value="0" @if($group['editable'] == 0) selected="" @endif>No</option>
              <option value="1" @if($group['editable'] == 1) selected="" @endif>Yes</option>
            </select>
        @endif
        <br>        
          <button onclick="editGroup({{ $group['groupid'] }});" class="pg-red headerRed gradualfader fullWidth topBottom">Edit Usergroup</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  var editGroup = function(groupid) {
    var title = $('#group-edit-title').val();
    var opentag = $('#group-edit-opentag').val();
    var height = $('#group-edit-height').val();
    var width = $('#group-edit-width').val();
    var immunity = $('#group-add-immunity').val();
    @if($is_super)
      var editable = $('#group-edit-editable').val();
    @else 
      var editable = 1;
    @endif

    $.ajax({
      url: urlRoute.getBaseUrl() + 'admincp/group/edit',
      type: 'post',
      data: {title:title, opentag:opentag, height:height, width:width, groupid:groupid, editable:editable, immunity:immunity},
      success: function(data) {
        if(data['error'] == 1) {
          $('#group-edit-title').removeClass('form-reg-error');
          $('#group-edit-opentag').removeClass('form-reg-error');
          $('#group-edit-height').removeClass('form-reg-error');
          $('#group-edit-width').removeClass('form-reg-error');

          if(data['field'] != "all") {
            $('#'+data['field']).addClass('form-reg-error');
          }

          urlRoute.ohSnap(data['message'], 'red');
        } else {
          //WOOP!
          urlRoute.loadPage('/admincp/usergroups');
          urlRoute.ohSnap('Group ' + title + ' edited!', 'green');
        }
      }
    });
  }

  var destroy = function() {
    editGroup = null;
  }
</script>
