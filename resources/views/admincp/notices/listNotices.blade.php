<script> urlRoute.setTitle("TH - Site Notices");</script>

<div class="small-12 column">
    <div class="content-holder">
        <div class="content contentpadding">
            <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
            <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
            <span>Manage Site Notices</span>
        </div>
    </div>
</div>

<div class="medium-4 column">
    @include('admincp.menu')
</div>

<div class="medium-8 column">
<div class="content-holder">
    <div class="content">
    <div class="contentHeader headerGreen">
    <span>Site Notices</span>
    <a href="/admincp/notices/add" class="web-page headerLink white_link">New Site Notice</a>
</div>
        <div class="content-ct">
            <div class="small-12">
                <table class="responsive" style="width: 100%;">
                    <tr>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Created By</th>
                        <th>Last Edited</th>
                        <th>Edited By</th>
                        <th>Action</th>
                    </tr>
                    @foreach($notices as $notice)
                    <tr>
                        <td>{{ $notice['title'] }}</td>
                        <td>{{ $notice['enabled'] }}</td>
                        <td>{{ $notice['created'] }}</td>
                        <td>{!! $notice['creator'] !!}</td>
                        <td>@if($notice['edited'] == '48 years ago') Never @else {{ $notice['edited'] }} @endif</td>
                        <td>@if($notice['editor'] == '__blank__') @else {!! $notice['editor'] !!} @endif</td>
                        <td><a href="/admincp/notices/edit/{{ $notice['noticeid'] }}" class="web-page"><i class="fa fa-pencil editcog4" aria-hidden="true"></i></a> <i class="fa fa-trash" aria-hidden="true" onclick="removeNotice({{ $notice['noticeid'] }});"></i></td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var removeNotice = function(noticeid) {
        if(confirm("Are you sure you want to delete this Site Notice?")) {
            $.ajax({
                url: urlRoute.getBaseUrl() + 'admincp/notices/remove',
                type: 'post',
                data: {noticeid:noticeid},
                success: function(data) {
                    urlRoute.ohSnap('Notice Successfully Deleted!', 'green');
                    urlRoute.loadPage('/admincp/notices/list');
                }
            })
        }
    }

    var destroy = function() {
        removeNotice = null;
    }
</script>
