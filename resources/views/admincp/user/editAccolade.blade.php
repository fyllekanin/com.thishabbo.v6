<script> urlRoute.setTitle("TH - Edit Acolade");</script>

<div class="small-12 column">
    <div class="content-holder">
        <div class="content contentpadding">
            <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
            <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
            <span>Edit Accolade</span>
        </div>
    </div>
</div>

<div class="medium-4 column">
    @include('admincp.menu')
</div>

<div class="medium-8 column">
    <div class="content-holder">
        <div class="content">
            <div class="contentHeader headerRed">
                <span>Edit Accolade for {{ $username }}</span>
            </div>
            <label for="edit-form-accolade">The Acolade:</label>
            <input type="login-form-input" id="edit-form-accolade" value="{{ $accolade }}" class="login-form-input" />
            <label for="edit-form-display">Display Prioirty (higher the number, the more up top it is):</label>
            <input type="login-form-input" id="edit-form-display" value="{{ $display_order }}" class="login-form-input" />
            <br />
            <button class="pg-red headerRed gradualfader fullWidth topBottom" onclick="editAccolade();">Edit Acolade</button>
        </div>
    </div>
</div>

<script type="text/javascript">
    var editAccolade = function() {
        var accolade = $('#edit-form-accolade').val();
        var display = $('#edit-form-display').val();
        var accoladeid = {{ $id }};
        var userid = {{ $userid }};

        $.ajax({
            url: urlRoute.getBaseUrl() + 'admincp/user/accolade/edit',
            type: 'post',
            data: {accoladeid:accoladeid, display:display, accolade:accolade, userid:userid},
            success: function(data) {
                if(data['response'] == true) {
                    urlRoute.loadPage('/admincp/users/accolade/'+userid);
                    urlRoute.ohSnap('Acolade edited!', 'green');
                } else {
                    urlRoute.ohSnap('Something went wrong!', 'red');
                }
            }
        });
    }

    var destroy = function() {
        editAccolade = null;
    }
</script>
