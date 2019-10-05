<script> urlRoute.setTitle("TH - Daily Quests");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
          <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
           <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
          <span>Manage Daily Quests</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-8 column">
          <div class="contentHeader headerRed">
                  Manage Daily Quests
            </div>
  <div class="content-holder">
      <div class="content">
          <div class="content-ct">
          <fieldset>
              <label for="bbcode-form-name">Select Type</label>
              <select class="login-form-input" id="type">
                  <option value="1">Post</option>
                  <option value="2">Quest Guide Shares</option>
                  <option value="3">Quest Guide Comments</option>
                  <option value="4">Referrals</option>
                  <option value="5">Post Likes</option>
                  <option value="6">Thread Creation</option>
                  <option value="7">Visitor Messages</option>

              </select>
              <label for="bbcode-form-name">Target Amount</label>
              <input type="number" id="target" placeholder="300" class="login-form-input" value="">
              <label for="bbcode-form-name">Text</label>
              <input type="text" id="text" placeholder="Gain 300 forum posts!" class="login-form-input" value="">
              <label for="bbcode-form-name">Prize</label>
              <select class="login-form-input" id="prize">
                  @foreach($boxes as $box)
                  <option value="{{ $box['id'] }}">{{ $box['name'] }}</option>
                  @endforeach
              </select><br>
          </fieldset>
          </div>
            <button type="button" class="pg-red headerRed gradualfader fullWidth topBottom" onclick="addQuest()">Add</button>
      </div>
</div>
</div>

<div class="medium-8 column">
            <div class="contentHeader headerPurple">
                    Daily Quests
              </div>
    <div class="content-holder">
        <div class="content">
              <div class="content-ct">
                        <table class="responsive" style="width: 100%;">
                            <tbody><tr>
                                <th>Quest</th>
                                <th>Reward</th>
                                <th>Edit</th>
                            </tr>
                  </thead>
                  <tbody id="quests">
                      @foreach($quests as $quest)
                      <tr>
                          <td>{{ $quest['text'] }}</td>
                          <td>{{ $quest['prize'] }}</td>
                          <td><a onclick="deleteQuest('{{ $quest['id'] }}')" class="fa fa-trash editcog4" aria-hidden="true"></a></td>
                      </tr>
                      @endforeach
                  </tbody>
              </table>
              </div>
          </div>
  </div>
</div>

<script>

var addQuest = function() {
    var type = $('#type').val();
    var text = $('#text').val();
    var target = $('#target').val();
    var boxid = $('#prize').val();
    $.ajax({
        url: urlRoute.getBaseUrl() + 'admincp/dailyquest/add',
        type: 'post',
        data: {type:type,text:text,target:target,boxid:boxid},
        success: function(data) {
            if(data['response'] == true) {
                urlRoute.ohSnap("Success!",'green');
                urlRoute.loadPage('admincp/dailyquest');
            } else {
                urlRoute.ohSnap(data['message'],'red');
            }
        }
    });
}

var deleteQuest = function(sid) {
    var questid = sid;

    if(confirm("Are you sure you want to delete this quest, it will also delete the active quests it corresponds to")){
        $.ajax({
            url: urlRoute.getBaseUrl() + 'admincp/dailyquest/delete',
            type: 'post',
            data: {questid:questid},
            success: function(data) {
                if(data['response'] == true) {
                    urlRoute.ohSnap("Success!",'green');
                    urlRoute.loadPage('admincp/dailyquest');
                } else {
                    urlRoute.ohSnap(data['message'],'red');
                }
            }
        });
    }



}

</script>
