<?php $ForumHelper = new \App\Helpers\ForumHelper; ?>
<script> urlRoute.setTitle("TH - Moderation");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Add New Moderation Forum</span>
    </div>
  </div>
</div>
<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-8 column">
  <div class="contentHeader headerGreen">
    <span>Add New Moderation Forum</span>
  </div>
  <div class="content-holder">
    <div class="content">
      <div class="content-ct">
        <i>What are moderation forums? It basically controls where all the user report threads are being stored. Below you will see some codes you can use to personalise the report, if for any reason you should.
        <br />
        <br />
        <li><b>{date}</b> <i>Will be replaced with the current day like "01/01/2012"</i></li>
        <li><b>{thread}</b> <i>Will be replaced with the thread's name</i></li>
        <li><b>{reporter}</b> <i>Will be replaced with the name of the person that sends the report</i></li>
        <li><b>{reported}</b> <i>Name of the person that gets reported</i></li>
        <li><b>{type}</b> <i>Will be replaced with post/visitor message, what type is being reported.</i></li>
        <br />
        <br />
        <b>Example:</b> {reporter} reported {reported}'s {type} at {date}. <br>
        This will be: irDez reported Dan's post at 01/01/2012 </i>
        <hr />
        <label for="mod-form-title">Title</label>
          <input type="text" id="mod-form-title" placeholder="Title..." class="login-form-input"/>
          <label for="mod-form-forum">Forum</label>
          <select id="mod-form-forum" class="login-form-input">
            @foreach($forums as $forum)
              <option value="{{ $forum['forumid'] }}">{{ $forum['title'] }}</option>
            @endforeach
          </select>
          <label for="mod-form-forum">Prefixes</label>
          <select id="mod-form-prefix" class="login-form-input">
            @foreach($prefixes as $prefix)
              <option value="{{ $prefix->prefixid }}">{{ $prefix->text }}</option>
            @endforeach
          </select>

          <br>

        <button class="pg-red headerGreen gradualfader fullWidth topBottom" onclick="addModForum();">Add</button>

      </div>
    </div>
  </div>
  <div class="contentHeader headerPurple">
    <span>Moderation Forum List</span>
  </div>
<div class="content">
  <div class="content">
    <div class="content-ct">
      <div class="small-12">
        <table class="responsive" style="width: 100%;">
          <tr>
            <th>Title</th>
            <th>Forum</th>
            <th>Prefix</th>
            <th>Remove</th>
          </tr>
          @foreach($mfs as $mf)
            <tr id="mf-{{ $mf['mfid'] }}">
              <td>{{ $mf['title'] }}</td>
              <td>{{ $mf['forum'] }}</td>
              <td>{!! $mf['prefix'] !!}</td>
              <td onclick="removeModForum({{ $mf['mfid'] }});">Remove</td>
            </tr>
          @endforeach
        </table>
      </div>
    </div>
  </div>
</div>
</div>

<script type="text/javascript">

  var removeModForum = function(mfid) {
    $.ajax({
      url: urlRoute.getBaseUrl() + 'admincp/settings/modforum/remove',
      type: 'post',
      data: {mfid:mfid},
      success: function(data) {
        $('#mf-'+mfid).fadeOut();
      }
    });
  }

  var addModForum = function() {
    var title = $('#mod-form-title').val();
    var forum = $('#mod-form-forum').val();
    var prefixid = $('#mod-form-prefix').val();

    $.ajax({
      url: urlRoute.getBaseUrl() + 'admincp/settings/modforum/add',
      type: 'post',
      data: {title:title, forum:forum, prefixid:prefixid},
      success: function(data) {
        if(data['response'] == true) {
          urlRoute.loadPage('/admincp/settings/modforum');
          urlRoute.ohSnap('Moderation Forum Added!', 'greed');
        } else {
          urlRoute.ohSnap(data['message'], 'red');
        }
      }
    });
  }

  var destroy = function() {
    removeModForum = null;
    addModForum = null;
  }
</script>
