<script> urlRoute.setTitle("TH - Edit Sticker");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Edit Sticker - {{ $sticker->name }}</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-8 column">
                  <div class="contentHeader headerRed">
                    <span>Edit Sticker - {{ $sticker->name }}</span>
                    <a href="/admincp/list/stickers" class="web-page headerLink white_link">Back</a>
                  </div>
      <div class="content-holder">
      <div class="content">
      <div class="content-ct">
          <label for="sub-form-name">Name</label>
          <input type="text" id="sticker-form-name" value="{{ $sticker->name }}" class="login-form-input"/>
        </div>

        <label for="thcb">THClub Exclusive?</label>
        <select id="thcb" class="login-form-input" name="">
            <option value="1">Yes</option>
            <option value="0">No</option>
        </select>
          <label for="sub-form-price">Price <i style="font-size: 0.7rem;"></i></label>
          <input type="number" id="sticker-form-price" value="{{ $sticker->price }}" class="login-form-input"/>
          <label for="sub-form-price">Quantity of Item <i style="font-size: 0.7rem;">(-1 = unlimited)</i></label>
          <input type="number" id="sticker-form-limit" value="{{ $sticker->limit }}" class="login-form-input"/>
          <label for="sub-form-description">Description <i style="font-size: 0.7rem;">(Make it selling!)</i></label>
          <textarea id="sticker-form-description" class="login-form-input">{{ $sticker->description }}</textarea>
          <br />
            <b>Sticker Image</b><br /><br />
            <img src="{{ asset('_assets/img/stickers/'.$sticker->stickerid.'.gif') }}" />
            <br><br />
            <div class="upload_avatar">
                <input type="file" id="sticker_file" />
            </div>
            <div class="progress-bar green stripes">
                <span id="progress_bar_meter" style="width: 0%"></span>
            </div>
<br />
        <button class="pg-red headerRed gradualfader fullWidth topBottom" style="float:right" onclick="saveSticker();">Save</button>
      </div>
      </div>
    </div>
  </div>
</div>

<script>
    var saveSticker = function() {
      var formData = new FormData();
      formData.append('stickerid', {{ $sticker->stickerid }});
      if ($('#sticker_file').get(0).files.length !== 0) {
        formData.append('sticker', $('#sticker_file')[0].files[0]);
      }
      formData.append('name', $('#sticker-form-name').val());
      formData.append('price', $('#sticker-form-price').val());
      formData.append('description', $('#sticker-form-description').val());
      formData.append('limit', $('#sticker-form-limit').val());
      formData.append('thcb', $('#thcb').val());

      $('.progress-bar').fadeIn("slow", function() {
        $('#progress_bar_meter').css("width", "30%");

        $.ajax({
          url: urlRoute.getBaseUrl() + 'admincp/sticker/update',
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

              urlRoute.ohSnap('Sticker updated!', 'green');
              urlRoute.loadPage('/admincp/list/stickers');
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
