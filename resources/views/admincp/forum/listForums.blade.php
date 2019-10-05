<?php $ForumHelper = new \App\Helpers\ForumHelper; ?>
<script> urlRoute.setTitle("TH - List Forums");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>List Forums</span>
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
                <span>Forums</span>
                <a href="/admincp/forums/add" class="web-page headerLink white_link">Add Forum</a>
            </div>
            <div class="content-ct">
                <div class="small-12">
                    <table class="responsive" style="width: 100%;">
                        <tr>
                            <th>Title</th>
                            <th style="text-align: center;">Display Order</th>
                            <th style="text-align: center;">Actions</th>
                            <th style="text-align: center;">Edit</th>
                        </tr>
                        @foreach($forums as $forum)
                            <tr class="contentHeader" style="background-color: #c66464 !important;">
                                <td>{{ $forum['title'] }}</td>
                                <td><center>{{ $forum['displayorder'] }}</center></td>
                                <td>{!! $ForumHelper::forumActions($forum['forumid']) !!}</td>
                            </tr>
                            <?php $childs = $ForumHelper::getChilds($forum['childs']);?>
                            {!! $childs !!}
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var forumAction = function(forumid) {
        var action = $('#forumid-'+forumid).val();
        switch(action) {
            case "1":
                //Edit Forum
                urlRoute.loadPage('/admincp/forums/edit/'+forumid);
            break;
            case "2":
                var r = confirm("Sure you wanna delete this forum?");
                if(r == true) {
                    $.ajax({
                        url: urlRoute.getBaseUrl() + 'admincp/forum/remove',
                        type: 'post',
                        data: {forumid:forumid},
                        success: function(data) {
                            if(data['response'] == true) {
                                urlRoute.loadPage('/admincp/forums');
                                urlRoute.ohSnap('Forum Removed!', 'green');
                            } else {
                                urlRoute.ohSnap('Something went wrong', 'red');
                            }
                        }
                    });
                }
            break;
            case "3":
            //Add Child
            urlRoute.loadPage('/admincp/forums/add?forumid=' + forumid);
            break;
        }
    }

    var destroy = function() {
        forumAction = null;
    }
</script>
