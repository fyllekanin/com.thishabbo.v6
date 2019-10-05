<?php $ForumHelper = new \App\Helpers\ForumHelper; ?>
<script> urlRoute.setTitle("TH - Prefixes");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Manage Prefixes</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-8 column">
                  <div class="contentHeader headerGreen">
                    <span>Manage Prefixes</span>
                  </div>

      <div class="content-holder">
        <div class="content">
          <div class="content-ct">
            <label for="badge-form-desc">Text</label>
            <input type="text" id="prefix-form-text" placeholder="Text..." class="login-form-input"/>
            <label for="badge-form-desc">Style</label>
            <input type="text" id="prefix-form-style" placeholder="font-weight: bold; color: blue;" class="login-form-input"/>
            <label for="prefix-form-forum">Select Forum</label>
            <select id="prefix-form-forum" class="login-form-input">
              <option value="0" selected="">All forums</option>
              @foreach($forums as $forumx)
                <option value="{{ $forumx['forumid'] }}">{{ $forumx['title'] }}</option>
                @if(count($forumx['childs']))
                  <?php $childs = $ForumHelper::getChildsSelect(0, $forumx['childs']);?>
                  {!! $childs !!}
                @endif
              @endforeach
            </select>
            <br>

          <button class="pg-red headerGreen gradualfader fullWidth topBottom" onclick="addPrefix();">Add Prefix</button>

      </div>
    </div>
  </div>
</div>
<div class="medium-8 column">
        <div class="contentHeader headerPurple">
          <span>Manage Prefixes</span>
        </div>
<div class="content-holder">
      <div class="content">
        <div class="content-ct">
        <div class="small-12">
          <table class="responsive" style="width: 100%;">
            <tr>
              <th>Prefix</th>
              <th>Forum</th>
              <th>Edit</th>
            </tr>
            @foreach($prefixes as $prefix)
              <tr>
                <td style="{!! $prefix['style'] !!}">{{ $prefix['text'] }}</td>
                <td>{{ $prefix['forum'] }}</td>
                <td><a href="/admincp/prefixes/edit/{{ $prefix['prefixid'] }}" class="web-page"><i class="fa fa-pencil editcog4" aria-hidden="true"></i></a> <i class="fa fa-trash" aria-hidden="true" onclick="removePrefix({{ $prefix['prefixid'] }});"></i></td>
              </tr>
            @endforeach
          </table>
      </div>
      </div>
  </div>
</div>

<script type="text/javascript">
  var addPrefix = function() {
    var text = $('#prefix-form-text').val();
    var style = $('#prefix-form-style').val();
    var forumid = $('#prefix-form-forum').val();

    $.ajax({
      url: urlRoute.getBaseUrl() + 'admincp/prefix/add',
      type: 'post',
      data: {text:text, style:style, forumid:forumid},
      success: function(data) {
        urlRoute.ohSnap('Prefix added!', 'green');
        urlRoute.loadPage('/admincp/prefixes');
      }
    })
  }

  var removePrefix = function(prefixid) {
    $.ajax({
      url: urlRoute.getBaseUrl() + 'admincp/prefix/remove',
      type: 'post',
      data: {prefixid:prefixid},
      success: function(data) {
        urlRoute.ohSnap('Prefix removed!', 'green');
        urlRoute.loadPage('/admincp/prefixes');
      }
    })
  }

  var destroy = function() {
    addPrefix = null;
    removePrefix = null;
  }
</script>
