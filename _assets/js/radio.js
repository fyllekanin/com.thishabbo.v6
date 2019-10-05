const radioPlayerId = 'radio_player';
const sliderId = 'audioSlider';
const radioMutedIndicator = 'radio-picture-muted';
const radioPlayerButtons = {
    play: 'radioPlayButton',
    mobilePlay: 'mobileRadioPlayButton',
    mute: 'radioMuteButton',
    like: 'radioLikeButton'
};

var radio = {

    start: function() {
        this.addListeners();
        this.addIntervals();
        this.radioPlayer = document.getElementById(radioPlayerId);
        this.radioUrl = this.radioPlayer.src;

        this.slider = $(`#${sliderId}`).slider({
            orientation: "horizontal",
            min: 0,
            max: 1,
            step: 0.01,
            animate: true,
            range: 'min',
            value: this.getVolume(),
            slide: (_e, ui) => {
                this.radioPlayer.volume = ui.value;
                if (this.radioPlayer.volume === 0) {
                    this.muteRadio();
                } else if (this.radioPlayer.paused) {
                    this.startRadio();
                }
            },
            stop: (_e, ui) => {
                urlRoute.setStorage('radio_volume', ui.value);
            }
        });

        //const isRadioMuted = urlRoute.getStorage('radio_muted');
        //if (isRadioMuted && isRadioMuted.value === 1) {
            this.muteRadio();
        //}

        if (!$('#top-navigation').is(':visible')) {
            $('.radio3').css('display', 'block');
        }
    },

    addListeners: function() {
        document.getElementById(radioPlayerButtons.play).addEventListener('click', this.startRadio.bind(this));
        document.getElementById(radioPlayerButtons.mute).addEventListener('click', this.muteRadio.bind(this));
        document.getElementById(radioPlayerButtons.like).addEventListener('click', this.likeDj.bind(this));
        document.getElementById(radioPlayerButtons.mobilePlay).addEventListener('click', this.startRadio.bind(this));
    },

    reloadRadio: function() {
        const isRadioMuted = urlRoute.getStorage('radio_muted');
        if (isRadioMuted && isRadioMuted.value === 1) {
            return;
        }
        const volume = this.getVolume();
        this.radioPlayer.src = this.radioUrl;
        this.radioPlayer.load();
        this.radioPlayer.volume = volume;
        this.radioPlayer.play();
    },

    startRadio: function() {
        if (!this.radioPlayer.paused) {
            this.muteRadio();
            return;
        }
        const volume = this.getVolume();
        this.radioPlayer.src = this.radioUrl;
        this.radioPlayer.load();

        this.slider.slider('value', volume);
        urlRoute.removeStorage('radio_muted');
        $(`.${radioMutedIndicator}`).fadeOut();
        this.radioPlayer.volume = volume;
        this.radioPlayer.play();
        $('#mobileRadioPlayButton').html('<i class="fa fa-pause hover-box-info" title="Stop Radio" aria-hidden="true" title=""></i>');
        $('#radio-stats').css('margin-top','0');
        $('#dj_song').fadeIn();
    },

    muteRadio: function() {
        this.slider.slider('value', 0);
        urlRoute.setStorage('radio_muted', 1);
        $(`.${radioMutedIndicator}`).fadeIn();
        this.radioPlayer.volume = 0;
        this.radioPlayer.pause();
        this.radioPlayer.src = '';
        this.radioPlayer.load();
        $('#radio-stats').css('margin-top','-15px');
        $('#dj_song').fadeOut();
        $('#mobileRadioPlayButton').html('<i class="fa fa-play hover-box-info" title="Start Radio" aria-hidden="true" title=""></i>');
    },

    likeDj: function() {
        $.ajax({
            url: urlRoute.getBaseUrl() + 'radio/like/dj',
            type: 'get',
            success: function (data) {
                if (!data || !data['response']) {
                    const type = data['login'] ? 'blue' : 'red';
                    const message = data['login'] ?
                        '<span class=\"alert-title\">Oh man!</span><br />You must be logged in to like the current DJ!' :
                        `<span class=\"alert-title\">Oh man!</span><br />You can\'t like again for another ${data['timeout']} min(s)!`;
                    urlRoute.ohSnap(message, type);
                    return;
                }
                urlRoute.ohSnap('<span class=\"alert-title\">Love me senpai!</span><br />You liked ' + data['djname'] + '!', 'green');
            }.bind(this)
        });
    },

    getVolume: function() {
        const storedVolume = urlRoute.getStorage('radio_volume');
        return storedVolume ? storedVolume.value : 0.2;
    },

    refresh: function() {
        this.updateRadioStats();
        this.reloadActivies();
        this.reloadNotices();
    },

    addIntervals: function() {
        this.refresh();
        setInterval(this.refresh.bind(this), 30000);
    },
    
    isMobile: function() {
        var check = false;
        (function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
        return check;
    },

    advertiseRadio: function() {
        if (this.isMobile()) {
            return;
        }
        urlRoute.ohSnap('<span class=\"alert-title\">Press the play button!</span><br />' + $('#dj_name').html() + ' is on air.', 'blue', 360000);
    },

    reloadNotices: function() {
        $.ajax({
            url: urlRoute.getBaseUrl() + 'sitenotices',
            type: 'get',
            success: function (data) {
                $('#site_notices').html('');
                for (let i = 0; i < data.length; i++) {
                    if (!urlRoute.getStorage('notice_' + data[i]['noticeid'] + '_dismissed')) {
                        $('#site_notices').append(`<div class="alertt alert-` + data[i]['type'] + `" id="notice-` + data[i]['noticeid'] + `" role="alertt" style="margin-bottom: 8px;"><a onclick="urlRoute.setStorage('notice_` + data[i]['noticeid'] + `_dismissed', 1); $('#notice-` + data[i]['noticeid'] + `').fadeOut();" style="float:right; font-size: 21px; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; filter: alpha(opacity=20); outline: 0;opacity: .2;">x</a><b>` + data[i]['title'] + `</b><br/>` + data[i]['body'] + `</div>`);
                    }
                }
            }.bind(this)
        });
    },

    reloadActivies: function() {
        $.ajax({
            url: urlRoute.getBaseUrl() + 'active',
            type: 'get',
            success: function (data) {
                $('#actives').html('');
                for (let i = 0; i < data.length; i++) {
                    $('#actives').append(`<a href="/profile/` + data[i]['username'] + `/page/1" class="web-page"><div class="footeractiveusers hover-box-info" style="background-image: url('` + data[i]['avatar'] + `');" title="<center><b>` + data[i]['username'] + `<br />Last Active:</b> ` + data[i]['lastactivity'] + `</center>"></div></a>`);
                }
            }.bind(this)
        });
    },

    checkQuestArticles: function(latest_article) {
        if (!latest_article) {
            return;
        }
        $('#latest_quest_id_holder').text(latest_article.articleid);
        var available = '<span class="questlabel label-quest questlabel-available">Available</span>';
        if (latest_article.available === 1) {
            available = '<span class="questlabel label-quest questlabel-available">Available</span>';
        } else if (latest_article === 2) {
            available = '<span class="questlabel label-quest questlabel-unavailable">Unavailable</span>';
        }
        var badgeContent = '';
        if (latest_article.badge_code !== '') {
            badgeContent = `<div class="badgebackground"><img src="https://habboo-a.akamaihd.net/c_images/album1584/` + latest_article.badge_code + `.gif" /></div>`;
        }
        $('#top_quest_article').fadeOut();
        $('#top_quest_article').html(`<div class="advertsfront" style="background-image: url(` + latest_article.image + `);" title="" onclick="urlRoute.loadPage('/article/` + latest_article.articleid + `');">` + available + badgeContent + `<div class="text"><a href="/article/` + latest_article.articleid + `" class="web-page red_link"><b>` + latest_article.title + `</b></a><br></div></div>`).hide().delay(1000).fadeIn(2000);
    },

    updateRadioStats: function() {
        const latest_questid = $('#latest_quest_id_holder').text();
        $.ajax({
            url: `${urlRoute.getBaseUrl()}radio/stats/1?latest_questid=${latest_questid}`,
            type: 'get',
            success: function (data) {
                this.checkQuestArticles(data['latest_article']);
                if (data['radio_details']) {

                    if ($('#djsaysmessage').html() !== data.djsaysmessage) {
                        $('#djsaysname').html(data.djsaysname);
                        $('#djsaysmessage').html(data.djsaysmessage);
                    }
                    if (data.djid !== this.currentDj) {
                        this.reloadRadio();
                        $('#djhabbo').html(data.djhabbo);
                    }
                    if ($('#dj_next_on_air').html() !== data.next_on_air) {
                        $('#dj_next_on_air').html(data.next_on_air);
                    }
                    if ($('#dj_loves').html() !== data.djlikes) {
                        $('#dj_loves').html(data.djlikes);
                    }
                    if (data.song !== $('#dj_song').html()) {
                        $('#dj_song').html(data.song);
                        $('#albumArt').css("background-image", "url(" + data.album_art + ")");
                    }
                    if (data.dj !== $('#dj_name').html()) {
                        $('#dj_name').html(data.dj);
                        if(this.radioPlayer.paused && data.djid !== this.currentDj) {
                            this.advertiseRadio();
                        }
                    }
                    if (data.listeners !== $('#dj_listeners').html()) {
                        $('#dj_listeners').html(data.listeners);
                    }
                    this.currentDj = data.djid;
                }
            }.bind(this)
        });
    }
}

radio.start();
