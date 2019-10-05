<script> urlRoute.setTitle("TH - Infraction Log");</script>
<?php $UserHelper = new \App\Helpers\UserHelper; ?>

<div class="small-12 column">
    <div class="content-holder">
        <div class="content contentpadding">
            <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right"
                                                                           aria-hidden="true"></i></span>
            <span><a href="/staff" class="bold web-page">StaffCP</a> <i class="fa fa-angle-double-right"
                                                                        aria-hidden="true"></i></span>
            <span>Infraction Log</span>
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
                Infraction Logs
            </div>
            <div class="content-ct">
                <div class="small-12">
                    <table class="responsive" style="width: 100%;">
                        <tr>
                            <th>Affected User</th>
                            <th>Type</th>
                            <th>Reason</th>
                            <th>Issued by</th>
                            <th>Time</th>
                            @if($UserHelper::haveAdminPerm(Auth::user()->userid, 4294967296))
                            <th>Actions</th>
                            @endif
                        </tr>
                        @foreach($logs as $log)
                        <tr>
                            <td>{!! $log['affected_user'] !!}</td>
                            <td>{!! $log['type'] !!}</td>
                            <td>{!! $log['reason'] !!}</td>
                            <td>{!! $log['action_username'] !!}</td>
                            <td>{{ $log['dateline'] }}</td>
                            @if($UserHelper::haveAdminPerm(Auth::user()->userid, 4294967296))
                            <td style="text-align: center;"><i class="fa fa-trash" aria-hidden="true"
                                                               onclick="deleteinfwarn({{ $log['id'] }})"></i></td>
                            @endif
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="content-holder">
        <div class="content">
            {!! $pagi !!}
        </div>
    </div>
</div>

<script type="text/javascript">
    var deleteinfwarn = function (id) {
        if(confirm('Are you sure you want to delete this warning or infraction?')) {
            $.ajax({
                url: urlRoute.getBaseUrl() + 'staff/mod/infractions/delete/' + id,
                type: 'delete',
                data: {id: id},
                success: function (data) {
                    if (data['response'] == true) {
                        urlRoute.ohSnap("Infraction/Warning Deleted!", 'green');
                        urlRoute.loadPage('staff/mod/infractions/page/1')
                    } else {
                        urlRoute.ohSnap(data['message'], 'red');
                    }
                }
            });
        }
    };

    var destroy = function () {
        deleteinfwarn = null;
    }
</script>
