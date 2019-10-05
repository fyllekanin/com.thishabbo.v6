<script> urlRoute.setTitle("TH - New Article");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/staff" class="bold web-page">StaffCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>New Article</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('staff.menu')
</div>
<div class="medium-8 column">
  <div class="content-holder"><div class="content">
  <div class="contentHeader headerRed">
                New Rare Article
              </div>
            <div class="content-ct">
                <div class="medium-6 column">
                    <label for="article-form-title">Title:</label>
                    <input type="text" id="article-form-title" placeholder="Title..." class="login-form-input"/>
                </div>
                <div class="medium-6 column">
                    <label for="article-form-type">Type:</label>
                    <select id="article-form-type" class="login-form-input">
                        <option value="0" selected="">Quest Guide</option>
                        <option value="1">News Article</option>
                        <option value="2">Wired Guide</option>
                        <option value="3">Tips & Tricks</option>
                    </select>
                </div>
                <div class="medium-6 column">
                    <label for="article-form-availability">Availability:</label>
                    <select id="article-form-availability" class="login-form-input">
                        <option value="0">Don't Show</option>
                        <option value="1" selected="">Available</option>
                        <option value="2">Not Available</option>
                    </select>
                </div>
                <div class="medium-6 column">
                    <label for="article-form-roomlink">Room Link <i>(Optional)</i>:</label>
                    <input type="text" id="article-form-roomlink" placeholder="Room link..." class="login-form-input"/>
                </div>
                <div class="medium-6 column" id="difficulty-column">
                    <label for="article-form-difficulty">Difficulty:</label>
                    <select id="article-form-difficulty" class="login-form-input">
                        <option value="0" selected="">Easy</option>
                        <option value="1">Medium</option>
                        <option value="2">Hard</option>
                    </select>
                </div>
                <div class="medium-6 column" id="paid-column">
                    <label for="article-form-paid">Paid/Unpaid:</label>
                    <select id="article-form-paid" class="login-form-input">
                        <option value="0">Unpaid</option>
                        <option value="1" selected="">Paid</option>
                    </select>
                </div>
                <div class="small-12 column"><br />
                    <textarea id="article_editor" style="height: 150px;">[atitle]New Rare[/atitle]Navigate to the [b]Shop[/b] > [b]Furni[/b] > [b][CHANGE]![/b] Purchase the new rare for [b][CHANGE]c and [CHANGE]d[/b].

[center][img]https://i.imgur.com/zFEh8Dm.png[/img]

[i]What do you think of the new rare? Will you be buying it?
Let us know in the comments![/i][/center]</textarea>
                </div>
                <div class="small-6 column" id="thumbnail_column">
                    <label for="article-form-badge">Thumbnail (recommended size: 400x250):</label>
                    <div class="upload_avatar">
                        <input type="file" id="thumbnail" />
                    </div>
                </div>
                <div class="small-6 column" id="badge_column">
                    <label for="article-form-badge">Badge(s) <i>(Badge codes in comma separated list)</i>:</label>
                    <input type="text" id="article-form-badge" placeholder="Badge Code(s)" class="login-form-input"/>
                </div>
                <div class="small-12 column">
                    <button class="pg-red fullWidth headerRed gradualfader shopbutton" style="margin-top: 0.5rem;" onclick="addNewArticle();">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
  $(document).ready(function() {
    $(document).foundation();

    $("#article_editor").wysibb();
  });

  $('#article-form-type').change(function() {
    var val = $('#article-form-type').val();
    if(val == 0) {
      $('#badge_column').fadeIn();
      $('#paid_column').fadeIn();
      $('#difficulty_column').fadeIn();
    } else {
      $('#badge_column').fadeOut();
      $('#paid_column').fadeOut();
      $('#difficulty_column').fadeOut();
    }
  });

  var addNewArticle = function() {
    var formData = new FormData();
    formData.append('thumbnail', $('#thumbnail')[0].files[0]);
    formData.append('title', $('#article-form-title').val());
    formData.append('type', $('#article-form-type').val());
    formData.append('content', $('#article_editor').bbcode());
    formData.append('badge', $('#article-form-badge').val());
    formData.append('availability', $('#article-form-availability').val());
    formData.append('roomlink', $('#article-form-roomlink').val());
    formData.append('paid', $('#article-form-paid').val());
    formData.append('difficulty', $('#article-form-difficulty').val());

    $.ajax({
      url: urlRoute.getBaseUrl() + 'staff/media/article/add',
      type: 'post',
      data: formData,
      processData: false,
      contentType: false,
      success: function(data) {
        if(data['response'] == true) {
          urlRoute.loadPage('/staff/media/articles/page/1');
          urlRoute.ohSnap('Article added!', 'green');
        } else {
          $('#'+data['field']).addClass('form-reg-error');

          urlRoute.ohSnap(data['message'], 'red');
        }
      }
    });
  }

  var destroy = function() {
    addNewArticle = null;
  }
</script>
