<?php $ForumHelper = new \App\Helpers\ForumHelper; ?>
<script> urlRoute.setTitle("TH - Carousel");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Manage Carousel</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-8 column">
    <div class="contentHeader headerGreen"><span>Carousel</span></div>
      <div class="content-holder">
        <div class="content">
                <label for="sub-form-name">Text</label>
                <input type="text" id="text" placeholder="Text..." class="login-form-input"/>


                <label for="sub-form-name">Link</label>
                <input type="text" id="link" placeholder="Link..." class="login-form-input"/>

<br/>

                <b>Image</b>
                <br>
                <div class="upload_avatar">
                <input type="file" id="carousel_file" />



            <div class="progress-bar green stripes">
                <span id="progress_bar_meter" style="width: 0%"></span>
            </div>


        <button class="pg-red headerGreen gradualfader fullWidth topBottom" style="float:right" onclick="addAdvert();">Save</button>

        </div>
      </div>
    </div>
  <div class="content-holder">
  <div class="content">
        <div class="content-ct">
        <div class="small-12">
          <table class="responsive" style="width: 100%;">
            <tr>
              <th>ID</th>
              <th class="widthFull">Advert</th>
              <th>Text</th>
              <th>Link</th>
              <th>Delete</th>
            </tr>
            @foreach($carousel as $advert)
              <tr>
                <td>{{ $advert['id'] }}</td>
                <td><img src="{{ $advert['image'] }}" /></td>
                <td>{{ $advert['text'] }}</td>
                <td>{{ $advert['link'] }}</td>
                <td><a onclick="deleteAd({{ $advert['id'] }});"><i class="fa fa-trash editcog4" aria-hidden="true"></i></a></td>
              </tr>
            @endforeach
          </table>
      </div>
      </div>
    </div>
  </div>


          <div class="contentHeader headerGreen">
            <span>Set Images</span>
          </div>
          <div class="content-holder">
            <div class="content">
              <div class="content-ct">
                <label for="select-ad-1">Advert 1:</label>
                <select class="login-form-input" id="select-ad-1">
                  @foreach($carousel as $advert)
                    <option value="{{ $advert['id']}}" @if($advert['adverts'] == 1) selected @endif>{{ $advert['id'] }}</option>
                  @endforeach
                </select>


                  <label for="select-ad-2">Advert 1:</label>
                  <select class="login-form-input" id="select-ad-2">
                    @foreach($carousel as $advert)
                      <option value="{{ $advert['id'] }}" @if($advert['adverts'] == 2) selected @endif>{{ $advert['id'] }}</option>
                    @endforeach
                  </select>

                  <button class="pg-red headerGreen gradualfader fullWidth topBottom" style="float:right" onclick="saveAds();">Save</button>


              </div>
            </div>
          </div>
</div>

<script type="text/javascript">

var saveAds = function() {
  var formData = new FormData();
  formData.append('ad1', $('#select-ad-1').val());
  formData.append('ad2', $('#select-ad-2').val());

  $.ajax({
    url: urlRoute.getBaseUrl() + 'admincp/carousel/ads',
    type: 'post',
    data: formData,
    processData: false,
    contentType: false,
    success: function(data) {
      if(data['response'] == true){
        urlRoute.ohSnap('Adverts saved!','green');
      } else {
        urlRoute.ohSnap('Error! '+data['message'],'red');
      }
    }
  });
}

var addAdvert = function() {
  var formData = new FormData();
  formData.append('carousel', $('#carousel_file')[0].files[0]);
  formData.append('text', $('#text').val());
  formData.append('link', $('#link').val());

  $('.progress-bar').fadeIn("slow", function() {
    $('#progress_bar_meter').css("width", "30%");

    $.ajax({
      url: urlRoute.getBaseUrl() + 'admincp/carousel/add',
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

          urlRoute.ohSnap('New carousel added!', 'green');
          urlRoute.loadPage('/admincp/carousel');
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
  var deleteAd = function(carouselid) {
        if(confirm('Are you sure you wanna delete this advert?')) {
          $.ajax({
            url: urlRoute.getBaseUrl() + 'admincp/carousel/remove',
            type: 'post',
            data: {carouselid:carouselid},
            success: function(data) {
              urlRoute.loadPage('/admincp/carousel');
              urlRoute.ohSnap('Removed!', 'green');
            }
          });
        }
    }

  var destroy = function() {
    subscriptionAction = null;
  }
</script>
