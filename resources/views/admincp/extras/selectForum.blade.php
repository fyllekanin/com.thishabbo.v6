<?php $ForumHelper = new \App\Helpers\ForumHelper; ?>
<script> urlRoute.setTitle("TH - Select Forum");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>    
      <span>Permissions: {{ $group->title }}</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-8 column">
      <div class="content-holder">
        <div class="content">
                  <div class="contentHeader headerRed">
                    <span>Permissions: {{ $group->title }}</span>
                    <a href="/admincp/usergroups" class="web-page headerLink white_link">Back</a> 
                  </div>
        <div class="medium-12 column">
          <label for="bbcode-form-name">Select Forum</label>
          <select id="forum-select" class="login-form-input">
            @foreach($forums as $forumx) 
              <option value="{{ $forumx['forumid'] }}">{{ $forumx['title'] }}</option>
              @if(count($forumx['childs']))
                <?php $childs = $ForumHelper::getChildsSelect(0, $forumx['childs']);?>
                {!! $childs !!}
              @endif
            @endforeach
          </select>

<br>

          <button onclick="goOnNext();" class="pg-red headerRed gradualfader fullWidth topBottom">Next</button>
    </div>
  </div>
</div>

<script type="text/javascript">
  var goOnNext = function() {
    var groupid = {{ $group->usergroupid }};
    var forumid = $('#forum-select').val();

    @if($forumpermissions) 
      urlRoute.loadPage('/admincp/usergroup/' + groupid + '/edit/forumpermissions/'+forumid);
    @else
      urlRoute.loadPage('/admincp/usergroup/' + groupid + '/edit/moderationpermissions/'+forumid);
    @endif
  }
  var destroy = function() {
    goOnNext = null;
  }
</script>
