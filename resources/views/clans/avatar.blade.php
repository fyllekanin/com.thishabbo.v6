<script> urlRoute.setTitle("TH - Clan Avatar");</script>

<div class="small-12 column">
    <div class="content-holder">
            <div class="content contentpadding">
                <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
                <span><a href="/clans" class="bold web-page">Clans</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
                <span><a href="/clans/{{ $clan->groupname }}" class="bold web-page">Clan: {{ $clan->groupname }}</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
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
                        <img src="/_assets/img/clanAvatars/{{ $clan->avatar }}.gif" alt="Your avatar" class="my_avatar2 marginBottom"/>

                    </div>
                </div>
                <div class="small-12 medium-6 column">
                    <div class="content-info-box">
                      <b>Quick Information</b> <br />
 -                        - Maximum avatar width: 200 <br />
 -                        - Maximum avatar height: 200 <br />
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
    formData.append('clanid', {{ $clan->groupid }});

    $('.progress-bar').fadeIn("slow", function() {
      $('#progress_bar_meter').css("width", "30%");

      $.ajax({
        url: urlRoute.getBaseUrl() + 'clans/update/avatar',
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

            urlRoute.loadPage('clans/{{ $clan->groupname }}/edit/avatar');
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
