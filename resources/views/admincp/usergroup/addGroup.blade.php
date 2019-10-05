<?php $ForumHelper = new \App\Helpers\ForumHelper; ?>
<script> urlRoute.setTitle("TH - New Usergroup");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>    
      <span>Add Usergroup</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-8 column">
                  <div class="contentHeader headerRed">
                    <span>Add Usergroup</span>
                  </div>
      <div class="content-holder">
        <div class="content">
      <div class="content-ct">
            <b>Tip</b> <br />
            When the group is created, please go to "List Usergroups" and find the drop down list of all available options for that newly made usergroup. Standard procedure.

          <label for="group-add-title">Title</label>
          <input type="text" id="group-add-title" placeholder="Group Title..." class="login-form-input"/>
          <label for="group-add-opentag">Name Color Hex Code<i style="font-size: 0.7rem;"></i></label>
          <input type="text" id="group-add-opentag" placeholder="font-weight: bold; color: black;" class="login-form-input"/>
          <label for="group-add-height">Avatar Height</label>
          <input type="number" id="group-add-height" placeholder="300..." class="login-form-input"/>
          <label for="group-add-width">Avatar Width <i style="font-size: 0.7rem;">(Always 200)</i></label>
          <input type="number" id="group-add-width" placeholder="200..." class="login-form-input"/>
          <label for="group-add-immunity">Immunity <i style="font-size: 0.7rem;">(0 to {{ $immunity }})</i></label>
          <input type="number" id="group-add-immunity" placeholder="1..." class="login-form-input" max="100" min="1"/>
          <label for="group-add-immunity">Copy usergroup <i style="font-size: 0.7rem;">(Usergroup to copy from, this will copy permissions only and userbar)</i></label>
          <select id="group-add-copy" class="login-form-input">
            <option value="0">Don't copy</option>
            @foreach($usergroups as $usergroup)
              <option value="{{ $usergroup['groupid'] }}">{{ $usergroup['title'] }}</option>
            @endforeach
          </select>
        @if($is_super)
            <label for="group-add-editable">Group editable? <i style="font-size: 0.7rem;">If no, ONLY super admins can control and edit this in the future.</i></label>
            <select id="group-add-editable" class="login-form-input">
              <option value="1" selected="">Yes</option>            
              <option value="0">No</option>
            </select>
        @endif
        </div>

          <br>

        <button class="pg-red headerRed gradualfader fullWidth topBottom" style="float:right" onclick="addGroup();">Save</button>

        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  var addGroup = function() {
    var title = $('#group-add-title').val();
    var opentag = $('#group-add-opentag').val();
    var height = $('#group-add-height').val();
    var width = $('#group-add-width').val();
    var immunity = $('#group-add-immunity').val();
    var copy = $('#group-add-copy').val();
    @if($is_super)
      var editable = $('#group-add-editable').val();
    @else 
      var editable = 1;
    @endif
    $.ajax({
      url: urlRoute.getBaseUrl() + 'admincp/group/add',
      type: 'post',
      data: {title:title, opentag:opentag, height:height, width:width, editable:editable, immunity:immunity, copy:copy},
      success: function(data) {
        if(data['error'] == 1) {
          $('#group-add-title').removeClass('form-reg-error');
          $('#group-add-opentag').removeClass('form-reg-error');
          $('#group-add-height').removeClass('form-reg-error');
          $('#group-add-width').removeClass('form-reg-error');

          if(data['field'] != "all") {
            $('#'+data['field']).addClass('form-reg-error');
          }

          urlRoute.ohSnap(data['message'], 'red');
        } else {
          //WOOP!
          urlRoute.ohSnap('Group ' + title + ' added!', 'green');
          urlRoute.loadPage('/admincp/usergroups');
        }
      }
    });
  }

  var destroy = function() {
    addGroup = null;
  }
</script>
