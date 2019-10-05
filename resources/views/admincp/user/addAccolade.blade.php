<script> urlRoute.setTitle("TH - Add Acolade");</script>

<div class="small-12 column">
    <div class="content-holder">
        <div class="content contentpadding">
            <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
            <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
            <span>Add Accolade</span>
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
                <span>Add Accolade to {{ $user->username }}</span>
            </div>
            <label for="edit-form-accolade">The Acolade:</label>
            <input type="login-form-input" id="edit-form-accolade" placeholder="e.g. Senior Administrator" class="login-form-input" />
            <label for="edit-form-start">Start:</label>
            <input type="login-form-input" id="edit-form-start" placeholder="e.g. Jan 2018" class="login-form-input" />
            <label for="edit-form-end">End (if award leave blank, else put Present or end Month/Year):</label>
            <input type="login-form-input" id="edit-form-end" placeholder="e.g. Present" class="login-form-input" />
            <label for="edit-form-type">Acolade Type:</label>
            <select id="edit-form-type" class="login-form-input" name="">
                <option value="award">Award</option>
                <option value="admin">Admin Role</option>
                <option value="management">Management Role</option>
                <option value="moderator">Moderator Role</option>
                <option value="veteran">Veteran Role</option>
                <option value="developer">Developer Role</option>
                <option value="audioproducer">Audio Producer Role</option>
            </select>
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
                        <a href="/admincp/users/accolade/edit/{{ $accolade['id'] }}" class="web-page"><i class="fa fa-pencil editcog4" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;
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
        var type = $('#edit-form-type').val();
        var start = $('#edit-form-start').val();
        var end = $('#edit-form-end').val();
        var userid = "{{ $user->userid }}";

        $.ajax({
            url: urlRoute.getBaseUrl() + 'admincp/user/accolade/add',
            type: 'post',
            data: {accolade:accolade, userid:userid, display:display, type:type, start:start, end:end},
            success: function(data) {
                if(data['response'] == true) {
                    urlRoute.loadPage('/admincp/users/accolade/'+userid);
                    urlRoute.ohSnap('Acolade added!', 'green');
                } else {
                    urlRoute.ohSnap('Something went wrong!', 'red');
                }
            }
        });
    }

    var deleteAccolade = function(accoladeid) {
        var userid = "{{ $user->userid }}";

        $.ajax({
            url: urlRoute.getBaseUrl() + 'admincp/user/accolade/delete',
            type: 'post',
            data: {accoladeid:accoladeid, userid:userid},
            success: function(data) {
                if(data['response'] == true) {
                    urlRoute.loadPage('/admincp/users/accolade/'+userid);
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
