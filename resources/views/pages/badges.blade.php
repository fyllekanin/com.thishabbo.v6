<script> urlRoute.setTitle("TH - Scanned Badges");</script>

<?php $last_online = \App\Helpers\ForumHelper::getOnlineUsers(); ?>
<?php $badges = \App\Helpers\ForumHelper::getLatestBages(144); ?>
<?php $questsHelper = new \App\Helpers\QuestsHelper ; ?>

<div class="reveal" id="badge_info" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal></div>

<div class="medium-8 column">
    <div class="content-holder">
        <div class="content">
        <div class="contentHeader headerRed">
        <span>Scanned Badges</span>
    </div>
            <div class="content-ct">
                <div id="list_all_badges">
                    @foreach($badges as $badge)
                        <div class="small-3 medium-2 large-1 column">
                            <div class="badge-container hover-box-info" title="<b>{{ $badge['name'] }}:</b> <i>{{ $badge['desc'] }}</i>">
                                <img id="{{ $badge['name'] }}" onerror="badgeError(this);" src="https://habboo-a.akamaihd.net/c_images/album1584/{{ $badge['name'] }}.gif" alt="badge" onclick="badgeInfo('{{ $badge['name'] }}', '{{ addslashes($badge['desc']) }}' , '{{ $questsHelper::getQuest($badge['name']) }}', '{{ $questsHelper::isSubscribed($badge['name']) }}' )"/>
                                @if($badge['new'])<div class="badge-new-badge" style="padding-left: 0.2rem;">New</div>@endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<div class="small-4 mobileFunction column">
    <div class="content-holder">
        <div class="content">
        <div class="contentHeader headerBlue">
        <span>Search Badges</span><a onclick="executeSearch()" class="headerLink white_link web-page">Find Badge</a>
    </div>
            <div class="content-ct ct-center">
                <input type="text" id="criteria" name="criteria" placeholder="e.g. FAN or Fansite" class="login-form-input"/>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var executeSearch = function(){
        var criteria = $('#criteria').val();
        if(criteria === ""){
        urlRoute.ohSnap('Enter criteria or user!','red');
        } else {
            var path = encodeURI('badges/'+criteria+'');
            urlRoute.loadPage(path);
        }
    };

    var badgeError = function(image) {
        image.onerror = "";
        image.src = "{{ asset('_assets/img/website/badge_error.gif') }}";
        return true;
    }

    var badgeInfo = function (badgeid,badgedesc,guide,subscribed) {
        $('#badge_info').html("<div class='small-1 column'><img onerror='badgeError(this)' src='https://habboo-a.akamaihd.net/c_images/album1584/" + badgeid + ".gif' /></div>");
        $('#badge_info').append("<h6 class='small-11 column text-right'>"+badgeid+" - "+badgedesc+"</h6>");
        if(guide !== '-1'){
            $('#badge_info').append("<div class='right'><a class='bold' id='guide-link'>Find out how to get this badge &raquo;</a></div>");
            $('#guide-link').click(function () {
            urlRoute.loadPage('article/' + guide);
            });
            $('#badge_info').foundation('open');
        } else {
            @if(Auth::check())
                if(subscribed) {
                    $('#badge_info').append("<div class='small-11 column text-right right'><a class='bold' id='unsubscribe-link'>You're subscribed to this badge. Click to unsubscribe &raquo;</a></div>");
                } else {
                    $('#badge_info').append("<div class='small-11 column text-right right'><a class='bold' id='subscribe-link'>Subscribe to this badge to be notified when we write a guide for it &raquo;</a></div>");
                }
            @else
                $('#badge_info').append("<div class='small-11 column text-right right'><a class='bold web-page' id='sign-up-link'>Sign up/log in to subscribe to this badge &raquo;</a></div>");
            @endif
                $('#guide-link').click(function (){
                    $('#badge_info').foundation('close');
                    urlRoute.loadPage('article/'+guide)
                });
                $('#subscribe-link').click(function (){
                    $.ajax({
                        url: urlRoute.getBaseUrl() + 'badges/subscribe',
                        type: 'POST',
                        data: {badgeid:badgeid},
                        success: function(data) {
                            urlRoute.ohSnap("Subscribed!", 'green');
                            $('#'+badgeid).attr("onclick","badgeInfo('"+badgeid+"','"+badgedesc+"','"+guide+"','1')");
                        },
                        error: function(data){
                            urlRoute.ohSnap("Something went wrong!", 'red');
                        },
                        complete: function(data){
                            $('#badge_info').foundation('close');
                        }
                    });
                });
            $('#unsubscribe-link').click(function (){
                $.ajax({
                    url: urlRoute.getBaseUrl() + 'badges/unsubscribe',
                    type: 'DELETE',
                    data: {badgeid:badgeid},
                    success: function(data) {
                        urlRoute.ohSnap("Unsubscribed!", 'green');
                        $('#'+badgeid).attr("onclick","badgeInfo('"+badgeid+"','"+badgedesc+"','"+guide+"','')");
                    },
                    error: function(data){
                        urlRoute.ohSnap("Something went wrong!", 'red');
                    },
                    complete: function(data){
                        $('#badge_info').foundation('close');
                    }
                });
            });
            $('#sign-up-link').click(function (){
                $('#badge_info').foundation('close');
                urlRoute.loadPage('register');
            });
        }
        $('#badge_info').foundation('open');
    }

    var skip = 144;

    if(urlRoute.currentUrl == "/badges") {
        $(window).scroll(function() {
            if($(window).scrollTop() == $(document).height() - $(window).height()) {
                $.ajax({
                    url: urlRoute.getBaseUrl() + 'badges/load/'+skip,
                    type: 'get',
                    success: function(data) {
                        $('#list_all_badges').append(data['returnHTML']);
                        skip += 144;
                        Tipped.create('.hover-box-info');
                    }
                });
            }
        });
    }

    var destroy = function() {
        executeSearch = null;
        badgeError = null;
        badgeInfo = null;
    }
</script>
