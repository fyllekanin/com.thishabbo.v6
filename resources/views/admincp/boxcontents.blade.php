<?php $ForumHelper = new \App\Helpers\ForumHelper; ?>
<script> urlRoute.setTitle("TH - Add to Box");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Manage Mystery Box</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-8 column">


      <div class="content-holder">
        <div class="content">
          <div class="content-ct">
          <fieldset>
              <label for="sub-form-name">Select Box</label>
              <select class="login-form-input" id="box" onchange="updateContents()">
                  @foreach($boxes as $box)
                    <option value="{{ $box['id'] }}" @if(isset($_GET['id']) && $_GET['id'] == $box['id']) selected="" @endif>{{ $box['name'] }}</option>
                  @endforeach
              </select>
              <label for="sub-form-name">Select Shop Item</label>
              <select class="login-form-input" id="typeitem">
                  <optgroup label="Themes">
                      @foreach($themes as $theme)
                        <option value="1,{{ $theme['themeid'] }}">{{ $theme['name'] }}</option>
                      @endforeach
                  </optgroup>
                  <optgroup label="Icons">
                      @foreach($icons as $icon)
                        <option value="2,{{ $icon['iconid'] }}">{{ $icon['name'] }}</option>
                      @endforeach
                  </optgroup>
                  <optgroup label="Effects">
                      @foreach($effects as $effect)
                        <option value="3,{{ $effect['effectid'] }}">{{ $effect['name'] }}</option>
                      @endforeach
                  </optgroup>
                  <optgroup label="Subscriptions">
                      @foreach($subs as $sub)
                        <option value="4,{{ $sub['packageid'] }}">{{ $sub['name'] }}</option>
                      @endforeach
                  </optgroup>
                  <optgroup label="Stickers">
                      @foreach($stickers as $sticker)
                        <option value="5,{{ $sticker['stickerid'] }}">{{ $sticker['name'] }}</option>
                      @endforeach
                  </optgroup>
              </select>
              <label for="sub-form-name">Weighting</label>
              <div class="small-10 columns">
                <div class="slider" data-start="0" data-end="100" data-slider>
                  <span class="slider-handle"  data-slider-handle role="slider" tabindex="1" aria-controls="weight"></span>
                  <span class="slider-fill" data-slider-fill></span>
                </div>
              </div>
              <div class="small-2 columns">
                <input min="0" max="10" class="login-form-input" type="number" id="weight">
            </div><br>
          </fieldset>
          </div>
            <button type="button" class="pg-red headerRed gradualfader fullWidth topBottom" onclick="addToBox()">Add</button>
      </div>
  </div>
</div>
</div>

<div class="medium-8 column">
            <div class="contentHeader headerPurple">
                    Box Contents
              </div>
    <div class="content-holder">
  <div class="content">
        <div class="content-ct">
        <div class="small-12">
          <table class="responsive" style="width: 100%;">
            <tr>
                          <th>Type</th>
                          <th>Item</th>
                          <th>Weight</th>
                          <th>Edit</th>
                      </tr>
                  </thead>
                  @foreach($boxes as $box)
                  <tbody id="table{{ $box['id'] }}" style="display:none;">
                      @foreach($box['items'] as $item)
                      <tr id="{{ $item['id'] }}">
                          <td>{{ $item['type'] }}</td>
                          <td>{{ $item['item'] }}</td>
                          <td>{{ $item['weight'] }}</td>
                          <td><i class="fa fa-trash" aria-hidden="true" onclick="deleteFromBox({{ $item['id'] }})"></i></td>
                      </tr>
                      @endforeach
                  </tbody>
                  @endforeach
              </table>
              </div>
          </div>
  </div>
</div>

<script type="text/javascript">

var current = $('#table0');
var updateContents = function() {
    var newBox = $('#box').val();
    current.toggle();
    current = $('#table'+newBox);
    current.toggle();
}

$(document).ready(function (){
    var newBox = $('#box').val();
    current.toggle();
    current = $('#table'+newBox);
    current.toggle();
});

var addBox = function() {
  var formData = new FormData();
  formData.append('box', $('#box_file')[0].files[0]);
  formData.append('name', $('#box-form-name').val());
  formData.append('price', $('#box-form-price').val());
  formData.append('description', $('#box-form-description').val());

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

          urlRoute.ohSnap('New box added!', 'green');
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
  var deleteFromBox = function (contentid){
      $.ajax({
          url: urlRoute.getBaseUrl() + 'admincp/box/delete',
          type: 'post',
          data: {contentid:contentid},
          success: function(data) {
              if(data['response'] == true) {
                  urlRoute.ohSnap("Deleted!",'green');
                  $('#'+contentid).remove();
              } else {
                  urlRoute.ohSnap(data['message'],'red');
              }
          }
      });
  }
  var addToBox = function () {
      var box = $('#box').val();
      var typeitem = $('#typeitem').val().split(",");
      var type = typeitem[0];
      var item = typeitem[1];
      var weight = $('#weight').val();

      $.ajax({
          url: urlRoute.getBaseUrl() + 'admincp/box/add',
          type: 'post',
          data: {box:box,type:type,item:item,weight:weight},
          success: function(data) {
              if(data['response'] == true) {
                  urlRoute.ohSnap("Success!",'green');
                  urlRoute.loadPage('/admincp/box?id='+box);

              } else {
                  urlRoute.ohSnap(data['message'],'red');
              }
          }
      });
  }





  var destroy = function() {
    saveAdminPerms = null;
  }
</script>
