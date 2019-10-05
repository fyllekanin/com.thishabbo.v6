<script> urlRoute.setTitle("TH - Scanned Badges");</script>
<div class="medium-8 column">
    <div class="contentHeader headerRed">
        <span>Search: {{ $badge }}</span>
    </div>
    <div class="content-holder">
        <div class="content">
            <div class="content-ct">
                <div id="badge_search_results">
                    @foreach($badges as $badge)
                        <div class="small-3 medium-2 large-1 column">
                            <div class="badge-container hover-box-info" title="<b>{{ $badge['name'] }}:</b> <i>{{ $badge['desc'] }}</i>">
                                <img id="{{ $badge['name'] }}" onerror="badgeError(this);" src="https://habboo-a.akamaihd.net/c_images/album1584/{{ $badge['name'] }}.gif" alt="badge"/>
                                @if($badge['new'])<div class="badge-new-badge" style="padding-left: 0.2rem;">New</div>@endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<div class="small-4 mobileFunction column">
    <div class="contentHeader headerBlue">
        <span>Search Badges</span><a onclick="executeSearch()" class="headerLink white_link web-page">Find Badge</a>
    </div>
    <div class="content-holder">
        <div class="content">
            <div class="content-ct ct-center">
                <input type="text" id="criteria" name="criteria" placeholder="e.g. FAN or Fansite" class="login-form-input"/>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var executeSearch = function(){
        var criteria = $('#criteria').val();
        if(criteria === ""){
        urlRoute.ohSnap('Enter criteria or user!','red');
        } else {
            var path = encodeURI('badges/'+criteria+'');
            urlRoute.loadPage(path);
        }
    };

    var badgeError = function(image) {
        image.onerror = "";
        image.src = "{{ asset('_assets/img/website/badge_error.gif') }}";
        return true;
    }

    var destroy = function() {
        executeSearch = null;
        badgeError = null;
    }
</script>
