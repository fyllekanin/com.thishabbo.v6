<script> urlRoute.setTitle("TH - New Background");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>New Background</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-8 column">
                  <div class="contentHeader headerRed">
                    <span>New Background</span>
                    <a href="/admincp/list/backgrounds" class="web-page headerLink white_link">Back</a>
                  </div>
  <div class="content-holder">
    <div class="content">
      <div class="content-ct">
          <label for="sub-form-name">Name</label>
          <input type="text" id="background-form-name" placeholder="Name..." class="login-form-input"/>
          <label for="thcb">THClub Exclusive?</label>
          <select id="thcb" class="login-form-input" name="">
              <option value="1">Yes</option>
              <option value="0">No</option>
          </select>
          <label for="sub-form-price">Price <i style="font-size: 0.7rem;">(Amount of credits, one time payment)</i></label>
          <input type="number" id="background-form-price" placeholder="20" class="login-form-input"/>
          <label for="sub-form-price">Amount in THS <i style="font-size: 0.7rem;">(-1 = unlimited)</i></label>
          <input type="number" id="background-form-limit" placeholder="-1" class="login-form-input"/>
          <label for="sub-form-description">Description <i style="font-size: 0.7rem;">(Cool description why to buy it?)</i></label>
          <textarea id="background-form-description" class="login-form-input"></textarea>
        <br>
            <b>Background Image</b>
            <br>
            <div class="upload_avatar">
                <input type="file" id="background_file" />
            </div>


            <div class="progress-bar green stripes">
                <span id="progress_bar_meter" style="width: 0%"></span>
            </div>


        <button class="pg-red headerRed gradualfader fullWidth topBottom" style="float:right" onclick="addBackground();">Save</button>
      </div>
    </div>
  </div>
</div>

<script>
    var addBackground = function() {
      var formData = new FormData();
      formData.append('background', $('#background_file')[0].files[0]);
      formData.append('name', $('#background-form-name').val());
      formData.append('price', $('#background-form-price').val());
      formData.append('description', $('#background-form-description').val());
      formData.append('limit', $('#background-form-limit').val());
      formData.append('thcb', $('#thcb').val());

      $('.progress-bar').fadeIn("slow", function() {
        $('#progress_bar_meter').css("width", "30%");

        $.ajax({
          url: urlRoute.getBaseUrl() + 'admincp/background/new',
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

              urlRoute.ohSnap('New background added!', 'green');
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
