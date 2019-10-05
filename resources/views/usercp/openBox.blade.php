<?php $ForumHelper = new \App\Helpers\ForumHelper; ?>
<?php $UserHelper = new \App\Helpers\UserHelper; ?>

<script> urlRoute.setTitle("TH - Open a box!");</script>

<div class="reveal" id="open30_box" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
    <div class="modal-header">
        <button class=".modal-header .close" data-close aria-label="Close modal" type="button" style="float:right; font-size: 21px; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; filter: alpha(opacity=20); opacity: .2;">x</button>
        <h4 class="modal-title">Open a box!</h4>
    </div>
    <div class="modal-footer">
        <button class="pg-red headerRed gradualfader fullWidth shopbutton" onclick="openBox(30, 1);">Open the box! (1 Key)</button>
        <button id="close" class="pg-red headerBlue floatright gradualfader fullWidth" style="padding: 6px 12px;" data-close aria-label="Close modal" type="button">Close</button>
    </div>
</div>

<div class="reveal" id="open50_box" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
    <div class="modal-header">
        <button class=".modal-header .close" data-close aria-label="Close modal" type="button" style="float:right; font-size: 21px; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; filter: alpha(opacity=20); opacity: .2;">x</button>
        <h4 class="modal-title">Open a box!</h4>
    </div>
    <div class="modal-footer">
        <button class="pg-red headerRed gradualfader fullWidth shopbutton" onclick="openBox(50, 2);">Open the box! (2 Keys)</button>
        <button id="close" class="pg-red headerBlue floatright gradualfader fullWidth" style="padding: 6px 12px;" data-close aria-label="Close modal" type="button">Close</button>
    </div>
</div>

<div class="reveal" id="open70_box" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
    <div class="modal-header">
        <button class=".modal-header .close" data-close aria-label="Close modal" type="button" style="float:right; font-size: 21px; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; filter: alpha(opacity=20); opacity: .2;">x</button>
        <h4 class="modal-title">Open a box!</h4>
    </div>
    <div class="modal-footer">
        <button class="pg-red headerRed gradualfader fullWidth shopbutton" onclick="openBox(70, 3);">Open the box! (3 Keys)</button>
        <button id="close" class="pg-red headerBlue floatright gradualfader fullWidth" style="padding: 6px 12px;" data-close aria-label="Close modal" type="button">Close</button>
    </div>
</div>

<div class="small-12 column">
    <div class="content-holder">
            <div class="content contentpadding">
                <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
                <span><a href="/usercp" class="bold web-page">UserCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
                Open a box!
            </div>
    </div>
</div>

<div class="medium-4 column">
    @include('usercp.menu')
</div>

<div class="medium-8 column">
    <div class="content-holder">
        <div class="content">
            <div class="contentHeader headerRed">
                <span>Open a box!</span>
            </div>
            You currently have <b>{{ Auth::user()->owned_keys }}</b> keys to use!
        </div>
    </div>

    <div class="content-holder">
        <div class="content">
            <div class="medium-4 column" style="text-align: center">
                <img src="https://www.thishabbo.com/_assets/img/boxes/1.gif" onclick="open30Modal();"><br /><br />
                <b>Chance</b> 30%<br />
                <b>Price:</b> 1 Key
            </div>
            <div class="medium-4 column" style="text-align: center">
                <img src="https://www.thishabbo.com/_assets/img/boxes/2.gif" onclick="open50Modal();"><br /><br />
                <b>Chance</b> 50%<br />
                <b>Price:</b> 2 Keys
            </div>
            <div class="medium-4 column" style="text-align: center">
                <img src="https://www.thishabbo.com/_assets/img/boxes/4.gif" onclick="open70Modal();"><br /><br />
                <b>Chance</b> 70%<br />
                <b>Price:</b> 3 Keys
            </div>
        </div>
    </div>

    <div class="content-holder">
        <div class="content">
            <div class="contentHeader headerRed">
                <span>Your Prizes</span>
            </div>
            <div class="small-12">
                <table class="responsive" style="width: 100%;">
                    <tr>
                        <td style="width:33.3%"><b>Prize</b></td>
                        <td style="width:33.3%"><b>Claimed</b></td>
                        <td style="width:33.3%"><b>Date Earnt</b></td>
                    </tr>
                    @foreach($prizes as $prize)
                    <tr>
                        <td style="width:33.3%">{{ $prize['prize'] }}</td>
                        <td style="width:33.3%">{{ $prize['claimed'] }}</td>
                        <td style="width:33.3%">{{ $prize['dateline'] }}</td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    var open30Modal = function() {
        $('#open30_box').foundation('open');
    }

    var open50Modal = function() {
        $('#open50_box').foundation('open');
    }

    var open70Modal = function() {
        $('#open70_box').foundation('open');
    }

    var openBox = function(chance, price) {
        var formData = new FormData();
        formData.append('chance', chance);
        formData.append('price', price);

        $.ajax({
            url: urlRoute.getBaseUrl() + 'usercp/boxes/open',
            type: 'post',
            data: formData,
            processData: false,
            contentType: false,
            success: function(data) {
                if(data['response'] == true) {
                    urlRoute.ohSnap(data['message'], 'green');
                    urlRoute.loadPage('usercp/boxes');
                }else {
                    urlRoute.ohSnap(data['message'], 'red');
                }
            }
        });
    }