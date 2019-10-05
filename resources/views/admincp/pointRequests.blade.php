<script> urlRoute.setTitle("TH - THC Requests");</script>

<div class="small-12 column">
    <div class="content-holder">
        <div class="content contentpadding">
            <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
            <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
            <span>THC Requests</span>
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
                <span>THC Requests</span>
                <a class="web-page headerLink white_link" onclick="approveAll()">Approve All</a>
            </div>
            <table class="responsive" style="width: 100%;">
                <tr>
                    <th>Username</th>
                    <th>THC</th>
                    <th>Last Logon</th>
                    <th>Reason</th>
                    <th>Requestor</th>
                    <th>Manage</th>
                </tr>
                @foreach($thcrequest as $thcr)
                <tr>
                    <td>{!! $thcr['username'] !!}</td>
                    <td>{{ $thcr['thc'] }}</td>
                    <td>{{ $thcr['timeago'] }}</td>
                    <td>{{ $thcr['reason'] }}</td>
                    <td>{!! $thcr['requestor'] !!}</td>
                    <td><a onclick="approveTHC({{ $thcr['id'] }});"><i class="fa fa-check editcog4" aria-hidden="true" style="color: #008000; font-weight: bold;"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a onclick="denyTHC({{ $thcr['id'] }});"><i class="fa fa-times editcog4" aria-hidden="true" style="color: #FF0000; font-weight: bold;"></i></a></td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    var approveAll = function() {
        if(confirm('Are you sure you want to approve all THC Request?')) {
            $.ajax({
                url: urlRoute.getBaseUrl() + 'admincp/points/requests/all/approve',
                type: 'post',
                success: function(data) {
                    if(data['response'] == true) {
                        urlRoute.loadPage('/admincp/points/requests');
                        urlRoute.ohSnap('THC Requests Approved!', 'green');
                    } else {
                        urlRoute.ohSnap(data['message'], 'red');
                    }
                }
            });
        }
    }

    var approveTHC = function(thcid) {
        if(confirm('Are you sure you want to approve this THC Request?')) {
            $.ajax({
                url: urlRoute.getBaseUrl() + 'admincp/points/requests/approve',
                type: 'post',
                data: {thcid:thcid},
                success: function(data) {
                    if(data['response'] == true) {
                        urlRoute.loadPage('/admincp/points/requests');
                        urlRoute.ohSnap('THC Request Approved!', 'green');
                    } else {
                        urlRoute.ohSnap(data['message'], 'red');
                    }
                }
            });
        }
    }

    var denyTHC = function(thcid) {
        if(confirm('Are you sure you want to deny this THC Request?')) {
            $.ajax({
                url: urlRoute.getBaseUrl() + 'admincp/points/requests/deny',
                type: 'post',
                data: {thcid:thcid},
                success: function(data) {
                    if(data['response'] == true) {
                        urlRoute.loadPage('/admincp/points/requests');
                        urlRoute.ohSnap('THC Request Denied!', 'green');
                    } else {
                        urlRoute.ohSnap(data['message'], 'red');
                    }
                }
            });
        }
    }

    var destroy = function() {
        addAdvert = null;
        deleteAd = null;
    }
</script>
