<script> urlRoute.setTitle("TH - New Box");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>New Box</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-8 column">
                  <div class="contentHeader headerRed">
                    <span>New Box</span>
                    <a href="/admincp/list/boxes" class="web-page headerLink white_link">Back</a>
                  </div>
  <div class="content-holder">
    <div class="content">
      <div class="content-ct">
          <label for="sub-form-name">Name</label>
          <input type="text" id="box-form-name" placeholder="Name..." class="login-form-input"/>
          <label for="dup">One per user?</label>
          <select id="dup" class="login-form-input" name="">
              <option value="0">Yes</option>
              <option value="1">No</option>
          </select>
          <label for="box-form-description">Description</label>
          <textarea id="box-form-description"></textarea>
          <label for="sub-form-price">Price <i style="font-size: 0.7rem;">(Amount of credits, one time payment)</i></label>
          <input type="number" id="box-form-price" placeholder="20" class="login-form-input"/>
          <label for="sub-form-price">Amount in THS <i style="font-size: 0.7rem;">(-1 = unlimited)</i></label>
          <input type="number" id="box-form-limit" placeholder="-1" class="login-form-input"/>
          <label for="sub-form-description">Description <i style="font-size: 0.7rem;">(Cool description why to buy it?)</i></label>
          <textarea id="box-form-description" class="login-form-input"></textarea>
        <br>
            <b>Box Image</b>
            <br>
            <div class="upload_avatar">
                <input type="file" id="box_file" />
            </div>


            <div class="progress-bar green stripes">
                <span id="progress_bar_meter" style="width: 0%"></span>
            </div>


        <button class="pg-red headerRed gradualfader fullWidth topBottom" style="float:right" onclick="addBox();">Save</button>
      </div>
    </div>
  </div>
</div>

<script>
    var addBox = function() {
      var formData = new FormData();
      formData.append('box', $('#box_file')[0].files[0]);
      formData.append('name', $('#box-form-name').val());
      formData.append('price', $('#box-form-price').val());
      formData.append('description', $('#box-form-description'));
      formData.append('duplicate',$('#dup'));

      $('.progress-bar').fadeIn("slow", function() {
        $('#progress_bar_meter').css("width", "30%");

        $.ajax({
          url: urlRoute.getBaseUrl() + 'admincp/box/new',
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

              urlRoute.ohSnap('New box added!', 'green');
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
