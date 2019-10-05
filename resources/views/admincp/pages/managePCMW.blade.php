<?php $UserHelper = new \App\Helpers\UserHelper; ?>
<script> urlRoute.setTitle("TH - Photo Comp Monthly Winner");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span> 
      <span>Manage Photo Comp Monthly Winner</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-8 column">

  <div class="content-holder">
    <div class="content">
              <div class="contentHeader headerBlue">  
            <span>Previous Award</span>
          </div>
          {!! $pcuserid !!}<br />
          {{ $comment }}<br />
          <br />
          Submitted by {!! $userid !!}, {{ $dateline }}.
    </div>
  </div>


  <div class="content-holder">
    <div class="content">
 <div class="contentHeader headerRed">
      <span>Change Photo Comp Monthly Winner</span>
  </div>
          <label for="edit-form-username">PCMW Username</label>
          <input type="login-form-input" id="edit-form-username" placeholder="Username..." class="login-form-input" />
          <label for="edit-form-comment">Comment</label>
          <textarea id="edit-form-comment" class="login-form-input" style="height: 4rem;" maxlength="250">Photo Comp Winner for MONTH YEAR!</textarea>
          <br>
          <button class="pg-red headerRed gradualfader fullWidth topBottom" onclick="savePCMW();">Save</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  var savePCMW = function() {
    var username = $('#edit-form-username').val();
    var comment = $('#edit-form-comment').val();

    $.ajax({
      url: urlRoute.getBaseUrl() + 'admincp/site/photo/submit', 
      type: 'post',
      data: {username:username, comment:comment},
      success: function(data) {
        if(data['response'] == true) {
          urlRoute.loadPage('/admincp/site/photo');
          urlRoute.ohSnap('MOTM submitted!', 'green');
        } else {
          $('#'+data['field']).addClass('form-reg-error');
          urlRoute.ohSnap(data['message'], 'red');
        }
      }
    });
  }

  var destroy = function() {
    savePCMW = null;
  }
</script>
