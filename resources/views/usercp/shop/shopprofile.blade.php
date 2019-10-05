<div class="mobileShop small-4 column end">
    <div class="content-holder">
            <div class="content">
            <div class="contentHeader headerRed">
                    ThisHabbo Shop
                </div>
                <div class="content-ct">
                    <button class="pg-red headerRed gradualfader shopbutton" style="width:100%;" onclick="toggleDiv('voucher-div');">
                        Redeem Voucher
                    </button>
                    <div id="voucher-div" class="content-gray">
                        <input type="text" placeholder="Voucher" id="vouchercode-code" class="login-form-input">
                        <center>
                        <button class="pg-red headerRed gradualfader shopbutton" onclick="redeemCode();">
                            Redeem
                        </button>
                        </center>
                    </div>
                    <a href="/usercp/credits" class="web-page">
                        <button class="pg-red headerRed gradualfader shopbutton" style="width:100%; margin-top: 10px;">
                            Buy Diamonds
                        </button>
                    </a>
                    <button class="pg-red headerRed gradualfader shopbutton" style="width:100%; margin-top: 10px;" onclick="toggleDiv('gift-div');">
                        Send THC
                    </button>
                    <div id="gift-div" class="content-gray">
                        <input type="text" placeholder="Username..." id="gift-username" class="login-form-input"><br/>
                        <input type="number" placeholder="Credits amount..." id="gift-points" class="login-form-input"><br />
                        <center><button class="pg-red headerRed gradualfader shopbutton floatright" onclick="giftPoints();">Gift Points</button></center>
                    </div>
                    <button class="pg-red headerRed gradualfader shopbutton" style="width:100%; margin-top: 10px;" onclick="toggleDiv('earn-div');">
                        How to earn THC?
                    </button>
                    <div id="earn-div" class="content-gray">
                        You can earn THC simply by attending one of our events and winning an event, or by participating in competitions around the website! Keep your eyes peeled for these opportunities.
                    </div>
                    <button class="pg-red headerRed gradualfader shopbutton" style="width:100%; margin-top: 10px;" onclick="toggleDiv('thcd-div');">
                        THC <i class="fa fa-arrow-right" aria-hidden="true"></i> Diamonds
                    </button>
                    <div id="thcd-div" class="content-gray">
                        <p><b>1 Diamond is equal to 2500 THC</b></p>
                        <input type="text" placeholder="Number of Diamonds to get..." id="swapthcd-number" class="login-form-input"><br />
                        <center><button class="pg-red headerRed gradualfader shopbutton floatright" onclick="swapthcd();">Change <i class="fa fa-ticket" aria-hidden="true"></i> to <i class="fa fa-diamond" aria-hidden="true"></i></button></center>
                    </div>

                    <button class="pg-red headerRed gradualfader shopbutton" style="width:100%; margin-top: 10px;" onclick="toggleDiv('thdc-div');">
                        Diamonds <i class="fa fa-arrow-right" aria-hidden="true"></i> THC
                    </button>
                    <div id="thdc-div" class="content-gray">
                        <p><b>2500 THC is equal to 1 Diamond</b></p>
                        <input type="text" placeholder="Number of Diamonds to trade..." id="swapthdc-number" class="login-form-input"><br />
                        <center><button class="pg-red headerRed gradualfader shopbutton floatright" onclick="swapthdc();">Change <i class="fa fa-diamond" aria-hidden="true"></i> to <i class="fa fa-ticket" aria-hidden="true"></i></button></center>
                    </div>
                </div>
            </div>
    </div>
</div>

