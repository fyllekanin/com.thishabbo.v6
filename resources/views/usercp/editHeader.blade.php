<script> urlRoute.setTitle("TH - Cover Photo");</script>

<div class="small-12 column">
    <div class="content-holder">
            <div class="content contentpadding">
                <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
                <span><a href="/usercp" class="bold web-page">UserCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
                Cover Photo
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
                <span>Edit Cover Photo</span>
            </div>
            <div class="content-ct">
                This image is your glamour. When anyone loads your profile, this will be the first thing they'll see.
                <br />
                <br />
                <div class="upload_avatar">
                    <input type="file" id="header_file" />
                    <br /><br /><br />
                    <button class="pg-red headerRed gradualfader fullWidth topBottom" style="float:right"  onclick="uploadCustom();">Upload</button>
                </div>
                <div class="progress-bar green stripes">
                    <span id="progress_bar_meter" style="width: 0%"></span>
                </div>
            </div>
        </div>
    </div>
    <div class="content-holder">
        <div class="content">
            <div class="contentHeader headerBlue">
                <span>Current Photo</span>
            </div>
            <br />
            <div class="content-ct">
                <div class="small-12 column" style="margin-bottom: 1rem;">
                    <img src="{{ $header }}" alt="example1" /> <br />
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
  var uploadCustom = function() {
    var formData = new FormData();
    formData.append('header', $('#header_file')[0].files[0]);
    formData.append('custom', "CUSTOM");
    $('.progress-bar').delay(60).fadeIn();
    $('.progress-bar').fadeIn("slow", function() {
      $('#progress_bar_meter').css("width", "30%");

      $.ajax({
        url: urlRoute.getBaseUrl() + 'usercp/post/header',
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

            urlRoute.loadPage('/usercp/header');
            urlRoute.ohSnap('Header updated!', 'green');
          }
          else {
            $('#progress_bar_meter').css("width", "0%");
            $('.progress-bar').delay(1000).fadeOut();
            urlRoute.ohSnap('Something went wrong with the upload!', 'red');
          }
        }
      });
    });
  }

  var destroy = function() {
    uploadCustom = null;
    staticImage = null;
  }
</script>
