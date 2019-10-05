<script> urlRoute.setTitle("TH - Manage Event Types"); </script>
<?php $ForumHelper = new \App\Helpers\ForumHelper; ?>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Manage Event Type</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('staff.menu')
</div>
<div class="medium-8 column">
  <div class="content-holder"><div class="content">
  <div class="contentHeader headerRed">
                Manage Event Type
              </div>
            <div class="content-ct">
            <label for="event-form-title">Event</label>
            <input type="text" id="event-form-title" placeholder="Event..." class="login-form-input"/>
              <br />
                <label for="event-form-thumbnail">Thumbnail</label>
                <div class="upload_avatar">
                    <input type="file" id="event-form-thumbnail" />
                </div>
            <button id="signin-now" onclick="addEvent();" class="pg-red headerRed gradualfader fullWidth topBottom" style="margin-top: 16px;">Add Event</button>
        </div>
        </div>
    </div>

  <div class="content-holder"><div class="content">
   <div class="contentHeader headerBlue">
                Event Types
              </div>
        <div class="content-ct">
          <table class="responsive" style="width: 100%;">
            <tr>
              <th>Event</th>
              <th>Action</th>
            </tr>
            @foreach($event_types as $event_type)
              <tr id="event-{{ $event_type->typeid }}">
                <td>{{$event_type->event}}</td>
                <td>
                  <i class="fa fa-trash" aria-hidden="true" onclick="removeEvent({{ $event_type->typeid }});"></i>
                </td>
              </tr>
            @endforeach
          </table>
        </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $("#event-form-desc").wysibb();
  });

  var addEvent = function() {
      var formData = new FormData();
      formData.append('title',$('#event-form-title').val());
      if ($('#event-form-thumbnail').get(0).files.length !== 0) {
        formData.append('thumbnail', $('#event-form-thumbnail')[0].files[0]);
      }

    $.ajax({
      url: urlRoute.getBaseUrl() + 'staff/event/add',
      type: 'post',
      data: formData,
      processData: false,
      contentType: false,
      success: function(data) {
        if(data['response'] === true) {
          urlRoute.ohSnap('Event added!', 'green');
          urlRoute.loadPage('/staff/events/manage');
        } else {
          urlRoute.ohSnap(data['message'], 'red');
        }
      }
    });
  }

  var removeEvent = function(typeid) {
    if(confirm('Are you sure you wanna delete this event type?')) {
      $.ajax({
        url: urlRoute.getBaseUrl() + 'staff/event/remove',
        type: 'post',
        data: {typeid:typeid},
        success: function(data) {
          urlRoute.ohSnap('Event removed!', 'green');
          urlRoute.loadPage('/staff/events/manage');
        }
      });
    }
  }

  var destroy = function() {
    addEvent = null;
    removeEvent = null;
  }
</script>
