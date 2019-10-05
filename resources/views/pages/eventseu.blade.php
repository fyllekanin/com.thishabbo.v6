<script> urlRoute.setTitle("TH - About Us");</script>

<?php $last_online = \App\Helpers\ForumHelper::getOnlineUsers(); ?>
<?php $badges = \App\Helpers\ForumHelper::getLatestBages(); ?>
<script>
       $(function() {
        $.contextMenu({
            selector: '.context-menu-one',
            callback: function(key, options) {
                var m = "clicked: " + key;
                window.console && console.log(m) || alert(m);
            },
            items: {
                "edit": {name: "Edit", icon: "edit"},
                "cut": {name: "Cut", icon: "cut"},
               copy: {name: "Copy", icon: "copy"},
                "paste": {name: "Paste", icon: "paste"},
                "delete": {name: "Delete", icon: "delete"},
                "sep1": "---------",
                "quit": {name: "Quit", icon: function(){
                    return 'context-menu-icon context-menu-icon-quit';
                }}
            }
        });

        $('.context-menu-one').on('click', function(e){
            console.log('clicked', this);
        })
    });
    </script>

<div class="medium-8 column">

            <div class="contentHeader headerRed">
            Events - Europe
            </div>

    <div class="content-holder">
      <div class="content-ct">
<span class="context-menu-one btn btn-neutral">right click me</span>
<span class="context-menu-one btn btn-neutral">right click me</span>
<span class="context-menu-one btn btn-neutral">right click me</span>
<span class="context-menu-one btn btn-neutral">right click me</span>
<span class="context-menu-one btn btn-neutral">right click me</span>
<span class="context-menu-one btn btn-neutral">right click me</span>

      </div>
    </div>
</div>
</div>
<div class="small-4 column">
      <div class="contentHeader headerRed">
            Scanned Badges
        </div>
  <div class="content-holder">
    <div class="content">
      <div class="content-ct ct-center">
        <div id="list_badges">
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

  function sendApp() {
  var email = $('#job-form-email').val();
  var discord = $('#job-form-discord').val();
  var job = $('#job-form-job').val();
  var country = $('#job-form-country').val();
  var why = $('#job-form-why').val();
  var habbo = $('#job-form-habbo').val();
  $.ajax({
    url: urlRoute.getBaseUrl() + 'jobs/post',
    type: 'post',
    data: {email:email, discord:discord, job:job, country:country, why:why, habbo:habbo},
    success: function(data) {
      if(data['response'] == true) {
              urlRoute.ohSnap('Request Sent!', 'green');
                urlRoute.loadPage('/forum/thread/' + data['threadid'] + '/page/1');
      } else {
        urlRoute.ohSnap(data['message'], 'red');
      }
    }
  });
}

  var destroy = function() {
    badgeError = null;
    sendApp = null;
    }
</script>
