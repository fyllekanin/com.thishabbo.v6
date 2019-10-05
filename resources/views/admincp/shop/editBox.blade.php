<script> urlRoute.setTitle("TH - Edit Box");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Edit Box - {{ $box->name }}</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-8 column">
                  <div class="contentHeader headerRed">
                    <span>Edit Box - {{ $box->name }}</span>
                    <a href="/admincp/list/boxes" class="web-page headerLink white_link">Back</a>
                  </div>
      <div class="content-holder">
        <div class="content">
      <div class="content-ct">
          <label for="sub-form-name">Name</label>
          <input type="text" id="box-form-name" value="{{ $box->name }}" class="login-form-input"/>
        </div>
          <label for="sub-form-price">Price <i style="font-size: 0.7rem;"></i></label>
          <input type="number" id="box-form-price" value="{{ $box->price }}" class="login-form-input"/>
          <label for="dup">One per user?</label>
          <select id="dup" class="login-form-input" name="">
              <option value="0">Yes</option>
              <option value="1">No</option>
          </select>
          <label for="box-form-description">Description</label>
          <textarea id="box-form-description"></textarea>
            <br><b>BoxImage</b> <br /><br />
            <img src="{{ asset('_assets/img/boxes/'.$box->boxid.'.gif') }}" />
            <br /><br />
            <div class="upload_avatar">
                <input type="file" id="box_file" />
            </div>
            <div class="progress-bar green stripes">
                <span id="progress_bar_meter" style="width: 0%"></span>
            </div>
        <br />
        <button class="pg-red headerRed gradualfader fullWidth topBottom" style="float:right" onclick="saveBox();">Save</button>
      </div>
    </div>
  </div>
</div>

<script>
    var saveBox = function() {
      var formData = new FormData();
      formData.append('boxid', {{ $box->boxid }});
      if ($('#box_file').get(0).files.length !== 0) {
        formData.append('box', $('#box_file')[0].files[0]);
      }
      formData.append('name', $('#box-form-name').val());
      formData.append('price', $('#box-form-price').val());
      formData.append('description', $('#box-form-description'));
      formData.append('duplicate',$('#dup'));

      $('.progress-bar').fadeIn("slow", function() {
        $('#progress_bar_meter').css("width", "30%");

        $.ajax({
          url: urlRoute.getBaseUrl() + 'admincp/box/update',
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

              urlRoute.ohSnap('Box updated!', 'green');
              urlRoute.loadPage('/admincp/list/boxes');
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
