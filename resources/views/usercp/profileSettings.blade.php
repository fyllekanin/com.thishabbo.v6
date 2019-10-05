<?php $ForumHelper = new \App\Helpers\ForumHelper; ?>
<?php $UserHelper = new \App\Helpers\UserHelper; ?>

<script> urlRoute.setTitle("TH - Profile Settings");</script>

<div class="small-12 column">
    <div class="content-holder">
            <div class="content contentpadding">
                <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
                <span><a href="/usercp" class="bold web-page">UserCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
                Profile Settings
            </div>
    </div>
</div>

<div class="medium-4 column">
    @include('usercp.menu')
</div>

<div class="medium-8 column">
    @include('usercp.profileSettings.menu')
    @include('usercp.profileSettings.postbit')
    @include('usercp.profileSettings.nameIcon')
    @include('usercp.profileSettings.nameEffect')
    @include('usercp.profileSettings.background')
    @include('usercp.profileSettings.profileBadges')
    @include('usercp.profileSettings.biography')

    @if($UserHelper::haveSubFeature(Auth::user()->userid, 1))
        @if($UserHelper::haveSubFeature(Auth::user()->userid, 2) OR $UserHelper::haveSubFeature(Auth::user()->userid, 4) OR $UserHelper::haveSubFeature(Auth::user()->userid, 8))
            @include('usercp.profileSettings.username')
        @endif
        @if($UserHelper::haveSubFeature(Auth::user()->userid, 16) OR $UserHelper::haveSubFeature(Auth::user()->userid, 32) OR $UserHelper::haveSubFeature(Auth::user()->userid, 64))
            @include('usercp.profileSettings.userbar')
        @endif
    @endif
</div>

