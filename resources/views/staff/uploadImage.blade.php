<script> urlRoute.setTitle("TH - Image Upload");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/staff" class="bold web-page">StaffCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Image Upload</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('staff.menu')
</div>

<div class="medium-8 column">
  <div class="content-holder"><div class="content">
  <div class="contentHeader headerRed">
                Image Upload
              </div>
        <div class="content-ct">
                <div class="row">
                    <div class="small-12 medium-6 column">
                        <label for="graphic-form-tag">Tag <i style="font-size: 0.7rem;">(Add tags for the image, click "add" for each)</i></label>
                        <input type="text" id="graphic-form-tag" placeholder="Tag..." class="login-form-input"/><br />
                        <button class="pg-red headerRed gradualfader floatright fullWidth" onclick="addTag();">Add Tag</button>
                    </div>
                    <div class="small-12 medium-6 column">
                        <label for="graphic-form-tag">Added Tags <i style="font-size: 0.7rem;">(Click on tag to remove)</i></label>
                        <br />
                        <div id="tag_collection">

                        </div>
                    </div>
                    <div class="small-12 column">
                        <div class="" style="margin-top: 1rem;">
                            <b>New Graphic:</b> <br />
                            <div class="upload_avatar">
                                <input type="file" id="graphic_file" />
                                <br /><br /><br />
                                <button class="pg-red headerRed gradualfader floatright fullWidth" onclick="uploadImage();">Upload</button>
                            </div>
<br><Br>
                            <div class="progress-bar green stripes">
                                <span id="progress_bar_meter" style="width: 0%"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
  var addTag = function() {
    var tag = $('#graphic-form-tag').val();
    $('#graphic-form-tag').val("");

    if(tag !== "") {
      $('#tag_collection').append('<div class="profile_info_box_answer tag_added" style="float: left; margin-right: 0.5rem; margin-bottom: 0.5rem;" onclick="removeTag(this);">' + tag + '</div>');
    }
  }

  var removeTag = function(e) {
    $(e).fadeOut(function() {
      $(e).remove();
    });
  }

  var uploadImage = function() {
    var tags = [];

    $('.tag_added').each(function() {
      tags.push($(this).html());
    });

    var formData = new FormData();
    formData.append('image', $('#graphic_file')[0].files[0]);
    formData.append('tags', tags);

    $('.progress-bar').fadeIn("slow", function() {
      $('#progress_bar_meter').css("width", "30%");

      $.ajax({
        url: urlRoute.getBaseUrl() + 'staff/graphic/upload',
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

            urlRoute.loadPage('/staff/graphic/gallery/page/1');
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
    addTag = null;
    removeTag = null;
    uploadImage = null;
  }
</script>
