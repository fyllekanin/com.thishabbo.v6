<!DOCTYPE html>
<html>
<head>
  <style type="text/css">
    body {
      background: url({{ asset('_images/bg.png') }});
    
    }

    .className{
      width:500px;
      height:75px;
      position:absolute;
      left:45%;
      top:40%;
      margin:-75px 0 0 -135px;
    }
    .flyout {
      width: 500px;
      min-height: 110px;
      margin-top: 10px;
      font-size: 11px;
      color:#959595;
      position: relative;
      font-family: 'Lucida Grande', Tahoma, Verdana, Arial, sans-serif;
      background-color: white;
      padding: 9px 11px;
      background: rgba(255, 255, 255, 0.9);
      -webkit-box-shadow: 0 3px 8px rgba(0, 0, 0, .15);
      -moz-box-shadow: 0 3px 8px rgba(0, 0, 0, .15);
      box-shadow: 0 3px 8px rgba(0, 0, 0, .15);
      -webkit-border-radius: 3px;
      -moz-border-radius: 3px;
      border-radius: 6px;
      }
       
      .flyout #tip {
      background-image: url({{ asset('_images/tip.png') }});
      background-repeat: no-repeat;
      background-size: auto;
      height: 11px;
      position: absolute;
      top: -11px;
      left: 25px;
      width: 20px;
      }
       
      .flyout h2 {
      text-transform: uppercase;
      color: #666;
      font-size: 1.2em; 
      padding-bottom: 5px;
      margin-bottom: 12px;
      border-bottom: 1px solid #dcdbda;
      }
      .flyout p { padding-bo Gttoem: 25px; font-size: 1.1em; color: #222; }
  </style>
</head>
<body>

<div class="className">
  <div class="flyout">
      <center><span style="color: red; font-size: 2rem;font-weight:bold;">Unfortunately.. it failed.</span></center>
      <br />
      Yeah, something failed, and we're not sure what.
      <br /><br />
      <i>If you believe something is wrong, simply create a thread in Ask the Staff and we'll try troubleshoot. Please check that your Paypal/Bank funds before doing so.</i>
      <br /><br />
      <b>Automatically redirecting you back to ThisHabbo in 20 seconds!</b>
      <meta http-equiv="refresh" content="20;URL=/usercp/credits">
  </div>
</div>
</body>
</html>