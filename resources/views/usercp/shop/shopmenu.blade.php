<?php $items = \App\Helpers\ForumHelper::getOnlineUsers(); ?>

<div class="reveal" id="icon_you_sure" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
    <div class="modal-header">
        <button class=".modal-header .close" data-close aria-label="Close modal" type="button" style="float:right; font-size: 21px; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; filter: alpha(opacity=20); opacity: .2;">x</button>
        <h4 class="modal-title">Are you sure?</h4>
    </div>
    <div class="modal-body">
        Are you sure you want to buy the icon <b id="ICON_NAME"></b> for <b id="ICON_PRICE"></b> credits?<br>
        <i id="ICON_DESC"></i>
        <div class="small-centered" id="ICON_PIC"></div>
    </div>
    <div class="modal-footer">
        <button class="pg-red fullWidth headerRed gradualfader shopbutton" onclick="buyIcon();">Buy <i class="fa fa-ticket" aria-hidden="true"></i></button>
        <button id="close" class="pg-red fullWidth headerBlue floatright gradualfader" style="padding: 6px 12px;" data-close aria-label="Close modal" type="button">Close</button>
    </div>
</div>

<div class="reveal" id="box_you_sure" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
    <div class="modal-header">
        <button class=".modal-header .close" data-close aria-label="Close modal" type="button" style="float:right; font-size: 21px; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; filter: alpha(opacity=20); opacity: .2;">x</button>
        <h4 class="modal-title">Are you sure?</h4>
    </div>
    <div class="modal-body">
        Are you sure you want to buy the box <b id="BOX_NAME"></b> for <b id="BOX_PRICE"></b> credits?<br>
        <i id="BOX_DESC"></i>
        <div class="small-centered" id="BOX_PIC"></div>
    </div>
    <div class="modal-footer">
        <button class="pg-red fullWidth headerRed gradualfader shopbutton" onclick="buyBox();">Buy <i class="fa fa-ticket" aria-hidden="true"></i></button>
        <button id="close" class="pg-red fullWidth headerBlue floatright gradualfader" style="padding: 6px 12px;" data-close aria-label="Close modal" type="button">Close</button>
    </div>
</div>

<div class="reveal" id="sticker_you_sure" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
    <div class="modal-header">
        <button class=".modal-header .close" data-close aria-label="Close modal" type="button" style="float:right; font-size: 21px; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; filter: alpha(opacity=20); opacity: .2;">x</button>
        <h4 class="modal-title">Are you sure?</h4>
    </div>
    <div class="modal-body">
        Are you sure you want to buy the sticker <b id="STICKER_NAME"></b> for <b id="STICKER_PRICE"></b> credits?<br>
        <i id="STICKER_DESC"></i>
        <div class="small-centered" id="STICKER_PIC"></div>
    </div>
    <div class="modal-footer">
        <button class="pg-red fullWidth headerRed gradualfader shopbutton" onclick="buySticker();">Buy <i class="fa fa-ticket" aria-hidden="true"></i></button>
        <button id="close" class="pg-red fullWidth headerBlue floatright gradualfader" style="padding: 6px 12px;" data-close aria-label="Close modal" type="button">Close</button>
    </div>
</div>

<div class="reveal" id="effect_you_sure" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
    <div class="modal-header">
        <button class=".modal-header .close" data-close aria-label="Close modal" type="button" style="float:right; font-size: 21px; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; filter: alpha(opacity=20); opacity: .2;">x</button>
        <h4 class="modal-title">Are you sure?</h4>
    </div>
    <div class="modal-body">
        Are you sure you want to buy the effect <b id="EFFECT_NAME"></b> for <b id="EFFECT_PRICE"></b> credits?<br>
        <i id="EFFECT_DESC"></i>
        <div class="small-centered" id="EFFECT_PIC"></div>
    </div>
    <div class="modal-footer">
        <button class="pg-red fullWidth headerRed gradualfader shopbutton" onclick="buyEffect();">Buy <i class="fa fa-ticket" aria-hidden="true"></i></button>
        <button id="close" class="pg-red fullWidth headerBlue floatright gradualfader" style="padding: 6px 12px;" data-close aria-label="Close modal" type="button">Close</button>
    </div>
</div>

<div class="reveal" id="sub_you_sure" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
    <div class="modal-header">
        <button class=".modal-header .close" data-close aria-label="Close modal" type="button" style="float:right; font-size: 21px; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; filter: alpha(opacity=20); opacity: .2;">x</button>
        <h4 class="modal-title">Are you sure?</h4>
    </div>
    <div class="modal-body">
        Are you sure you want to buy the subscription <b id="SUB_NAME"></b> for <b id="SUB_DPRICE"></b> diamonds?<br>
        <i id="SUB_DESC"></i>
    </div>
    <div class="modal-footer">
        <button class="pg-red fullWidth headerRed gradualfader shopbutton" onclick="buySub();">Buy with Diamonds <i class="fa fa-diamond" aria-hidden="true"></i></button>
        <button id="close" class="pg-red fullWidth headerBlue floatright gradualfader" style="padding: 6px 12px;" data-close aria-label="Close modal" type="button">Close</button>
    </div>
