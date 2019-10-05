<script> urlRoute.setTitle("TH - Upload Creation");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/creations/page/1" class="bold web-page">Creations</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Upload Creation</span>
    </div>
  </div>
</div>

<div class="small-12 column">
        <div class="contentHeader headerRed">
          <span>Upload Creation</span> <a onclick="uploadCreation();" class="headerLink white_link">Upload Creation</a>
        </div>
  <div class="content-holder">
      <div class="content">
        <div class="content-ct">
          <div class="row">
            <div class="small-12 column">
              <div class="small-12 medium-6 column">
                <label for="creation-form-tag">Tag <i style="font-size: 0.7rem;">(Add tags for the image, click "add" for each)</i></label>
                <input type="text" id="creation-form-tag" placeholder="Tag..." class="login-form-input"/><br>
                <button class="pg-red headerRed floatright gradualfader" onclick="addTag();">Add Tag</button>
              </div>
              <div class="small-12 medium-6 column">
                <label for="creation-form-name">Name <i style="font-size: 0.7rem;">(Name for the creation)</i></label>
                <input type="text" id="creation-form-name" placeholder="Name..." class="login-form-input"/>
              </div>
            </div>
            <div class="small-12 column" style="margin-top: 1.5rem;">
              <div class="small-12 medium-6 column">
                <label for="creation-form-tag">Added Tags <i style="font-size: 0.7rem;">(Click on tag to remove)</i></label>
                <br />
                <div id="tag_collection">

                </div>
              </div>
              <div class="small-12 medium-6 column">
                <b>Choose creation to upload</b> <br />
                <input type="file" id="creation_file" />
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
    var tag = $('#creation-form-tag').val();
    $('#creation-form-tag').val("");

    if(tag !== "") {
      $('#tag_collection').append('<div class="profile_info_box_answer tag_added" style="float: left; margin-right: 0.5rem; margin-bottom: 0.5rem;" onclick="removeTag(this);">' + tag + '</div>');
    }
  }

  var removeTag = function(e) {
    $(e).fadeOut(function() {
      $(e).remove();
    });
  }

  var uploadCreation = function() {
    var tags = [];

    $('.tag_added').each(function() {
      tags.push($(this).html());
    });

    var formData = new FormData();
    formData.append('creation', $('#creation_file')[0].files[0]);
    formData.append('tags', tags);
    formData.append('name', $('#creation-form-name').val());

    $('.progress-bar').fadeIn("slow", function() {
      $('#progress_bar_meter').css("width", "30%");

      $.ajax({
        url: urlRoute.getBaseUrl() + 'creation/upload',
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

            urlRoute.ohSnap(data['message'], 'green');
            urlRoute.loadPage('/creations/page/1');
          }
          else {
            urlRoute.ohSnap(data['message'], 'red');
            $('#progress_bar_meter').css("width", "0%");
            $('.progress-bar').delay(1000).fadeOut();
          }
        },
        error: function(data) {
          urlRoute.ohSnap('Something went wrong!', 'red');
          $('#progress_bar_meter').delay(1000).css("width", "0%");
          $('.progress-bar').delay(1000).fadeOut();
        }
      });
    });
  }

  var destroy = function() {
      addTag = null;
      removeTag = null;
      uploadCreation = null;
    }
</script>
