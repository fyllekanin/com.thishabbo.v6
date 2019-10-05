<script> urlRoute.setTitle("TH - Edit Theme");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Edit Theme: {{ $theme->name }}</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-8 column">
                  <div class="contentHeader headerRed">
                    <span>Edit Theme: {{ $theme->name }}</span>
                    <a href="/admincp/list/themes" class="web-page headerLink white_link">Back</a>
                  </div>
      <div class="content-holder">
        <div class="content">
      <div class="content-ct">
          <label for="theme-form-name">Name</label>
          <input type="text" id="theme-form-name" value="{{ $theme->name }}" class="login-form-input"/>
        </div>

        <label for="thcb">THClub Exclusive?</label>
        <select id="thcb" class="login-form-input" name="">
            <option value="1">Yes</option>
            <option value="0">No</option>
        </select>
          <label for="theme-form-price">Price <i style="font-size: 0.7rem;">(Amount of credits, one time payment)</i></label>
          <input type="number" id="theme-form-price" value="{{ $theme->price }}" class="login-form-input"/>
          <label for="theme-form-visible">Visibility <i style="font-size: 0.7rem;">(Amount of credits, one time payment)</i></label>
          <select id="theme-form-visible" class="login-form-input">
            <option value="1" @if($theme->visible == 1) selected="" @endif>Visible</option>
            <option value="0" @if($theme->visible == 0) selected="" @endif>Hidden</option>
          </select>
          <label for="theme-form-description">Description <i style="font-size: 0.7rem;">(Cool description why to buy it?)</i></label>
          <textarea id="theme-form-description" class="login-form-input">{{ $theme->description }}</textarea>
                    <br>
            <b>Placeholder Image</b> <br />
            <div class="upload_avatar">
                <input type="file" id="theme_file" />
            </div>

            <div class="progress-bar green stripes">
                <span id="progress_bar_meter" style="width: 0%"></span>
            </div>
            <img src="{{ asset('_assets/img/themes/' . $theme->themeid . '.gif') }}" />
          <label for="theme-form-css">CSS <i style="font-size: 0.7rem;">(Site will change live with changes in here)</i></label>
          <textarea id="theme-form-css" class="login-form-input" onkeyup="cssChange();">{{ $theme->style }}</textarea>
                    <br>
          <button class="pg-red headerRed gradualfader fullWidth topBottom" style="float:right" onclick="editTheme();">Save</button>
      </div>
    </div>
  </div>
</div>

<script>
    $(document).ready(function() {
      var cssChange = function() {
        var css = $('#theme-form-css').val();

        if(!$('#temp-style-tag').length) {
          $('head').append('<style type="text/css" id="temp-style-tag"></style');
        }
        $('#temp-style-tag').html(css);
      }
      cssChange();
    });

    var editTheme = function() {
        var formData = new FormData();
        formData.append('theme', $('#theme_file')[0].files[0]);
        formData.append('name', $('#theme-form-name').val());
        formData.append('price', $('#theme-form-price').val());
        formData.append('description', $('#theme-form-description').val());
        formData.append('css', $('#theme-form-css').val());
        formData.append('themeid', {{ $theme->themeid }});
        formData.append('thcb', $('#thcb').val());
        formData.append('visible',$('#theme-form-visible').val());

        $('.progress-bar').fadeIn("slow", function() {
          $('#progress_bar_meter').css("width", "30%");

          $.ajax({
            url: urlRoute.getBaseUrl() + 'admincp/theme/edit',
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

                urlRoute.ohSnap('Theme edited!', 'green');
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
