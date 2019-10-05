
    <div class="notif-box" id="notification-{{ $notificationid }}" style="position: relative;">
    <i class="fa fa-times notif-link" aria-hidden="true" style="position: absolute; top: 0; right: 0; margin: 7px 15px 0 0; z-index: 1000;"></i>
    <a href="{{ $link }}" class="web-page notif-link">
    <div class="notif-content">
        <div class="notif-avatar">
              <div class="notif-show" style="background-image: url('{{ $avatar }}'); background-repeat: no-repeat; background-size: 100%;"></div>
        </div>
        <div class="notif-text">
             {!! $message !!}<br />
             <i class="timeNotif"><i class="fa fa-tag" aria-hidden="true"></i> {{ $time }}</i></p>
        </div>

    </div>

    </a>
    </div>