</div>

<div class="reveal" id="theme_you_sure" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
    <div class="modal-header">
        <button class=".modal-header .close" data-close aria-label="Close modal" type="button" style="float:right; font-size: 21px; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; filter: alpha(opacity=20); opacity: .2;">x</button>
        <h4 class="modal-title">Are you sure?</h4>
    </div>
    <div class="modal-body">
        Are you sure you want to buy the theme <b id="THEME_NAME"></b> for <b id="THEME_PRICE"></b> credits?<br>
        <i id="THEME_DESC"></i>
        <div class="small-centered" id="THEME_PIC"></div>
    </div>
    <div class="modal-footer">
        <button class="pg-red fullWidth headerRed gradualfader shopbutton" onclick="buyTheme();">Buy <i class="fa fa-ticket" aria-hidden="true"></i></button>
        <button id="close" class="pg-red fullWidth headerBlue floatright gradualfader" style="padding: 6px 12px;" data-close aria-label="Close modal" type="button">Close</button>
    </div>
</div>

<div class="medium-4 mobileFunction column">
    <div class="content-holder minHeight">
        <div class="content">
            <div class="contentHeader headerBlue">
                ThisHabbo Shop Categories
            </div>
            <div class="content-ct">
                <a href="/usercp/shop" clasS="web-page">
                    <button class="pg-blue headerBlue gradualfader shopbutton  fullWidth">
                        Shop Home
                    </button>
                </a>
                <a href="/usercp/shop/backgrounds/page/1" class="web-page">
                    <button class="pg-blue headerBlue gradualfader shopbutton  fullWidth">
                        Backgrounds
                    </button>
                </a>
                <a href="/usercp/shop/effects/page/1" class="web-page">
                    <button class="pg-blue headerBlue gradualfader shopbutton  fullWidth">
                        Name Effects
                    </button>
                </a>
                <a href="/usercp/shop/icons/page/1" class="web-page">
                    <button class="pg-blue headerBlue gradualfader shopbutton  fullWidth">
                        Name Icons
                    </button>
                </a>
                <a href="/usercp/shop/stickers/page/1" class="web-page">
                    <button class="pg-blue headerBlue gradualfader shopbutton  fullWidth">
                        Stickers
                    </button>
                </a>
                <a href="/usercp/shop/subs/page/1" class="web-page">
                    <button class="pg-blue headerBlue gradualfader shopbutton  fullWidth">
                        Subscriptions
                    </button>
                </a>
                <a href="/usercp/shop/themes/page/1" class="web-page">
                    <button class="pg-blue headerBlue gradualfader shopbutton  fullWidth">
                        Themes
                    </button>
                </a>
                <!--<a href="/usercp/shop/boxes/page/1" class="web-page">
                    <button class="pg-blue headerBlue gradualfader shopbutton  fullWidth">
                        Mystery Boxes
                    </button>
                </a>-->
                <br />
                <b>My ThisHabboCredits (THC):</b> <span id="CURRENT_AMOUNT">{{ number_format(Auth::user()->credits) }}</span><br />
                <b>My ThisHabboDiamonds (THD):</b> <span id="CURRENT_DAMOUNT">{{ number_format(Auth::user()->diamonds) }}</span><br />
                <br />
                <b>What is THC?</b><br />
                THC is the equivalent of Credits on Habbo. It's one of the two currencies here at ThisHabbo. Think of it primarily as monopoly money!<br >
                <br />
                <b>What is THD?</b><br />
                THD is the equivalent of Diamonds on Habbo. It's one of the two currencies here at ThisHabbo. Think of it primarily as monopoly money!<br >
                <br />
                <b>Can I send THC to other users?</b><br />
                Yes, you can. At the bottom left of this page is a form you can fill out to send THC to one of your friends!<br />
                <br />
                <b>Can I send THD to other users?</b><br />
                No! THD is the equivalent of 2500 THC, so we felt it was nicer/ more manageable for you to send THC only.<br />
                <br />
                <b>I have bought a name effect, how do I use it?</b><br />
                You can click <a href="/usercp/settings/profile" class="web-page blue_link">here</a> and choose it from the ones you own!<br />
                <br />
                <b>I have bought an icon, how do I use it?</b><br />
                You can click <a href="/usercp/settings/profile" class="web-page blue_link">here</a> and choose it from the ones you own!<br />
                <br />
            </div>
        </div>
    </div>
</div>
