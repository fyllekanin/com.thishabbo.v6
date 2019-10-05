<script> urlRoute.setTitle("TH - Automated Thread");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
          <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
           <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
          <span>New Automated Thread</span>
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

                    <span>New Automated Thread</span>
                    <a href="/admincp/settings/automated" class="web-page headerLink white_link">Back</a>   
                 </div>    
      <div class="content-ct">

          <label for="at-form-title">Title</label>
          <input type="text" id="at-form-title" placeholder="Title..." class="login-form-input"/>


          <label for="at-form-forum">Forum</label>
          <select id="at-form-forum" class="login-form-input">
            @foreach($forums as $forum) 
              <option value="{{ $forum['forumid'] }}">{{ $forum['title'] }}</option>
            @endforeach
          </select>

          <br />

          <textarea id="at_editor" style="height: 150px;"></textarea>


          <label for="at-form-day">Day</label>
          <select id="at-form-day" class="login-form-input">
            <option value="1">Monday</option>
            <option value="2">Tuesday</option>
            <option value="3">Wednesday</option>
            <option value="4">Thursday</option>
            <option value="5">Friday</option>
            <option value="6">Saturday</option>
            <option value="7">Sunday</option>
          </select>


          <label for="at-form-hour">Hour</label>
          <select id="at-form-hour" class="login-form-input">
            <option value="0">12 AM</option>
            <option value="1">1 AM</option>
            <option value="2">2 AM</option>
            <option value="3">3 AM</option>
            <option value="4">4 AM</option>
            <option value="5">5 AM</option>
            <option value="6">6 AM</option>
            <option value="7">7 AM</option>
            <option value="8">8 AM</option>
            <option value="9">9 AM</option>
            <option value="10">10 AM</option>
            <option value="11">11 AM</option>
            <option value="12">12 PM</option>
            <option value="13">1 PM</option>
            <option value="14">2 PM</option>
            <option value="15">3 PM</option>
            <option value="16">4 PM</option>
            <option value="17">5 PM</option>
            <option value="18">6 PM</option>
            <option value="19">7 PM</option>
            <option value="20">8 PM</option>
            <option value="21">9 PM</option>
            <option value="22">10 PM</option>
            <option value="23">11 PM</option>
          </select>

          <label for="at-form-min">Minute</label>
          <select id="at-form-min" class="login-form-input">
            <option value="0">0</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
            <option value="10">10</option>
            <option value="11">11</option>
            <option value="12">12</option>
            <option value="13">13</option>
            <option value="14">14</option>
            <option value="15">15</option>
            <option value="16">16</option>
            <option value="17">17</option>
            <option value="18">18</option>
            <option value="19">19</option>
            <option value="20">20</option>
            <option value="21">21</option>
            <option value="22">22</option>
            <option value="23">23</option>
            <option value="24">24</option>
            <option value="25">25</option>
            <option value="26">26</option>
            <option value="27">27</option>
            <option value="28">28</option>
            <option value="29">29</option>
            <option value="30">30</option>
            <option value="31">31</option>
            <option value="32">32</option>
            <option value="33">33</option>
            <option value="34">34</option>
            <option value="35">35</option>
            <option value="36">36</option>
            <option value="37">37</option>
            <option value="38">38</option>
            <option value="39">39</option>
            <option value="40">40</option>
            <option value="41">41</option>
            <option value="42">42</option>
            <option value="43">43</option>
            <option value="44">44</option>
            <option value="45">45</option>
            <option value="46">46</option>
            <option value="47">47</option>
            <option value="48">48</option>
            <option value="49">49</option>
            <option value="50">50</option>
            <option value="51">51</option>
            <option value="52">52</option>
            <option value="53">53</option>
            <option value="54">54</option>
            <option value="55">55</option>
            <option value="56">56</option>
            <option value="57">57</option>
            <option value="58">58</option>
            <option value="59">59</option>
          </select>

          <br/>

        <button class="pg-red headerRed gradualfader fullWidth topBottom" onclick="addNewAt();">Post</button>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">
  $(document).ready(function() {
    $(document).foundation();

    $("#at_editor").wysibb();
  });

  var addNewAt = function() {
    var title = $('#at-form-title').val();
    var forum = $('#at-form-forum').val();
    var content = $('#at_editor').bbcode();
    var day = $('#at-form-day').val();
    var hour = $('#at-form-hour').val();
    var min = $('#at-form-min').val();

    $.ajax({
      url: urlRoute.getBaseUrl() + 'admincp/settings/post/automated',
      type: 'post',
      data: {title:title, forum:forum, content:content, day:day, hour:hour, min:min},
      success: function(data) {
        console.log(data);
        if(data['response'] == true) {
          urlRoute.loadPage('/admincp/settings/automated');
          urlRoute.ohSnap('Automated thread added!', 'green');
        } else {
          ohSnap(data['message'], 'red');
        }
      }
    });
  }
  var destroy = function() {
    addNewAt = null;
  }
</script>
