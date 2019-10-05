<?php $ForumHelper = new \App\Helpers\ForumHelper; ?>
<?php $UserHelper = new \App\Helpers\UserHelper; ?>

<script> urlRoute.setTitle("TH - Forum Settings");</script>

<div class="small-12 column">
    <div class="content-holder">
            <div class="content contentpadding">
                <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
                <span><a href="/usercp" class="bold web-page">UserCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
                Forum Settings
            </div>
    </div>
</div>

<div class="medium-4 column">
    @include('usercp.menu')
</div>

<div class="medium-8 column">
    @include('usercp.forumSettings.menu')
    @include('usercp.forumSettings.notifications')
    @include('usercp.forumSettings.otherSettings')
    @include('usercp.forumSettings.subscribedThreads')
    @include('usercp.forumSettings.xpLevels')
</div>

<script type="text/javascript">
    $("#editnotification").click(function() {
        $('html, body').animate({
            scrollTop: $("#notification").offset().top - 60
        }, 1000);
    });
    $("#othersettings").click(function() {
        $('html, body').animate({
            scrollTop: $("#other").offset().top - 60
        }, 1000);
    });
    $("#editsubscribed").click(function() {
        $('html, body').animate({
            scrollTop: $("#subscribed").offset().top - 60
        }, 1000);
    });
    $("#seepostlevels").click(function() {
        $('html, body').animate({
            scrollTop: $("#postlevels").offset().top - 60
        }, 1000);
    });

    var saveExtras = function() {
        var extras = 0;
        $('.extra_activated').each(function(){
            if($(this).is(':checked')) {
                extras += parseInt($(this).val());
            }
        });

        $.ajax({
            url: urlRoute.getBaseUrl() + 'usercp/save/extras',
            type: 'post',
            data: {extras:extras},
            success: function(data) {
                if(data['response'] === "true") {
                    urlRoute.ohSnap('Extras saved!','green');
                } else {
                    urlRoute.ohSnap('Something went wrong', 'red');
                }
            }
        })
    }

    var unsubscribe = function(threadid) {
        $.ajax({
            url: urlRoute.getBaseUrl() + 'usercp/thread/unsubscribe',
            type: 'post',
            data: {threadid:threadid},
            success: function(data) {
                $('#threadid-'+threadid).fadeOut();
                urlRoute.ohSnap('You have unsubscribed to this thread!', 'blue');
            }
        });
    }
    
    var unsubscribeall = function(threadid) {
        $.ajax({
            url: urlRoute.getBaseUrl() + 'usercp/thread/unsubscribeall',
            type: 'post',
            success: function(data) {
                $('#threadid-'+threadid).fadeOut();
                urlRoute.ohSnap('You have unsubscribed from all threads!', 'blue');
            }
        });
    }

    @if(Auth::user()->auto_subscribe == 1)
    var turnOffAutomatic = function() {
        $.ajax({
            url: urlRoute.getBaseUrl() + 'usercp/automatic/off',
            type: 'get',
            success: function(data) {
                urlRoute.loadPage('/usercp/settings/forum');
                urlRoute.ohSnap('Automatic Subscrtion Off!', 'blue');
            }
        });
    }
    @else
    var turnOnAutomatic = function() {
        $.ajax({
            url: urlRoute.getBaseUrl() + 'usercp/automatic/on',
            type: 'get',
            success: function(data) {
                urlRoute.loadPage('/usercp/settings/forum');
                urlRoute.ohSnap('Automatic Subscrtion On!', 'blue');
            }
        });
    }
    @endif

    var destroy = function() {
        saveExtras = null;
        unsubscribe = null;
        turnOffAutomatic = null;
        turnOnAutomatic = null;
    }
</script>
