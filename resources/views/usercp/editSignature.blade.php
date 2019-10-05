<?php $avatar = \App\Helpers\UserHelper::getAvatar(Auth::user()->userid); ?>
<script> urlRoute.setTitle("TH - Signature");</script>

<div class="small-12 column">
    <div class="content-holder">
            <div class="content contentpadding">
                <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
                <span><a href="/usercp" class="bold web-page">UserCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
                Signature
            </div>
    </div>
</div>

<div class="medium-4 column">
  @include('usercp.menu')
</div>
<div class="medium-8 column">
    <div class="content-holder">
        <div class="content">
        <div class="contentHeader headerRed">
                <span>Edit Signature</span>
            </div>
            <div class="content-ct">
                {!! $signature !!}
            </div>
        </div>
    </div>

    <div class="content-holder">
        <div class="content" style="overflow: visible;">
            <div class="content-ct">
                <textarea id="edit-user-signature" class="login-form-input" style="height: 4rem;">{!! $editsignature !!}</textarea><br>
                <button class="pg-red headerRed gradualfader fullWidth topBottom" style="float:right;" onclick="saveSignature();">Save</button>
                <br clear="all">
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    $(document).foundation();

    $("#edit-user-signature").wysibb();
  });

  var saveSignature = function() {
    var content = $('#edit-user-signature').bbcode();

    $.ajax({
      url: urlRoute.getBaseUrl() + 'usercp/save/signature',
      type: 'post',
      data: {content:content},
      success: function(data) {
        urlRoute.loadPage('/usercp/signature');
        urlRoute.ohSnap('Signature saved!', 'green');
      }
    });
  }

  var destroy = function() {
    saveSignature = null;
  }
</script>
