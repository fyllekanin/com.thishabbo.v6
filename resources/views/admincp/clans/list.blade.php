<script> urlRoute.setTitle("TH - Clans List");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Clans</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-8 column">
                  <div class="contentHeader headerRed">
                    <span>Clans</span>
                  </div>
      <div class="content-holder">
        <div class="content">
        <div class="small-12">
          <table class="responsive" style="width: 100%;">
            <tr>
              <th>Clan Name</th>
              <th>Actions</th>
              <th>Edit</th>
            </tr>
            @foreach($clans as $clan)
              <tr>
                <td>{{ $clan->groupname }}</td>
                <td>
                  <select id="clanid-{{ $clan->groupid }}">
                    <option value="1">Accolades</option>
                  </select>
                  <td><a onclick="clanAction({{ $clan->groupid }});"><i class="fa fa-cog editcog4" aria-hidden="true"></i></a></td>
                </td>
              </tr>
            @endforeach
          </table>
        </div>
      </div></div>

  <div class="content-holder">
      <div class="content">
    {!! $pagi !!}
      </div>
  </div>

  <script type="text/javascript">
    $(document).foundation();

    var clanAction = function(clanid) {
        var action = $('#clanid-'+clanid).val();
        switch(action) {
            case "1":
            urlRoute.loadPage('/admincp/clans/accolade/'+clanid);
            break;
        }
    }

    var destroy = function() {
        clanAction = null;
    }
</script>
