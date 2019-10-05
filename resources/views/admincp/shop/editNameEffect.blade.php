<script> urlRoute.setTitle("TH - Edit Name Effect");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Edit Name Effect - {{ $effect->name }}</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-8 column">
                  <div class="contentHeader headerRed">
                    <span>Edit Name Effect - {{ $effect->name }}</span>
                    <a href="/admincp/list/nameeffects" class="web-page headerLink white_link">Back</a>
                  </div>
      <div class="content-holder">
        <div class="content">
      <div class="content-ct">
          <label for="sub-form-name">Name</label>
          <input type="text" id="effect-form-name" value="{{ $effect->name }}" class="login-form-input"/>
        </div>

        <label for="thcb">THClub Exclusive?</label>
        <select id="thcb" class="login-form-input" name="">
            <option value="1">Yes</option>
            <option value="0">No</option>
        </select>
          <label for="sub-form-price">Price <i style="font-size: 0.7rem;"></i></label>
          <input type="number" id="effect-form-price" value="{{ $effect->price }}" class="login-form-input"/>
          <label for="sub-form-price">Limit <i style="font-size: 0.7rem;">(-1 = unlimited)</i></label>
          <input type="number" id="effect-form-limit" value="{{ $effect->limit }}" class="login-form-input"/>
          <label for="sub-form-description">Description <i style="font-size: 0.7rem;">(Make it selling!)</i></label>
          <textarea id="effect-form-description" class="login-form-input">{{ $effect->description }}</textarea>
          <br>
            <b>Effect Image</b> <br /><br />
            <img src="{{ asset('_assets/img/nameeffects/'.$effect->effectid.'.gif') }}" />
            <br /><br />
            <div class="upload_avatar">
                <input type="file" id="effect_file" />
            </div>
            <div class="progress-bar green stripes">
                <span id="progress_bar_meter" style="width: 0%"></span>
            </div>
        <br />
        <button class="pg-red headerRed gradualfader fullWidth topBottom" style="float:right" onclick="saveEffect();">Save</button>
      </div>
    </div>
  </div>
</div>

<script>
    var saveEffect = function() {
      var formData = new FormData();
      formData.append('effectid', {{ $effect->effectid }});
      if ($('#effect_file').get(0).files.length !== 0) {
        formData.append('effect', $('#effect_file')[0].files[0]);
      }
      formData.append('name', $('#effect-form-name').val());
      formData.append('price', $('#effect-form-price').val());
      formData.append('description', $('#effect-form-description').val());
      formData.append('limit', $('#effect-form-limit').val());
      formData.append('thcb',$('#thcb').val());

      $('.progress-bar').fadeIn("slow", function() {
        $('#progress_bar_meter').css("width", "30%");

        $.ajax({
          url: urlRoute.getBaseUrl() + 'admincp/nameeffect/update',
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

              urlRoute.ohSnap('Effect updated!', 'green');
              urlRoute.loadPage('/admincp/list/nameeffects');
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
