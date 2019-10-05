<script> urlRoute.setTitle("TH - Edit Background");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Edit Background - {{ $background->name }}</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-8 column">
                  <div class="contentHeader headerRed">
                    <span>Edit Background - {{ $background->name }}</span>
                    <a href="/admincp/list/backgrounds" class="web-page headerLink white_link">Back</a>
                  </div>
      <div class="content-holder">
        <div class="content">
      <div class="content-ct">
          <label for="sub-form-name">Name</label>
          <input type="text" id="background-form-name" value="{{ $background->name }}" class="login-form-input"/>
        </div>

        <label for="thcb">THClub Exclusive?</label>
        <select id="thcb" class="login-form-input" name="">
            <option value="1">Yes</option>
            <option value="0">No</option>
        </select>
          <label for="sub-form-price">Price <i style="font-size: 0.7rem;"></i></label>
          <input type="number" id="background-form-price" value="{{ $background->price }}" class="login-form-input"/>
          <label for="sub-form-price">Limit <i style="font-size: 0.7rem;">(-1 = unlimited)</i></label>
          <input type="number" id="background-form-limit" value="{{ $background->limit }}" class="login-form-input"/>
          <label for="sub-form-description">Description <i style="font-size: 0.7rem;">(Make it selling!)</i></label>
          <textarea id="background-form-description" class="login-form-input">{{ $background->description }}</textarea>
            <br><b>Background Image</b> <br /><br />
            <img src="{{ asset('_assets/img/backgrounds/'.$background->backgroundid.'.gif') }}" />
            <br /><br />
            <div class="upload_avatar">
                <input type="file" id="background_file" />
            </div>
            <div class="progress-bar green stripes">
                <span id="progress_bar_meter" style="width: 0%"></span>
            </div>
        <br />
        <button class="pg-red headerRed gradualfader fullWidth topBottom" style="float:right" onclick="saveBackground();">Save</button>
      </div>
    </div>
  </div>
</div>

<script>
    var saveBackground = function() {
      var formData = new FormData();
      formData.append('backgroundid', {{ $background->backgroundid }});
      if ($('#background_file').get(0).files.length !== 0) {
        formData.append('background', $('#background_file')[0].files[0]);
      }
      formData.append('name', $('#background-form-name').val());
      formData.append('price', $('#background-form-price').val());
      formData.append('description', $('#background-form-description').val());
      formData.append('limit', $('#background-form-limit').val());
      formData.append('thcb', $('#thcb').val());

      $('.progress-bar').fadeIn("slow", function() {
        $('#progress_bar_meter').css("width", "30%");

        $.ajax({
          url: urlRoute.getBaseUrl() + 'admincp/background/update',
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

              urlRoute.ohSnap('Background updated!', 'green');
              urlRoute.loadPage('/admincp/list/backgrounds');
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
</script>
