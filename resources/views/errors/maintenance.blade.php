<script> urlRoute.setTitle("TH - Maintenance");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content subNav">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Maintenance</span>
    </div>
  </div>
</div>

<div class="medium-8 column">
        <div class="contentHeader headerRed">
          <span>Maintenance</span>
        </div>

    <div class="content-holder">
      <div class="content">
        <div class="content-ct">
      <div class="content-ct" style="padding-top: 0; padding-bottom: 0;">
          <BR/>
          <center>
          <img src="/_assets/img/website/logosummer.png" align="center">
          </center>
          <br>
              We are currently undergoing some maintenance on ThisHabbo right now,<br/>
              Please be patient as we're currently working on something.<br/>
              <br/>
            @if(strlen($reason) > 0)
              <b>Message:</b><br />
              {!! $reason !!}<br/>
            @endif
      </div>
    </div>
  </div>
</div>
</div>

<div class="medium-4 column">
        <div class="contentHeader headerBlue">
          <span>Tweets</span>
        </div>

    <div class="content-holder">
      <div class="content">
        <div class="content-ct">
            <a class="twitter-timeline"
              href="https://twitter.com/thishabbo"
              width="100%"
              height="500">
            Tweets by @Thishabbo
            </a>
        </div>
      </div>
    </div>
</div>

<script>window.twttr = (function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0],
    t = window.twttr || {};
  if (d.getElementById(id)) return t;
  js = d.createElement(s);
  js.id = id;
  js.src = "https://platform.twitter.com/widgets.js";
  fjs.parentNode.insertBefore(js, fjs);

  t._e = [];
  t.ready = function(f) {
    t._e.push(f);
  };

  return t;
}(document, "script", "twitter-wjs"));</script>
