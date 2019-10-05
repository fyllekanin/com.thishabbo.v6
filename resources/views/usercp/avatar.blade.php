<?php $avatar = \App\Helpers\UserHelper::getAvatar(Auth::user()->userid); ?>
<script> urlRoute.setTitle("TH - Avatar");</script>

<div class="small-12 column">
    <div class="content-holder">
            <div class="content contentpadding">
                <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
                <span><a href="/usercp" class="bold web-page">UserCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
                Edit Avatar
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
                <span>Edit Avatar</span>
            </div>
            <div class="content-ct">
                <div class="small-12 medium-6 column"> 
                    <div class="ct-center">
                        <img src="{{ $avatar }}" alt="Your avatar" class="my_avatar2 marginBottom"/>

                    </div>
                </div>
                <div class="small-12 medium-6 column">
                    <div class="content-info-box">
                      <b>Quick Information</b> <br />
 -                        - Maximum avatar width: {{ $max_width }} <br />
 -                        - Maximum avatar height: {{ $max_height }} <br />
 -                        - <strong>We'll always take the highest dimensions your usergroup allows!</strong>
 -                    </div>
 -                    <div class="content-info-box">
                        <b>Upload new avatar</b> <br />
                        <div class="upload_avatar">
                            <input type="file" id="avatar_file" />
                        </div>

                        <div class="progress-bar green stripes">
                            <span id="progress_bar_meter" style="width: 0%"></span>
                        </div>
                    </div>
                </div>
            </div>
            <button class="pg-red headerRed gradualfader fullWidth topBottom" onclick="uploadAvatar();">Upload</button>
        </div>
    </div>
</div>

<script type="text/javascript">
  var uploadAvatar = function() {
    var formData = new FormData();
    formData.append('avatar', $('#avatar_file')[0].files[0]);

    $('.progress-bar').fadeIn("slow", function() {
      $('#progress_bar_meter').css("width", "30%");

      $.ajax({
        url: urlRoute.getBaseUrl() + 'usercp/update/avatar',
        type: 'post',
        data: formData,
        processData: false,
        contentType: false,
        success: function(data) {
          if(data['response'] == true) {
            $('#progress_bar_meter').css("width", "100%");
            $('.progress-bar').delay(1000).fadeOut("slow", function() {
              $('#progress_bar_meter').css("width", "0%");
            });

            $('.my_avatar').attr("style", "background-image: url(" + data['new_avatar'] + ");");
            $('.my_avatar2').attr("src", data['new_avatar']);
          }
          else {
            urlRoute.ohSnap('You din\'t choose a image', 'red');
            $('#progress_bar_meter').css("width", "0%");
            $('.progress-bar').delay(1000).fadeOut();
          }
        }
      });
    });
  }

  var destroy = function() {
    uploadAvatar = null;
  }
</script>
