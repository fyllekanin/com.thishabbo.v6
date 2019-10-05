<script> urlRoute.setTitle("TH - BBCode Edit");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
          <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
           <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
          <span>Edit BBCode: {{ $bbcode->name }}</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-8 column">
<div class="content-holder">
<div class="content">
      <div class="contentHeader headerRed">
        <a href="/admincp/settings/bbcodes" class="web-page headerLink white_link">Back</a>
        <span>Edit BBCode - {{ $bbcode->name }}</span>
      </div>
      <div class="content-ct">
          <label for="bbcode-form-name">Name</label>
          <input type="text" id="bbcode-form-name" value="{{ $bbcode->name }}" class="login-form-input"/>
          <label for="bbcode-form-example">Example</label>
          <input type="text" id="bbcode-form-example" value="{{ $bbcode->example }}" class="login-form-input"/>
          <label for="bbcode-form-pattern">Pattern</label>
          <input type="text" id="bbcode-form-pattern" value="{{ $bbcode->pattern }}" class="login-form-input"/>
          <label for="bbcode-form-replace">Replace</label>
          <input type="text" id="bbcode-form-replace" value="{{ $bbcode->replace }}" class="login-form-input"/>
          <label for="bbcode-form-content">Content</label>
          <input type="text" id="bbcode-form-content" value="{{ $bbcode->content }}" class="login-form-input"/>
          <label for="bbcode-form-hidden">Is this BBCode Public?</label>
          <select id="bbcode-form-hidden" class="login-form-input">
            <option value="1" @if($bbcode->staff_specific == 1) selected="" @endif>Hidden</option>
            <option value="0" @if($bbcode->staff_specific == 0) selected="" @endif>Visible</option>
          </select>
            <br/>

          <button onclick="saveBBcode();" class="pg-red headerRed gradualfader fullWidth topBottom" style="float:right;">Save</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  var saveBBcode = function() {
    var name = $('#bbcode-form-name').val();
    var example = $('#bbcode-form-example').val();
    var pattern = $('#bbcode-form-pattern').val();
    var replace = $('#bbcode-form-replace').val();
    var content = $('#bbcode-form-content').val();
    var hidden = $('#bbcode-form-hidden').val();
    var bbcodeid = {{ $bbcode->bbcodeid }};

    $.ajax({
      url: urlRoute.getBaseUrl() + 'admincp/settings/edit/bbcode',
      type: 'post',
      data: {bbcodeid:bbcodeid, name:name, example:example, pattern:pattern, replace:replace, content:content, hidden:hidden},
      success: function(data) {
        if(data['response'] == true) {
          urlRoute.loadPage("/admincp/settings/bbcodes");
          urlRoute.ohSnap('BBcode edited!', 'green');
        } else {
          $('#'+data['field']).addClass('form-reg-error');
          urlRoute.ohSnap(data['message'], 'red');
        }
      }
    });
  }

  var destroy = function() {
    saveBBcode = null;
  }
</script>
