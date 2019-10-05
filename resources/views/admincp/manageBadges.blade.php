<script> urlRoute.setTitle("TH - Manage Badges");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
          <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
           <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
          <span>Manage Badges</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-8 column">
              <div class="contentHeader headerRed">
                Manage Badges
              </div>
  <div class="content-holder">
  <div class="content">
        <div class="content-ct">
                  <label for="bbcode-form-name">Name</label>
                  <input type="text" id="badge-form-name" placeholder="Description..." class="login-form-input"/>
                  <label for="bbcode-form-name">Description</label>
                  <input type="text" id="badge-form-desc" placeholder="Description..." class="login-form-input"/>
                <div class="small-12 medium-6 column">
                  <label for="bbcode-form-name">Upload Badges</label>
                  <div class="upload_avatar">
                    <input type="file" id="badge_file" />
                  </div>
                  <div class="progress-bar green stripes">
                      <span id="progress_bar_meter" style="width: 0%"></span>
                  </div>
                </div>

<button class="pg-red headerRed gradualfader fullWidth topBottom" style="float:right" onclick="addNewBadge();">Upload</button>

              </div>
        </div>
    </div>
                  <div class="contentHeader headerRed">
                Badges
              </div>
  <div class="content-holder">
  <div class="content">

        <div class="content-ct">
              <table class="responsive" style="width: 100%;">
                <tr>
                  <th style="text-align: center;">Image</th>
                  <th>Name</th>
                  <th>Description</th>
                  <th style="text-align: center;">Manage Users</th>
                  <th style="text-align: center;">Admin</th>
                </tr>
                @foreach($badges as $badge)
                  <tr id="badge-{{ $badge['badgeid'] }}">
                    <td style="text-align: center;"><img src="{{ $badge['badge'] }}?{{ time() }}" alt="badge" width="36" height="36" /></td>
                    <td>{{ $badge['name'] }}</td>
                    <td>{{ $badge['description'] }}</td>
                    <td style="text-align: center;"><a href="/admincp/badge/manage/{{ $badge['badgeid'] }}" class="web-page"><i class="fa fa-user editcog4" aria-hidden="true"></i></a></td>
                    <td style="text-align: center;"><a href="/admincp/badge/edit/{{ $badge['badgeid'] }}" class="web-page"><i class="fa fa-pencil editcog4" aria-hidden="true"></i></a>
                      <i class="fa fa-trash editcog4" aria-hidden="true" onclick="removeBadge({{ $badge['badgeid'] }});"></i></td>
                  </tr>
                @endforeach
              </table>
            </div>
        </div>
    </div>

<div class="content-holder">
   <div class="content">
            {!! $pagi !!}
        </div>
    </div>
</div>

<script type="text/javascript">
  var removeBadge = function(badgeid) {
    if(confirm('Are you sure you want to remove this badge from all users?')) {
      $.ajax({
        url: urlRoute.getBaseUrl() + 'admincp/badge/remove',
        type: 'post',
        data: {badgeid:badgeid},
        success: function(data) {
          urlRoute.ohSnap('Badge removed!', 'green');
          urlRoute.loadPage('admincp/badges/manage/page/1');
        }
      });
    }
  }

  var addNewBadge = function() {
    var formData = new FormData();
    formData.append('badge_file', $('#badge_file')[0].files[0]);
    formData.append('name', $('#badge-form-name').val());
    formData.append('description', $('#badge-form-desc').val());

    $('.progress-bar').fadeIn("slow", function() {
      $('#progress_bar_meter').css("width", "30%");
      $.ajax({
        url: urlRoute.getBaseUrl() + 'admincp/badge/add',
        type: 'post',
        data: formData,
        processData: false,
        contentType: false,
        success: function(data) {
          if(data['response'] == true) {
            urlRoute.ohSnap('Badge added!', 'green');
            urlRoute.loadPage('admincp/badges/manage/page/1');
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

  var destroy = function() {
    removeBadge = null;
    addNewBadge = null;
  }
</script>