<script type="text/javascript">
    var toggleDiv = function(elName) {
        $('#'+elName).slideToggle();
    }

    var swapthcd = function() {
        var points = $('#swapthcd-number').val();

        $.ajax({
            url: urlRoute.getBaseUrl() + 'usercp/swap/thc',
            type: 'post',
            data: {points:points},
            success: function(data) {
                if(data['response'] === true) {
                    urlRoute.ohSnap('The requested THC has been converted to Diamonds!', 'green');
                    urlRoute.reloadPage();
                } else {
                    urlRoute.ohSnap(data['message'], 'red');
                }
            }
        })
    }

    var swapthdc = function() {
        var points = $('#swapthdc-number').val();

        $.ajax({
            url: urlRoute.getBaseUrl() + 'usercp/swap/diamonds',
            type: 'post',
            data: {points:points},
            success: function(data) {
                if(data['response'] === true) {
                    urlRoute.ohSnap('The requested Diamonds has been converted to THC!', 'green');
                    urlRoute.reloadPage();
                } else {
                    urlRoute.ohSnap(data['message'], 'red');
                }
            }
        })
    }

    var giftPoints = function() {
        var username = $('#gift-username').val();
        var points = $('#gift-points').val();

        $.ajax({
            url: urlRoute.getBaseUrl() + 'usercp/gift/points',
            type: 'post',
            data: {username:username, points:points},
            success: function(data) {
                if(data['response'] === true) {
                    $('#gift-username').val('');
                    $('#gift-points').val('');

                    var current = parseInt($('#CURRENT_AMOUNT').text());
                    current = current + parseInt(data['points']);
                    $('#CURRENT_AMOUNT').text(current);

                    urlRoute.ohSnap('Points sent!', 'green');
                } else {
                    urlRoute.ohSnap(data['message'], 'red');
                }
            }
        })
    }

    var redeemCode = function() {
        var code = $('#vouchercode-code').val();
        $.ajax({
            url: urlRoute.getBaseUrl() + 'usercp/redeem/code',
            type: 'post',
            data: {code:code},
            success: function(data) {
                if(data['response'] === true) {
                    urlRoute.ohSnap('Success! you got ' + data['worth'] + ' credits!', 'green');

                    var current = parseInt($('#CURRENT_AMOUNT').text());
                    current = current + parseInt(data['worth']);
                    $('#CURRENT_AMOUNT').text(current);
                    $('#vouchercode-code').val('');

                } else {
                    urlRoute.ohSnap(data['message'], 'red');
                }
            }
        });
    }
</script>

<script type="text/javascript">
    var packageid = 0;

    $(document).ready(function() {
        $(document).foundation();
    });

    var openYouSureSub = function(spackageid, dprice, name, description) {
        $('#SUB_NAME').text(name);
        $('#SUB_DPRICE').text(dprice);
        $('#SUB_DESC').text(description);
        packageid = spackageid;
    }

    var buySub = function() {
        $.ajax({
            url: urlRoute.getBaseUrl() + 'usercp/shop/subs/buy',
            type: 'post',
            data: {packageid:packageid},
            success: function(data) {
                if(data['response'] === true) {
                    urlRoute.ohSnap('You bought a month of ' + $('#SUB_NAME').text(), 'green');
                    urlRoute.reloadPage();
                } else {
                    urlRoute.ohSnap(data['message'], 'red');
                }
            }
        });
    }
</script>

<script type="text/javascript">
    var boxid = 0;

    var openYouSureBox = function(sboxid, price, name, description) {
        $('#BOX_NAME').text(name);
        $('#BOX_PRICE').text(price);
        $('#BOX_DESC').text(description)

        $('#BOX_PIC').html('<img src="' + urlRoute.getBaseUrl() + '_assets/img/boxes/' + sboxid + '.gif" />');
        boxid = sboxid;
    }

    var buyBox = function() {
        $.ajax({
            url: urlRoute.getBaseUrl() + 'usercp/shop/boxes/buy',
            type: 'post',
            data: {boxid:boxid},
            success: function(data) {
                if(data['response'] === true) {
                    urlRoute.ohSnap('You bought the box!', 'green');
                    urlRoute.loadPage('/usercp/shop/box/open/'+data['purchaseId']);
                } else {
                    urlRoute.ohSnap(data['message'], 'red');
                    console.log(data['message']);

                }
            }
        });
    }
</script>

<script type="text/javascript">
    var stickerid = 0;

    var openYouSureSticker = function(sstickerid, price, name, description) {
        $('#STICKER_NAME').text(name);
        $('#STICKER_PRICE').text(price);
        $('#STICKER_DESC').text(description);
        $('#STICKER_PIC').html('<img src="' + urlRoute.getBaseUrl()+ '_assets/img/stickers/' + sstickerid + '.gif" />');
        stickerid = sstickerid;
    }

    var buySticker = function() {
        $.ajax({
            url: urlRoute.getBaseUrl() + 'usercp/shop/stickers/buy',
            type: 'post',
            data: {stickerid:stickerid},
            success: function(data) {
                if(data['response'] === true) {
                    urlRoute.ohSnap('You bought the sticker!', 'green');
                    urlRoute.reloadPage();
                } else {
                    urlRoute.ohSnap(data['message'], 'red');
                }
            }
        });
    }
