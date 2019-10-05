 <script> urlRoute.setTitle("TH - Radio Connection Information");</script>

<div class="small-12 column">
    <div class="content-holder">
        <div class="content contentpadding">
            <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
            <span><a href="/staff" class="bold web-page">StaffCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
            <span>Radio Connection Information</span>
        </div>
    </div>
</div>

<div class="medium-4 column">
    @include('staff.menu')
</div>

<div class="medium-8 column">
    <div class="content-holder">
        <div class="content">
	<div class="contentHeader headerRed">
    Radio Connection Information
    </div>
            @if($can_access)
            <div class="small-12">
                <table class="responsive" style="width: 100%;">
                    <tr>
                        <td><b>Server Type</b></td>
                        <td>Shoutcast 1</td>
                    </tr>
                    <tr>
                        <td><b>Bitrate</b></td>
                        <td>128kbps, 44100Hz</td>
                    </tr>
                    <tr>
                        <td><b>IP</b></td>
                        <td>{{ $ip }}</td>
                    </tr>
                    <tr>
                        <td><b>Port</b></td>
                        <td>{{ $port }}</td>
                    </tr>
                    <tr>
                        <td><b>Password</b></td>
                        <td>{{ $password }}</td>
                    </tr>
                    <tr>
                        <td><b>Station Name</b></td>
                        <td>Anything - This is your stream name.</td>
                    </tr>
                    @if(Auth::check())
                    <tr>
                        <td><b>Genre</b></td>
                        <td>{{ Auth::user()->username }}</td>
                    </tr>
		            @else
                    <tr>
                        <td><b>Genre</b></td>
                        <td>Your Site Username</td>
                    </tr>
                    @endif
                </table>
            </div>
            @else
            You can't access the radio connection details, these are only visible when it's the hour before your slot or while your slot!
            @endif
        </div>
    </div>

    <div class="content-holder">
        <div class="content">
            <div class="contentHeader headerBlue">
                Sam Broadcaster
            </div>
            <div class="content-ct">
                Here's a Step By Step guide on how to add your encoders to Sam Broadcaster.<br/><br/>
                <div style="margin: 10px 10px 5px 10px;">
                    <div class="spoilerheader" style="font-weight: bold; cursor: pointer; padding: 6px; background: rgb(247, 247, 247); border-radius: 4px; border: 1px solid rgb(225, 225, 225); margin-bottom: 4px;">Click here for hidden content.</div>    <div class="spoilercontent" style="padding: 6px; background: #f7f7f7; border-radius: 4px; border: 1px solid #e1e1e1;">
                        <b>Step 1 &raquo;</b> Click "Window" on the title area of Sam Broadcaster and then click on "Encoders".<br/>
                        <b>Step 2 &raquo;</b> Whnen the window pops up, click on the Plus Button.<br/>
                        <b>Step 3 &raquo;</b> Quality: High Quality | Format: 128 kb/s, 44.1kHz, Stereo.<br/>
                        <b>Step 4 &raquo;</b> Click on Server Details and enter the information shown above.<br/>
                        <b>Step 5 &raquo;</b> Click Ok.<br/>
                        <br />
                        <i><b>Please Note:</b> You <u>MUST</u> have your Habbo name verified on the site and your stream genre need to be your Site Username.</i><br />
                        <br />
                        <center>
                        <img src="https://i.imgur.com/OduAx9k.png" style="width: 350px;" />
                        <img src="https://i.imgur.com/t0P5rDe.png" style="width: 350px;" />
                        </center>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content-holder">
        <div class="content">
            <div class="contentHeader headerBlue">
                Nicecast
            </div>
            <div class="content-ct">
                Here's a Step By Step guide on how to add your encoders to Nicecast.<br/><br/>
                <div style="margin: 10px 10px 5px 10px;">
                    <div class="spoilerheader" style="font-weight: bold; cursor: pointer; padding: 6px; background: rgb(247, 247, 247); border-radius: 4px; border: 1px solid rgb(225, 225, 225); margin-bottom: 4px;">Click here for hidden content.</div>    <div class="spoilercontent" style="padding: 6px; background: #f7f7f7; border-radius: 4px; border: 1px solid #e1e1e1;">
                        <b>Step 1 &raquo;</b> Click "Source" and select the application you will use to play music from i.e. Spotify or Itunes.<br/>
                        <b>Step 2 &raquo;</b> Click "Info" and fill in your information.<br/>
                        <b>Step 3 &raquo;</b> Click "Quality" set it to Bitrate 128 Kbps, Sample Rate 44.100 Khz and Stero.<br/>
                        <b>Step 4 &raquo;</b> Click "Window" and select "Show Server".<br/>
                        <b>Step 5 &raquo;</b> Enter the information shown above.<br/>
                        <b>Step 6 &raquo;</b> Check the box next to the encoder and then you're good to DJ!.<br/>
                        <br />
                        <i><b>Please Note:</b> You <u>MUST</u> have your Habbo name verified on the site and your stream genre need to be your Site Username.</i><br />
                        <br />
                        <center>
                        <img src="https://i.imgur.com/eGkQ276.png" style="width: 350px;" />
                        <img src="https://i.imgur.com/2Bx6Agu.png" style="width: 350px;" />
                        </center>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
