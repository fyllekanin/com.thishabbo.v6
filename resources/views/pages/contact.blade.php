<script> urlRoute.setTitle("TH - Contact Us");</script>

<?php $last_online = \App\Helpers\ForumHelper::getOnlineUsers(); ?>
<?php $badges = \App\Helpers\ForumHelper::getLatestBages(); ?>

<div class="medium-8 column">
    <div class="content-holder">
        <div class="content">
            <div class="contentHeader headerRed">
                Contact ThisHabbo
            </div>
          <div class="content-ct">
            <strong>ThisHabbo</strong> has remained strong for the last 8 years, but to continue, we'll need <strong>your</strong> opinions. It's clear that we're just not good enough in some areas, and your opinions matter.
            <br /><br />
            Get in touch with us through here, no matter what your problem. We will get in touch under 24 hours - that's our gurantee.
          </div>

          <div class="content-ct">
              @if(Auth::check())
              <fieldset>
                <div class="row">
                  <div class="small-12 medium-6 column">
                    <label for="contact-form-username">Forum Name</label>
                    <input type="text" id="contact-form-username" value="{{ Auth::user()->username }}" disabled="disabled" class="login-form-input"/>
                  </div>
                  <div class="small-12 medium-6 column">
                    <label for="contact-form-habbo">Habbo Username</label>
                    <input type="text" id="contact-form-habbo" placeholder="Habbo.com Username" class="login-form-input"/>
                  </div>
                  <div class="small-12 medium-6 column">
                    <label for="contact-form-reason">Contact Reason</label>
                    <select id="contact-form-reason" class="login-form-input">
                      <option value="Fansite Partnership">Fansite Partnership</option>
                      <option value="Account Issues">Account Related Issues</option>
                      <option value="Events Problem">Problem in/with an Events</option>
                      <option value="Radio Problem">Problem with/on the Radio</option>
                      <option value="Spelling Error">Spelling Error / Typo</option>
                      <option value="Other" selected="">Other - my contact reason doesn't match the above.</option>
                    </select>
                  </div>
                  <div class="small-12 column">
                    <label for="contact-form-why">Explain the problem in detail!</label>
                    <textarea id="contact-form-why" class="login-form-input"></textarea>
                  </div>
                  <div class="small-12 column"><br>
                    <button class="pg-red headerRed gradualfader fullWidth topBottom" onclick="sendContact();">Submit <i class="fa fa-check" aria-hidden="true"></i></button>
                  </div>
                </div>
              </fieldset>
            @else
            <br>
            <div class="alert alert-danger">
              <p><b>You must be logged in to submit a contact submission!</b></p>
            </div><br>
            <a href="/login" class="web-page"> <button id="signin-now" class="pg-red headerRed gradualfader fullWidth topBottom">Sign in now</button></a>
            @endif
            </div>
        </div>
    </div>
</div>
<div class="small-4 mobileFunction column">
    <div class="content-holder">
        <div class="content">
          <div class="contentHeader headerRed">
            Scanned Badges
          </div>
          <div class="content-ct ct-center">
            <div class="row" id="list_badges">
                @foreach($badges as $badge)
                <div class="small-2 column">
                  <div class="badge-container hover-box-info" title="<b>{{ $badge['name'] }}:</b> <i>{{ $badge['desc'] }}</i>">
                    <img onerror="badgeError(this);" src="https://habboo-a.akamaihd.net/c_images/album1584/{{ $badge['name'] }}.gif" alt="badge" />
                    @if($badge['new'])<div class="badge-new-badge">New</div>@endif
                  </div>
                 </div>
                @endforeach
            </div>
          </div>
    </div>
</div>

<script type="text/javascript">
    var badgeError = function(image) {
        image.onerror = "";
        image.src = '{{ asset('_assets/img/website/badge_error.gif') }}';
        return true;
    };

    function sendContact() {
    var habbo = $('#contact-form-habbo').val();
    var email = $('#contact-form-email').val();
    var reason = $('#contact-form-reason').val();
    var why = $('#contact-form-why').val();
    $.ajax({
        url: urlRoute.getBaseUrl() + 'contact/post',
        type: 'post',
        data: {habbo:habbo, email:email, reason:reason, why:why},
        success: function(data) {
            if(data['response'] == true) {
                urlRoute.ohSnap('Your Contact Us message has been sent!', 'green');
                urlRoute.loadPage('/forum/thread/' + data['threadid'] + '/page/1');
            } else {
                urlRoute.ohSnap(data['message'], 'red');
            }
        }
    });
}

    var destroy = function() {
        badgeError = null;
        sendContact = null;
    }
</script>
