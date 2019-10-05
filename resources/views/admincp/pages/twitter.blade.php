<script> urlRoute.setTitle("TH - Tweet");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
          <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
           <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
          <span>Make a Tweet</span>
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
            <span>Make a New Tweet</span>
          </div>
                  <div class="content-ct">
            <form method="POST" action="{{ route('post.tweet') }}" enctype="multipart/form-data">
              {{ csrf_field() }}
              @if(count($errors))
              <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.
                <br/>
                <ul>
                  @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
              @endif
                <label for="bbcode-form-name">Add Tweet Text</label>
                <textarea class="login-form-input" name="tweet" required="required"></textarea>
                <label for="bbcode-form-name">Add Multiple Images (Max: 4)</label>
                <input type="file" name="images[]" multiple class="form-control">
                <br>
                <button class="pg-red headerRed gradualfader fullWidth topBottom" style="float:right">Add New Tweet</button>
            </form>
          </div>
      </div>
    </div>

  <div class="content-holder">
        <div class="content">
          <div class="contentHeader headerBlue">  
            <span>Last 10 Tweets</span>
          </div>
          <div class="content-ct">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th width="50px">No</th>
                  <th>Twitter Id</th>
                  <th>Message</th>
                  <th>Images</th>
                  <th>Favorite</th>
                  <th>Retweet</th>
                </tr>
              </thead>
              <tbody>
                @if(!empty($data))
                  @foreach($data as $key => $value)
                    <tr>
                      <td>{{ ++$key }}</td>
                      <td><a href="https://twitter.com/ThisHabbo/status/{{ $value['id'] }}" target="_blank">{{ $value['id'] }}</a></td>
                      <td>{{ $value['text'] }}</td>
                      <td>
                        @if(!empty($value['extended_entities']['media']))
                          @foreach($value['extended_entities']['media'] as $v)
                            <a href="{{ $v['media_url_https'] }}" target="_blank"><img src="{{ $v['media_url_https'] }}" style="width:100px;"></a>
                          @endforeach
                        @endif
                      </td>
                      <td>{{ $value['favorite_count'] }}</td>
                      <td>{{ $value['retweet_count'] }}</td>
                    </tr>
                  @endforeach
                  @else
                  <tr>
                    <td colspan="6">There is no data to display.</td>
                  </tr>
                @endif
              </tbody>
            </table>
      </div>
    </div>
  </div>
</div>
