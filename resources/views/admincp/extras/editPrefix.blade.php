<?php $ForumHelper = new \App\Helpers\ForumHelper; ?>
<script> urlRoute.setTitle("TH - Edit Prefix");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Edit Prefix</span>
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
                    <span>Edit Prefixes</span>
                  </div>
          <div class="content-ct">
            <label for="prefix-form-text">Text</label>
            <input type="text" id="prefix-form-text" value="{{ $text }}" class="login-form-input"/>
            <label for="prefix-form-style">Style</label>
            <input type="text" id="prefix-form-style" value="{{ $style }}" class="login-form-input"/>
            <label for="prefix-form-forum">Select Forum</label>
            <select id="prefix-form-forum" class="login-form-input">
              <option value="0" @if($prefix->forumid === "0") selected="" @endif>All forums</option>
              @foreach($forums as $forumx)
                <option @if($prefix->forumid === $forumx['forumid']) selected="" @endif value="{{ $forumx['forumid'] }}">{{ $forumx['title'] }}</option>
                @if(count($forumx['childs']))
                  <?php $childs = $ForumHelper::getChildsSelect(0, $forumx['childs']);?>
                  {!! $childs !!}
                @endif
              @endforeach
            </select>
            <br>
          <button class="pg-red headerRed gradualfader fullWidth topBottom" onclick="editPrefix({{ $prefix->prefixid }});">Edit Prefix</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  var editPrefix = function(prefixid) {
    var text = $('#prefix-form-text').val();
    var style = $('#prefix-form-style').val();
    var forumid = $('#prefix-form-forum').val();

    $.ajax({
      url: urlRoute.getBaseUrl() + 'admincp/prefix/update',
      type: 'post',
      data: {prefixid:prefixid, text:text, style:style, forumid:forumid},
      success: function(data) {
        urlRoute.ohSnap('Prefix Edited!', 'green');
        urlRoute.loadPage('/admincp/prefixes');
      }
    })
  }

  var destroy = function() {
    editPrefix = null;
  }
</script>
