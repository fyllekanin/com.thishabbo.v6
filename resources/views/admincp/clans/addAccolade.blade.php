<script> urlRoute.setTitle("TH - Add Clan Acolade");</script>

<div class="small-12 column">
    <div class="content-holder">
        <div class="content contentpadding">
            <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
            <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
            <span>Add Clan Accolade</span>
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
                <span>Add Accolade to {{ $clan->groupname }}</span>
            </div>
            <label for="edit-form-accolade">The Acolade:</label>
            <input type="login-form-input" id="edit-form-accolade" placeholder="e.g. Senior Administrator" class="login-form-input" />
            <label for="edit-form-display">Display Prioirty (higher the number, the more up top it is):</label>
            <input type="login-form-input" id="edit-form-display" value="1" class="login-form-input" />
            <br />
            <button class="pg-red headerRed gradualfader fullWidth topBottom" onclick="addAccolade();">Add Acolade</button>
        </div>
    </div>

    <div class="content-holder">
        <div class="content">
            <div class="contentHeader headerPink">
                <span>Current Accolades</span>
            </div>
            <table class="responsive" style="width: 100%;">
                <tr>
                    <th style="width:35%">Accolade</th>
                    <th style="width:25%">Awarded By</th>
                    <th style="width:20%">Date Awarded</th>
                    <th style="width:10%">Display Priority</th>
                    <th style="width:10%">Actions</th>
                </tr>
                @foreach($current_accolades as $accolade)
                <tr>
                    <td><b>{!! $accolade['accolade'] !!}</b></td>
                    <td>{!! $accolade['awarded_by'] !!}</td>
                    <td>{{ $accolade['date'] }}</td>
                    <td>{{ $accolade['display_order'] }}</td>
                    <td style="text-align:center;">
                        <a href="/admincp/clans/accolade/edit/{{ $accolade['id'] }}" class="web-page"><i class="fa fa-pencil editcog4" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;
                        <a onclick="deleteAccolade('{{$accolade['id'] }}');"><i class="fa fa-trash editcog4" aria-hidden="true"></i></a>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    var addAccolade = function() {
        var accolade = $('#edit-form-accolade').val();
        var display = $('#edit-form-display').val();
        var groupid = "{{ $clan->groupid }}";

        $.ajax({
            url: urlRoute.getBaseUrl() + 'admincp/clans/accolade/add',
            type: 'post',
            data: {accolade:accolade, groupid:groupid, display:display},
            success: function(data) {
                if(data['response'] == true) {
                    urlRoute.loadPage('/admincp/clans/accolade/'+groupid);
                    urlRoute.ohSnap('Acolade added!', 'green');
                } else {
                    urlRoute.ohSnap('Something went wrong!', 'red');
                }
            }
        });
    }

    var deleteAccolade = function(accoladeid) {
        var groupid = "{{ $clan->groupid }}";

        $.ajax({
            url: urlRoute.getBaseUrl() + 'admincp/clans/accolade/delete',
            type: 'post',
            data: {accoladeid:accoladeid, groupid:groupid},
            success: function(data) {
                if(data['response'] == true) {
                    urlRoute.loadPage('/admincp/clans/accolade/'+groupid);
                    urlRoute.ohSnap('Acolade Removed!', 'green');
                } else {
                    urlRoute.ohSnap('Something went wrong!', 'red');
                }
            }
        });
    }

    var destroy = function() {
        addAccolade = null;
        deleteAccolade = null;
    }
</script>