<script type="text/javascript">
    $("#editpostbit").click(function() {
        $('html, body').animate({
            scrollTop: $("#postbit").offset().top - 60
        }, 1000);
    });
    $("#editnameicon").click(function() {
        $('html, body').animate({
            scrollTop: $("#nameicon").offset().top - 60
        }, 1000);
    });
    $("#editnameeffect").click(function() {
        $('html, body').animate({
            scrollTop: $("#nameeffect").offset().top - 60
        }, 1000);
    });
    $("#editbackground").click(function() {
        $('html, body').animate({
            scrollTop: $("#background").offset().top - 60
        }, 1000);
    });
    $("#editprofilebadges").click(function() {
        $('html, body').animate({
            scrollTop: $("#profilebadges").offset().top - 60
        }, 1000);
    });
    $("#editbiography").click(function() {
        $('html, body').animate({
            scrollTop: $("#biography").offset().top - 60
        }, 1000);
    });
    @if($UserHelper::haveSubFeature(Auth::user()->userid, 1))
        @if($UserHelper::haveSubFeature(Auth::user()->userid, 2) OR $UserHelper::haveSubFeature(Auth::user()->userid, 4) OR $UserHelper::haveSubFeature(Auth::user()->userid, 8))
        $("#editusernamefatures").click(function() {
            $('html, body').animate({
                scrollTop: $("#username").offset().top - 60
            }, 1000);
        });
        @endif
        @if($UserHelper::haveSubFeature(Auth::user()->userid, 16) OR $UserHelper::haveSubFeature(Auth::user()->userid, 32) OR $UserHelper::haveSubFeature(Auth::user()->userid, 64))
        $("#edituserbarfeatures").click(function() {
            $('html, body').animate({
                scrollTop: $("#userbar").offset().top - 60
            }, 1000);
        });
        @endif
    @endif

    /* POSTBIT */
    var selectedBadges = [];
    var badgeUrl = "{{ url('_assets/img/website/badges/') }}";
    @foreach($slBadges as $slBadge)
        selectedBadges.push({
            id: "{{ $slBadge['badgeid'] }}",
            desc: "{{ $slBadge['description'] }}"
        });
    @endforeach
    var addBadge = function() {
        if(selectedBadges.length >= 4) {
            urlRoute.ohSnap('Maximum of 4 badges!', 'red');
        } else {
            var bg = $('#postbit_badge_style').val().split("&&");
            selectedBadges.push({
                id: bg[0],
                desc: bg[1]
            });
            $('#badge-select-'+bg[0]).remove();
            $('#selected-badges').prepend(`<img src="` + badgeUrl + `/` + bg[0] + `.gif" alt="` + bg[1] + `" id="` + bg[0] + `" style="cursor:pointer;margin-right:4px;"/>`);
        }
    }
    $('#selected-badges').on('click', 'img', function() {
        var id = $(this).attr('id'),
        desc = $(this).attr('alt');
        selectedBadges = selectedBadges.filter(function(badge){
            return parseInt(badge.id) !== parseInt(id);
        });
        $('#postbit_badge_style').append(`<option id="badge-select-`+id+`" value="`+id+`&&`+desc+`">`+desc+`</option>`);
        $(this).remove();
    });
    var savePostbit = function() {
        var jn = 0;
        var ps = 0;
        var lk = 0;
        var sa = 0;
        var lb = 0;
        var hh = 0;
        var post_avatar = $('#postbit_avatar_style').val();
        if($('#hideJoined').is(':checked')) {
            jn = 1;
        }
        if($('#hidePosts').is(':checked')) {
            ps = 1;
        }
        if($('#hideLikes').is(':checked')) {
            lk = 1;
        }
        if($('#hidesa').is(':checked')) {
            sa = 1;
        }
        if($('#hideLb').is(':checked')) {
            lb = 1;
        }
        if($('#hideHh').is(':checked')) {
            hh = 1;
        }
        var hide_groups = "";
        $('input:checkbox.userbar-check').each(function() {
            hide_groups = hide_groups + "," + (this.checked ? "" : $(this).val());
        });
        var slBad = selectedBadges.map(function(badge) {
            return badge.id;
        });
        $.ajax({
            url: urlRoute.getBaseUrl() + 'usercp/save/postbit',
            type: 'post',
            data: {jn:jn, ps:ps, lk:lk, sa:sa, lb:lb, hh:hh, hide_groups:hide_groups, post_avatar:post_avatar, selectedBadges:slBad},
            success: function(data) {
                if(data['response'] == true) {
                    urlRoute.ohSnap('Postbit saved!','green');
                } else {
                    urlRoute.ohSnap('Something went wrong', 'red');
                }
            }
        })
    }

    /* NAME ICON */
    var icons = [];
    var toggleIcon = function(ele, iconid) {
        if(icons.indexOf(iconid) >= 0) {
            var index = icons.indexOf(iconid);
            icons.splice(index, 1);
            $(ele).find('.badge-selected').fadeOut();
        } else{
            if(icons.length <= 0) {
                $(ele).find('.badge-selected').fadeIn();
                if(icons.indexOf(iconid) < 0) {
                icons.push(iconid);
                } else {
                    var index = icons.indexOf(iconid);
                    icons.splice(index, 1);
                    $(ele).find('.badge-selected').fadeOut();
                }
            } else {
                urlRoute.ohSnap('You can only have 1 icon selected at each time!', 'blue');
            }
        }
    }
    var saveSelectedIcon = function() {
        var icon = icons.length > 0 ? icons[0] : 0;
        var icon_side = $('input[name=icon_side]:checked').val();
        $.ajax({
            url: urlRoute.getBaseUrl() + 'usercp/save/nameicon',
            type: 'post',
            data: {icon:icon, icon_side:icon_side},
            success: function(data) {
                if(data['response'] === true) {
                    urlRoute.ohSnap('Awesome, Icon Saved!', 'green');
                    urlRoute.loadPage('/usercp/settings/profile');
                    urlRoute.loadAuthContent();
                } else {
                    urlRoute.ohSnap('Sorry, Are you sure you have this icon?', 'green');
                }
            }
        });
    }

    /* NAME EFFECT */
    var effects = [];
    var toggleEffect = function(ele, effectid) {
        if(effects.indexOf(effectid) >= 0) {
            var index = effects.indexOf(effectid);
            effects.splice(index, 1);
            $(ele).find('.badge-selected').fadeOut();
        } else{
            if(effects.length <= 0) {
                $(ele).find('.badge-selected').fadeIn();
                if(effects.indexOf(effectid) < 0) {
                    effects.push(effectid);
                } else {
                    var index = effects.indexOf(effectid);
                    effects.splice(index, 1);
                    $(ele).find('.badge-selected').fadeOut();
                }
            } else {
                urlRoute.ohSnap('You can only have 1 effect selected at each time!', 'blue');
            }
        }
    }
    var saveSelectedEffect = function() {
        var effect = effects.length > 0 ? effects[0] : 0;
        $.ajax({
            url: urlRoute.getBaseUrl() + 'usercp/save/nameeffect',
            type: 'post',
            data: {effect:effect},
            success: function(data) {
                if(data['response'] === true) {
                    urlRoute.ohSnap('Awesome, Effect Saved!', 'green');
                    urlRoute.loadPage('/usercp/settings/profile');
                    urlRoute.loadAuthContent();
                } else {
                    urlRoute.ohSnap('Sorry, Are you sure you have this icon?', 'green');
                }
            }
        });
    }

    /* BACKGROUND */
    var backgrounds = [];
    var toggleBackground = function(ele, backgroundid) {
        if(backgrounds.indexOf(backgroundid) >= 0) {
            var index = backgrounds.indexOf(backgroundid);
            backgrounds.splice(index, 1);
            $(ele).find('.badge-selected').fadeOut();
        } else{
            if(backgrounds.length <= 0) {
                $(ele).find('.badge-selected').fadeIn();
                if(backgrounds.indexOf(backgroundid) < 0) {
                    backgrounds.push(backgroundid);
                } else {
                    var index = backgrounds.indexOf(backgroundid);
                    backgrounds.splice(index, 1);
                    $(ele).find('.badge-selected').fadeOut();
                }
            } else {
                urlRoute.ohSnap('You can only have 1 background selected at each time!', 'blue');
            }
        }
    }
    var saveSelectedBackground = function() {
        var background = backgrounds.length > 0 ? backgrounds[0] : 0;
        $.ajax({
            url: urlRoute.getBaseUrl() + 'usercp/save/background',
            type: 'post',
            data: {background:background},
            success: function(data) {
                if(data['response'] === true) {
                    urlRoute.ohSnap('Awesome, Background Saved!', 'green');
                    urlRoute.loadPage('/usercp/settings/profile');
                } else {
                    urlRoute.ohSnap('Sorry, Are you sure you have this background?', 'green');
                }
            }
        });
    }

    /* PROFILE BADGES */
    var badges = [];
    var toggleBadge = function(ele, badgeid) {
        if(badges.length <= 8) {
            if(badges.indexOf(badgeid) < 0 && badges.length < 8) {
                $(ele).find('.badge-selected').fadeIn();
                badges.push(badgeid);
            } else if(badges.indexOf(badgeid) < 0 && badges.length === 8) {
                urlRoute.ohSnap('You can only have a maximum of 8 badges selected!', 'blue');
            } else {
                var index = badges.indexOf(badgeid);
                badges.splice(index, 1);
                $(ele).find('.badge-selected').fadeOut();
            }
        }
    }
    var saveSelectedBadges = function() {
        $.ajax({
            url: urlRoute.getBaseUrl() + 'usercp/save/badges',
            type: 'post',
            data: {badges:badges},
            success: function(data) {
                urlRoute.ohSnap('Badges Saved!', 'green');
                urlRoute.loadPage('/usercp/settings/profile');
            }
        });
    }

    /* BIOGRAPHY */
    var countAmount = null;
    var checkAmount = null;
    var saveBio = null;
    var countAmount = function () {
        var content = $('#edit-user-bio').val();
        $('#len').html(content.length);
    }
    var checkAmount = setInterval(function() {countAmount()}, 1000);
    var saveBio = function () {
        var content = $('#edit-user-bio').val();
        $.ajax({
            url: urlRoute.getBaseUrl() + 'usercp/save/bio',
            type: 'post',
            data: {content:content},
            success: function(data) {
                urlRoute.loadPage('/usercp/settings/profile');
                urlRoute.ohSnap('Biography saved!', 'green');
            }
        });
    }

    /* USERNAME FEATURES */
    var useDefault = function() {
        //set username_option to 0
        //username_color to empty
        $.ajax({
            url: urlRoute.getBaseUrl() + 'usercp/username/default',
            type: 'post',
            success: function(data) {
                urlRoute.loadPage('/usercp/settings/profile');
                urlRoute.ohSnap('Username updated!', 'green');
                urlRoute.loadAuthContent();
            }
        });
    }
    @if($username_one_color)
    var oneColor = function() {
        //set username_option to 1
        //username_color to input
        var color = $('#feature-one-username').val();
        $.ajax({
            url: urlRoute.getBaseUrl() + 'usercp/username/one',
            type: 'post',
            data: {color:color},
            success: function(data) {
                urlRoute.loadPage('/usercp/settings/profile');
                urlRoute.ohSnap('Username updated!', 'green');
                urlRoute.loadAuthContent();
            }
        });
    }
    @endif
    @if($username_rainbow_color)
    var rainbow = function() {
        //set username_option to 2
        //username_color to empty
        $.ajax({
            url: urlRoute.getBaseUrl() + 'usercp/username/rainbow',
            type: 'post',
            success: function(data) {
                urlRoute.loadPage('/usercp/settings/profile');
                urlRoute.ohSnap('Username updated!', 'green');
                urlRoute.loadAuthContent();
            }
        });
    }
    @endif
    @if($username_custom_rainbow_color)
    var customRainbow = function() {
        //set username_option to 3
        //username_color to input
        var colors = $('#feature-rainbow-username').val();
        $.ajax({
            url: urlRoute.getBaseUrl() + 'usercp/username/customRainbow',
            type: 'post',
            data: {colors:colors},
            success: function(data) {
                urlRoute.loadPage('/usercp/settings/profile');
                urlRoute.ohSnap('Username updated!', 'green');
                urlRoute.loadAuthContent();
            }
        });
    }
    @endif

    /* USERBAR FEATURES */
    var grabData = function() {
        var groupid = $('#userbarfeature-group').val();
        $.ajax({
            url: urlRoute.getBaseUrl() + 'usercp/userbar/specific/'+groupid,
            type: 'get',
            success: function(data) {
                $('#group_specific_info').html(data['returnHTML']);
            }
        });
    }

    $(document).ready(function() {
        $('.selected-badge').each(function(ele) {
            $(this).trigger("click");
        });
        $(document).foundation();
    });

    var destroy = function() {
        savePostbit = null;
        toggleIcon = null;
        saveSelectedBadges = null;
        toggleEffect = null;
        saveSelectedEffect = null;
        toggleEffect = null;
        saveSelectedEffect = null;
        toggleBadge = null;
        saveSelectedBadges = null;
        countAmount = null;
        saveBio = null;
        clearInterval(checkAmount);
        useDefault = null;
        oneColor = null;
        rainbow = null;
        customRainbow = null;
        grabData = null;
    }
</script>
