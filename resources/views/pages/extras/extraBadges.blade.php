<?php $badges = \App\Helpers\ForumHelper::getLatestBages(144, $skip); ?>
<?php $questsHelper = new \App\Helpers\QuestsHelper; ?>

@foreach($badges as $badge)
	<div class="small-3 medium-2 large-1 column">
  <div class="badge-container hover-box-info" title="{{ $badge['desc'] }}">
    <img onerror="badgeError(this);" src="https://habboo-a.akamaihd.net/c_images/album1584/{{ $badge['name'] }}.gif" onclick="badgeInfo('{{ $badge['name'] }}', '{{ addslashes($badge['desc']) }}' , '{{ $questsHelper::getQuest($badge['name']) }}', '{{ $questsHelper::isSubscribed($badge['name']) }}' )" alt="badge" />
    @if($badge['new'])<div class="badge-new-badge" style="padding-left: 0.2rem;">New</div>@endif
  </div>
</div>
@endforeach

<script type="text/javascript">
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
            @else $('#badge_info').append("<div class='small-11 column text-right right'><a class='bold web-page' id='sign-up-link'>Sign up/log in to subscribe to this badge &raquo;</a></div>"); @endif
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
                          urlRoute.ohSnap("Success!", 'green');
                          $('#'+badgeid).attr("onclick","badgeInfo('"+badgeid+"','"+badgedesc+"','"+guide+"','1')");
                    },
                    error: function(data){
                         urlRoute.ohSnap("Something went wrong!", 'red');
                    },
                    complete: function(data){
                        $('#badge_info').foundation('close');
                        $('#'+badgeid).attr("onclick","");
                    }

                });
            });
            $('#unsubscribe-link').click(function (){
                $.ajax({
                    url: urlRoute.getBaseUrl() + 'badges/unsubscribe',
                    type: 'DELETE',
                    data: {badgeid:badgeid},
                    success: function(data) {
                          urlRoute.ohSnap("Success!", 'green');
                          urlRoute.loadPage('badges');
                          $('#'+badgeid).attr("onclick","badgeInfo('"+badgeid+"','"+badgedesc+"','"+guide+"','')");
                    },
                    error: function(data){
                         urlRoute.ohSnap("Something went wrong!", 'red');
                         urlRoute.loadPage('badges');
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
        badgeError = null;
        badgeInfo = null;
    }
</script>
