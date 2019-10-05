<script> urlRoute.setTitle("TH - Radio Connection Details");</script>
<?php $UserHelper = new \App\Helpers\UserHelper; ?>
<?php $ForumHelper = new \App\Helpers\ForumHelper; ?>



<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Radio Details</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('staff.menu')
</div>
<div class="medium-8 column">
  <div class="content-holder"><div class="content">
                <div class="contentHeader headerRed">
                Radio Details
              </div>
            <div class="content-ct">
                    <label for="article-form-title">IP</label>
                    <input type="text" id="radio-info-ip" value="{{ $ip }}" class="login-form-input"/>

                    <label for="article-form-title">Port</label>
                    <input type="number" id="radio-info-port" value="{{ $port }}" class="login-form-input"/>

                    <label for="article-form-title">Password</label>
                    <input type="text" id="radio-info-password" value="{{ $password }}" class="login-form-input"/>
                     @if($UserHelper::haveAdminPerm(Auth::user()->userid, 4294967296))
                    <label for="article-form-title">Admin Password</label>
                    <input type="text" id="radio-info-admin-password" value="{{ $admin_password }}" class="login-form-input"/>
                    @endif
                @if($UserHelper::haveAdminPerm(Auth::user()->userid, 4294967296))
                <br />
                <button class="pg-red fullWidth headerRed gradualfader shopbutton" onclick="changeRadioInfo();">Save</button>
                @endif
            </div>
        </div>
    </div>

    <div class="content-holder">
        <div class="content">

              <div class="contentHeader headerBlue">
                Latest Changes
              </div>
            <div class="content-ct">
                <table class="responsive" style="width: 100%;">
                    <tr>
                        <th>Time</th>
                        <th>User</th>
                        <th>IP</th>
                        <th>Port</th>
                        <th>Password</th>
                         @if($UserHelper::haveAdminPerm(Auth::user()->userid, 4294967296))
                        <th>Admin Password</th>
                        @endif
                    </tr>
                    @foreach($latest_changes as $change)
                        <tr>
                            <td>{{ $ForumHelper::timeAgo($change->dateline)}}</td>
                            <td>{!! $UserHelper::getUsername($change->userid) !!}</td>
                            <td>{{ $change->ip }}</td>
                            <td>{{ $change->port }}</td>
                            <td>{{ $change->password }}</td>
                             @if($UserHelper::haveAdminPerm(Auth::user()->userid, 4294967296))
                            <td>{{ $change->admin_password }}</td>
                            @endif
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>

@if($UserHelper::haveAdminPerm(Auth::user()->userid, 4294967296))
<script>
    var changeRadioInfo = function() {
        var ip = $('#radio-info-ip').val();
        var port = $('#radio-info-port').val();
        var password = $('#radio-info-password').val();
        var admin_password = $('#radio-info-admin-password').val();
        if(ip === '') {
            urlRoute.ohSnap('Can\'t leave PORT empty!', 'red');
            return;
        }
        if(port === '') {
            urlRoute.ohSnap('Can\'t leave IP empty!', 'red');
            return;
        }
        if(password === '') {
            urlRoute.ohSnap('Can\'t leave PASSWORD empty!', 'red');
            return;
        }
        if(admin_password === '') {
            urlRoute.ohSnap('Can\'t leave ADMIN PASSWORD empty!', 'red');
            return;
        }

        $.ajax({
          url: urlRoute.getBaseUrl() + 'staff/manage/radio/save',
          type: 'post',
          data: {ip:ip, port:port, password:password, admin_password:admin_password},
          success: function(data) {
            if(data['response'] === true) {
              urlRoute.loadPage('/staff/manage/radio');
              urlRoute.ohSnap('Radio info saved!', 'green');
            } else {
              urlRoute.ohSnap('Something went wrong!', 'red');
            }
          }
        });
    }

    var destroy = function() {
        changeRadioInfo = null;
    }
</script>
@endif