</script>

<script type="text/javascript">
    var backgroundid = 0;

    var openYouSureBackground = function(sbackgroundid, price, name, description) {
        $('#BACKGROUND_NAME').text(name);
        $('#BACKGROUND_PRICE').text(price);
        $('#BACKGROUND_DESC').text(description);

        $('#BACKGROUND_PIC').html('<img src="' + urlRoute.getBaseUrl()+ '_assets/img/backgrounds/' + sbackgroundid + '.gif" />');
        backgroundid = sbackgroundid;
    }

    var buyBackground = function() {
        $.ajax({
            url: urlRoute.getBaseUrl() + 'usercp/shop/backgrounds/buy',
            type: 'post',
            data: {backgroundid:backgroundid},
            success: function(data) {
                if(data['response'] === true) {
                    urlRoute.ohSnap('You bought the background!', 'green');
                    urlRoute.reloadPage();
                } else {
                    urlRoute.ohSnap(data['message'], 'red');
                }
            }
        });
    }
</script>

<script type="text/javascript">
    var iconid = 0;

    var openYouSureIcon = function(siconid, price, name, description) {
        $('#ICON_NAME').text(name);
        $('#ICON_PRICE').text(price);
        $('#ICON_DESC').text(description);

        $('#ICON_PIC').html('<img src="' + urlRoute.getBaseUrl()+ '_assets/img/nameicons/' + siconid + '.gif" />');
        iconid = siconid;
    }

    var buyIcon = function() {
        $.ajax({
            url: urlRoute.getBaseUrl() + 'usercp/shop/icons/buy',
            type: 'post',
            data: {iconid:iconid},
            success: function(data) {
                if(data['response'] === true) {
                    urlRoute.ohSnap('You bought the icon!', 'green');
                    urlRoute.reloadPage();
                } else {
                    urlRoute.ohSnap(data['message'], 'red');
                }
            }
        });
    }
</script>

<script type="text/javascript">
    var effectid = 0;
    var openYouSureEffect = function(seffectid, price, name, description) {
        $('#EFFECT_NAME').text(name);
        $('#EFFECT_PRICE').text(price);
        $('#EFFECT_DESC').text(description);

        $('#EFFECT_PIC').html('<img src="' + urlRoute.getBaseUrl()+ '_assets/img/nameeffects/' + seffectid + '.gif" />');
        effectid = seffectid;
    }

    var buyEffect = function() {
        $.ajax({
            url: urlRoute.getBaseUrl() + 'usercp/shop/effects/buy',
            type: 'post',
            data: {effectid:effectid},
            success: function(data) {
                if(data['response'] === true) {
                    urlRoute.ohSnap('You bought the effect!', 'green');
                    urlRoute.reloadPage();
                } else {
                    urlRoute.ohSnap(data['message'], 'red');
                }
            }
        });
    }
</script>

<script type="text/javascript">
    var themeid = 0;

    var openYouSureTheme = function(sthemeid, price, name, description) {
        $('#THEME_NAME').text(name);
        $('#THEME_PRICE').text(price);
        $('#THEME_DESC').text(description);

        $('#THEME_PIC').html('<img src="' + urlRoute.getBaseUrl()+ '_assets/img/themes/' + sthemeid + '.gif" />');

        themeid = sthemeid;
    }

    var buyTheme = function() {
        $.ajax({
            url: urlRoute.getBaseUrl() + 'usercp/shop/theme/buy',
            type: 'post',
            data: {themeid:themeid},
            success: function(data) {
                if(data['response'] === true) {
                    urlRoute.ohSnap('You bought the theme!', 'green');
                    urlRoute.reloadPage();
                } else {
                    urlRoute.ohSnap(data['message'], 'red');
                }
            }
        });
    }
</script>
