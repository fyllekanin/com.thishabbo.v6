<script> urlRoute.setTitle("TH - Edit Automated Thread");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="inner-content-holder"><div class="content">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>          
      <span>Edit Automated Thread: {{ $at->title }}</span>
    </div></div>
  </div>
</div>

<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-8 column"> 
<div class="content-holder">
  <div class="content">  
        <div class="contentHeader headerRed">

                    <span>Edit {{ $at->title }}</span>   
                 </div>   
      <div class="content-ct">
          <label for="at-form-title">Title</label>
          <input type="text" id="at-form-title" value="{{ $at->title }}" class="login-form-input"/>


          <label for="at-form-forum">Forum</label>
          <select id="at-form-forum" class="login-form-input">
            @foreach($forums as $forum) 
              <option value="{{ $forum['forumid'] }}" @if($forum['forumid'] == $at->forumid) selected="" @endif >{{ $forum['title'] }}</option>
            @endforeach
          </select>

          <textarea id="at_editor" style="height: 150px;">{!! $at->content !!}</textarea>
        </div>

          <label for="at-form-day">Day</label>
          <select id="at-form-day" class="login-form-input">
            <option value="1" @if($day == 1) selected="" @endif >Monday</option>
            <option value="2" @if($day == 2) selected="" @endif >Tuesday</option>
            <option value="3" @if($day == 3) selected="" @endif >Wednesday</option>
            <option value="4" @if($day == 4) selected="" @endif >Thursday</option>
            <option value="5" @if($day == 5) selected="" @endif >Friday</option>
            <option value="6" @if($day == 6) selected="" @endif >Saturday</option>
            <option value="7" @if($day == 7) selected="" @endif >Sunday</option>
          </select>

          <label for="at-form-hour">Hour</label>
          <select id="at-form-hour" class="login-form-input">
            <option value="0" @if($hour == 0) selected="" @endif >12 AM</option>
            <option value="1" @if($hour == 1) selected="" @endif >1 AM</option>
            <option value="2" @if($hour == 2) selected="" @endif >2 AM</option>
            <option value="3" @if($hour == 3) selected="" @endif >3 AM</option>
            <option value="4" @if($hour == 4) selected="" @endif >4 AM</option>
            <option value="5" @if($hour == 5) selected="" @endif >5 AM</option>
            <option value="6" @if($hour == 6) selected="" @endif >6 AM</option>
            <option value="7" @if($hour == 7) selected="" @endif >7 AM</option>
            <option value="8" @if($hour == 8) selected="" @endif >8 AM</option>
            <option value="9" @if($hour == 9) selected="" @endif >9 AM</option>
            <option value="10" @if($hour == 10) selected="" @endif >10 AM</option>
            <option value="11" @if($hour == 11) selected="" @endif >11 AM</option>
            <option value="12" @if($hour == 12) selected="" @endif >12 PM</option>
            <option value="13" @if($hour == 13) selected="" @endif >1 PM</option>
            <option value="14" @if($hour == 14) selected="" @endif >2 PM</option>
            <option value="15" @if($hour == 15) selected="" @endif >3 PM</option>
            <option value="16" @if($hour == 16) selected="" @endif >4 PM</option>
            <option value="17" @if($hour == 17) selected="" @endif >5 PM</option>
            <option value="18" @if($hour == 18) selected="" @endif >6 PM</option>
            <option value="19" @if($hour == 19) selected="" @endif >7 PM</option>
            <option value="20" @if($hour == 20) selected="" @endif >8 PM</option>
            <option value="21" @if($hour == 21) selected="" @endif >9 PM</option>
            <option value="22" @if($hour == 22) selected="" @endif >10 PM</option>
            <option value="23" @if($hour == 23) selected="" @endif >11 PM</option>
          </select>

          <label for="at-form-min">Minute</label>
          <select id="at-form-min" class="login-form-input">
            <option value="0" @if($min == 0) selected="" @endif >0</option>
            <option value="1" @if($min == 1) selected="" @endif >1</option>
            <option value="2" @if($min == 2) selected="" @endif >2</option>
            <option value="3" @if($min == 3) selected="" @endif >3</option>
            <option value="4" @if($min == 4) selected="" @endif >4</option>
            <option value="5" @if($min == 5) selected="" @endif >5</option>
            <option value="6" @if($min == 6) selected="" @endif >6</option>
            <option value="7" @if($min == 7) selected="" @endif >7</option>
            <option value="8" @if($min == 8) selected="" @endif >8</option>
            <option value="9" @if($min == 9) selected="" @endif >9</option>
            <option value="10" @if($min == 10) selected="" @endif >10</option>
            <option value="11" @if($min == 11) selected="" @endif >11</option>
            <option value="12" @if($min == 12) selected="" @endif >12</option>
            <option value="13" @if($min == 13) selected="" @endif >13</option>
            <option value="14" @if($min == 14) selected="" @endif >14</option>
            <option value="15" @if($min == 15) selected="" @endif >15</option>
            <option value="16" @if($min == 16) selected="" @endif >16</option>
            <option value="17" @if($min == 17) selected="" @endif >17</option>
            <option value="18" @if($min == 18) selected="" @endif >18</option>
            <option value="19" @if($min == 19) selected="" @endif >19</option>
            <option value="20" @if($min == 20) selected="" @endif >20</option>
            <option value="21" @if($min == 21) selected="" @endif >21</option>
            <option value="22" @if($min == 22) selected="" @endif >22</option>
            <option value="23" @if($min == 23) selected="" @endif >23</option>
            <option value="24" @if($min == 24) selected="" @endif >24</option>
            <option value="25" @if($min == 25) selected="" @endif >25</option>
            <option value="26" @if($min == 26) selected="" @endif >26</option>
            <option value="27" @if($min == 27) selected="" @endif >27</option>
            <option value="28" @if($min == 28) selected="" @endif >28</option>
            <option value="29" @if($min == 29) selected="" @endif >29</option>
            <option value="30" @if($min == 30) selected="" @endif >30</option>
            <option value="31" @if($min == 31) selected="" @endif >31</option>
            <option value="32" @if($min == 32) selected="" @endif >32</option>
            <option value="33" @if($min == 33) selected="" @endif >33</option>
            <option value="34" @if($min == 34) selected="" @endif >34</option>
            <option value="35" @if($min == 35) selected="" @endif >35</option>
            <option value="36" @if($min == 36) selected="" @endif >36</option>
            <option value="37" @if($min == 37) selected="" @endif >37</option>
            <option value="38" @if($min == 38) selected="" @endif >38</option>
            <option value="39" @if($min == 39) selected="" @endif >39</option>
            <option value="40" @if($min == 40) selected="" @endif >40</option>
            <option value="41" @if($min == 41) selected="" @endif >41</option>
            <option value="42" @if($min == 42) selected="" @endif >42</option>
            <option value="43" @if($min == 43) selected="" @endif >43</option>
            <option value="44" @if($min == 44) selected="" @endif >44</option>
            <option value="45" @if($min == 45) selected="" @endif >45</option>
            <option value="46" @if($min == 46) selected="" @endif >46</option>
            <option value="47" @if($min == 47) selected="" @endif >47</option>
            <option value="48" @if($min == 48) selected="" @endif >48</option>
            <option value="49" @if($min == 49) selected="" @endif >49</option>
            <option value="50" @if($min == 50) selected="" @endif >50</option>
            <option value="51" @if($min == 51) selected="" @endif >51</option>
            <option value="52" @if($min == 52) selected="" @endif >52</option>
            <option value="53" @if($min == 53) selected="" @endif >53</option>
            <option value="54" @if($min == 54) selected="" @endif >54</option>
            <option value="55" @if($min == 55) selected="" @endif >55</option>
            <option value="56" @if($min == 56) selected="" @endif >56</option>
            <option value="57" @if($min == 57) selected="" @endif >57</option>
            <option value="58" @if($min == 58) selected="" @endif >58</option>
            <option value="59" @if($min == 59) selected="" @endif >59</option>
          </select>

         <button class="pg-left pg-grey pg-right" style="margin-top: 10px;" onclick="editNewAt();">Save</button>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">
  $(document).ready(function() {
    $(document).foundation();

    $("#at_editor").wysibb();
  });

  var editNewAt = function() {
    var title = $('#at-form-title').val();
    var forum = $('#at-form-forum').val();
    var content = $('#at_editor').bbcode();
    var day = $('#at-form-day').val();
    var hour = $('#at-form-hour').val();
    var min = $('#at-form-min').val();
    var atid = {{ $at->atid }};

    $.ajax({
      url: urlRoute.getBaseUrl() + 'admincp/settings/save/automated',
      type: 'post',
      data: {title:title, forum:forum, content:content, day:day, hour:hour, min:min, atid:atid},
      success: function(data) {
        if(data['response'] == true) {
          urlRoute.loadPage('/admincp/settings/automated');
          urlRoute.ohSnap('Automated thread saved!', 'green');
        } else {
          urlRoute.ohSnap(data['message'], 'red');
        }
      }
    });
  }

  var destroy = function() {
    editNewAt = null;
  }
</script>
