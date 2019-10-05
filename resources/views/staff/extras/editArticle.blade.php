<script> urlRoute.setTitle("TH - Edit Article: {{ $article->title }}");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/staff" class="bold web-page">StaffCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Edit Article: {{ $article->title }}</span>
    </div>
  </div>
</div>



<div class="medium-4 column">
  @include('staff.menu')
</div>
<div class="medium-8 column">
              <div class="contentHeader headerRed">
                Edit Article: {{ $article->title }}
              </div>
  <div class="content-holder"><div class="content">
        <div class="content-ct">
          <div class="medium-6 column">
            <label for="article-form-title">Title:</label>
            <input type="text" id="article-form-title" value="{{ $article->title }}" class="login-form-input"/>
          </div>
          <div class="medium-6 column">
            <label for="article-form-type">Type:</label>
            <select id="article-form-type" class="login-form-input">
              <option value="0" @if($article->type == 0) selected="" @endif >Quest Guide</option>
              <option value="1" @if($article->type == 1) selected="" @endif >News Article</option>
              <option value="2" @if($article->type == 2) selected="" @endif >Wired Guide</option>
              <option value="3" @if($article->type == 3) selected="" @endif >Tips & Tricks</option>
            </select>
          </div>
          <div class="medium-6 column">
            <label for="article-form-availability">Availability:</label>
            <select id="article-form-availability" class="login-form-input">
              <option value="0" @if($article->available == 0) selected="" @endif >Don't Show</option>
              <option value="1" @if($article->available == 1) selected="" @endif >Available</option>
              <option value="2" @if($article->available == 2) selected="" @endif >Not Available</option>
            </select>
          </div>
          <div class="medium-6 column">
            <label for="article-form-roomlink">Room Link <i>(Optional)</i>:</label>
            <input type="text" id="article-form-roomlink" value="{{ $article->room_link }}" class="login-form-input"/>
          </div>
          <div class="medium-6 column" id="difficulty-column">
              <label for="article-form-difficulty">Difficulty:</label>
              <select id="article-form-difficulty" class="login-form-input">
                  <option value="0" @if($article->difficulty == 0) selected="" @endif>Easy</option>
                  <option value="1" @if($article->difficulty == 1) selected="" @endif>Medium</option>
                  <option value="2" @if($article->difficulty == 2) selected="" @endif>Hard</option>
              </select>
          </div>
          <div class="medium-6 column" id="paid-column">
              <label for="article-form-paid">Paid/Unpaid:</label>
              <select id="article-form-paid" class="login-form-input">
                  <option value="0" @if($article->paid == 0) selected="" @endif>Unpaid</option>
                  <option value="1" @if($article->paid == 1) selected="" @endif>Paid</option>
              </select>
          </div>
          <div class="small-12 column"><br />
          <label for="article-form-availability">Content:</label>
            <textarea id="article_editor" style="height: 150px;">{!! $article->content !!}</textarea>
          </div>
          <div class="small-6 column" id="thumbnail_column">
            <label for="article-form-badge">Thumbnail (recommended size: 400x250):</label>
            <div class="upload_avatar">
              <input type="file" id="thumbnail" />
            </div>
          </div>
          <div class="small-6 column" id="badge_column" @if($article->type > 0) style="display: none;" @endif >
            <label for="article-form-badge">Badge <i>(Badge codes in comma separated list)</i:</label>
            <input type="text" id="article-form-badge" value="{{ $article->badge_code }}" class="login-form-input"/>
          </div>
          <div class="small-12 column">
            <div style="float: left;">
              <b>Thumbnail:</b><br />
              <div class="thumbnailImage" style="background-image: url(/_assets/img/thumbnails/{{ $article->articleid }}.gif);"></div>
            </div>
            @if($article->badge_code != '')
              <div style="float: left; padding-left: 1rem;">
                <b>Badge:</b><br /><br />
                <div class="badge-container hover-box-info" style="background: #3a3a3a; filter: grayscale(0%); -webkit-filter: grayscale(0%);">
                  <img src="https://habboo-a.akamaihd.net/c_images/album1584/{{ $article->badge_code }}.gif" alt="badge" />
                </div>
              </div>
            @endif
          </div></i>
          <br />
           <button class="pg-red headerRed gradualfader fullWidth topBottom" style="margin-top: 10px;" onclick="addNewArticle();">Save</button>
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
    formData.append('articleid', {{ $article->articleid }});
    formData.append('paid', $('#article-form-paid').val());
    formData.append('difficulty', $('#article-form-difficulty').val());

    $.ajax({
      url: urlRoute.getBaseUrl() + 'staff/media/article/edit',
      type: 'post',
      data: formData,
      processData: false,
      contentType: false,
      success: function(data) {
        if(data['response'] == true) {
          urlRoute.loadPage('/staff/media/articles/page/1');
          urlRoute.ohSnap('Article edited!', 'green');
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
