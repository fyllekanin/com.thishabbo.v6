<script> urlRoute.setTitle("TH - New Theme");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>New Theme</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-8 column">
                  <div class="contentHeader headerRed">
                    <span>New Theme</span>
                    <a href="/admincp/list/themes" class="web-page headerLink white_link">Back</a>
                  </div>
  <div class="content-holder">
    <div class="content">
      <div class="content-ct">
          <label for="theme-form-name">Name</label>
          <input type="text" id="theme-form-name" placeholder="Name..." class="login-form-input"/>
          <label for="thcb">THClub Exclusive?</label>
          <select id="thcb" class="login-form-input" name="">
              <option value="1">Yes</option>
              <option value="0">No</option>
          </select>
          <label for="theme-form-price">Price <i style="font-size: 0.7rem;">(Amount of credits, one time payment)</i></label>
          <input type="number" id="theme-form-price" placeholder="20" class="login-form-input"/>
          <label for="theme-form-visible">Visibility <i style="font-size: 0.7rem;">(Amount of credits, one time payment)</i></label>
          <select id="theme-form-visible" class="login-form-input" >
            <option value="1">Visible</option>
            <option value="0">Hidden</option>
          </select>
          <label for="theme-form-description">Description <i style="font-size: 0.7rem;">(Cool description why to buy it?)</i></label>
          <textarea id="theme-form-description" class="login-form-input"></textarea>
          <br />

            <div class="progress-bar green stripes">
                <span id="progress_bar_meter" style="width: 0%"></span>
            </div>

             <b>Placeholder Image</b> <br />
            <div class="upload_avatar">
                <input type="file" id="theme_file" />
            </div>

            <label for="theme-form-css">CSS <i style="font-size: 0.7rem;">(Site will change live with changes in here)</i></label>
          <textarea id="theme-form-css" class="login-form-input" onkeyup="cssChange();"></textarea>

            <br>

          <button class="pg-red headerRed gradualfader fullWidth topBottom" style="float:right" onclick="addTheme();">Save</button>
      </div>
    </div>
  </div>
</div>

<script>
    var cssChange = function() {
      var css = $('#theme-form-css').val();

      if(!$('#temp-style-tag').length) {
        $('head').append('<style type="text/css" id="temp-style-tag"></style');
      }
      $('#temp-style-tag').html(css);
    }

    var addTheme = function() {
      var formData = new FormData();
      formData.append('theme', $('#theme_file')[0].files[0]);
      formData.append('name', $('#theme-form-name').val());
      formData.append('price', $('#theme-form-price').val());
      formData.append('description', $('#theme-form-description').val());
      formData.append('css', $('#theme-form-css').val());
      formData.append('thcb',$('#thcb').val());
      formData.append('visible',$('#theme-form-visible').val());

      $('.progress-bar').fadeIn("slow", function() {
        $('#progress_bar_meter').css("width", "30%");

        $.ajax({
          url: urlRoute.getBaseUrl() + 'admincp/theme/new',
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

              urlRoute.ohSnap('New theme added!', 'green');
              urlRoute.loadPage('/admincp/list/themes');
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
