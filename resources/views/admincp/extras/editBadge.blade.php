<script> urlRoute.setTitle("TH - Edit Badge");</script>
<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
          <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
           <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
          <span>Edit Badges</span>
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
                Edit Badge
                <a href="/admincp/badges/manage/page/1" class="web-page headerLink white_link">Back</a>
              </div>
        <div class="content-ct">
                <label for="badge-form-name">Name</label>
                <input type="text" id="badge-form-name" value="{{ $name }}" class="login-form-input"/>
              <br />
                <label for="badge-form-desc">Description</label>
                <input type="text" id="badge-form-desc" value="{{ $description }}" class="login-form-input"/>
              <br />
                <b>Upload badge image</b> <br />
                <div class="upload_avatar">
                  <input type="file" id="badge_file" />
                </div>
                <div class="progress-bar green stripes">
                    <span id="progress_bar_meter" style="width: 0%"></span>
                </div>
                <img src="{{ asset('_assets/img/website/badges/' . $badgeid . '.gif') }}" />
              <br>
              <button class="pg-red headerRed gradualfader fullWidth topBottom topHelp" onclick="saveBadge();">Save</button>
            </div>
      </div>
  </div>
</div>

<script type="text/javascript">
  var saveBadge = function() {
    var formData = new FormData();
    formData.append('badge_file', $('#badge_file')[0].files[0]);
    formData.append('name', $('#badge-form-name').val());
    formData.append('description', $('#badge-form-desc').val());

    formData.append('badgeid', {{ $badgeid }});
    $('.progress-bar').fadeIn("slow", function() {
      $('#progress_bar_meter').css("width", "30%");
      $.ajax({
        url: urlRoute.getBaseUrl() + 'admincp/badge/edit',
        type: 'post',
        data: formData,
        processData: false,
        contentType: false,
        success: function(data) {
          if(data['response'] == true) {
            urlRoute.ohSnap('Badge edited!', 'green');
            urlRoute.loadPage('admincp/badges/manage/page/1');
          }
          else {
            urlRoute.ohSnap(data['message'], 'red');
            $('#progress_bar_meter').css("width", "0%");
            $('.progress-bar').delay(1000).fadeOut();
          }
        }
      });
    });
  }

  var destroy = function() {
    saveBadge = null;
  }
</script>
