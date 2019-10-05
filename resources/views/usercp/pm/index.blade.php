<script> urlRoute.setTitle("TH - Private Messages");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/usercp" class="bold web-page">UserCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Private Messages</span>
    </div>
  </div>
</div>

<div class="small-4 column mobileFunction">
  <div class="content-holder">
    <div class="content">
    <div class="contentHeader headerBlue">
            Conversations
        </div>
        <div class="content-ct">
            @foreach($conversations as $conversation)
            @if($conversation['username'] == '__blank__')
            @else
              <a href="/usercp/pm?userid={{ $conversation['userid'] }}" class="web-page">
                <div class="row pm-row">
                    <div class="small-1 column">
                        <div class="profile-avatar-pm" style="background-image: url({{ $conversation['avatar'] }});"></div>
                    </div>
                    <div class="userPM">
                        <div>
                          <b>{!! $conversation['username'] !!}</b>
                          <span style="float: right;">
                            {{ $conversation['dateline'] }}
                            @if(!$conversation['read'])
                              <i class="fa fa-info-circle new-message-{{ $conversation['userid'] }}" aria-hidden="true"></i>
                            @endif
                          </span>
                        </div>
                        <div class="pm-part-content">{!! $conversation['content'] !!}</div>
                    </div>
                </div>
              </a>
            @endif
            @endforeach
        </div>
    </div>
  </div>
</div>

<div class="medium-8 column">
@if(isset($_GET['userid']))
  @include('usercp.pm.converstation')
@else
      <div class="content-holder">
        <div class="content contentpadding">
        <div class="contentHeader headerRed">
                  Private Messages
            </div>
               Please click a conversation on the left! If you have sent a message, it won't appear until the other party replies.
        </div>
     </div>
  </div>
@endif
</div>
