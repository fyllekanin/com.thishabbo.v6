<script> urlRoute.setTitle("TH - Edit Site Notice");</script>

<div class="small-12 column">
    <div class="content-holder">
        <div class="content contentpadding">
            <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
            <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
            <span><a href="/admincp/notices/list" class="bold web-page">Manage Site Notices</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
            <span>Edit Site Notices</span>
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
    <span>Edit Site Notices</span>
</div>
        <div class="content-ct">
            <div class="small-12">
                <label for="edit-form-title">Notice Title:</label>
                <input type="text" id="edit-form-title" value="{{ $title }}" class="login-form-input" />

                <label for="edit-form-type">Notice Type:</label>
                <select id="edit-form-type" class="login-form-input">
                    <option value="danger" @if($type == 'danger') selected="" @endif>Warning (Red)</option>
                    <option value="warning" @if($type == 'warning') selected="" @endif>Alert (Orange)</option>
                    <option value="info" @if($type == 'info') selected="" @endif>Information (Blue)</option>
                </select>
                
                <label for="edit-form-content">Notice Content:</label>
                <textarea class="editor" id="notice_editor" style="height: 150px; font-size:12px !important;">{!! $body !!}</textarea>

                <label for="edit-form-expiry">Notice Expiry: <i>Leave blank to never expire!</i></label>
                <input type="date" id="edit-form-expiry" value="{{ $expiry > 0 ? date('Y-m-d', $expiry) : '' }}" class="login-form-input" />
                
                <label for="edit-form-visibility">Visibility:</label>
                <select id="edit-form-visibility" class="login-form-input">
                    <option value="0" @if($enabled == 0) selected="" @endif>Disabled (Invisible)</option>
                    <option value="1" @if($enabled == 1) selected="" @endif>Enabled (Visible)</option>
                </select>
                <br />
                <br />
                <button class="pg-red headerRed gradualfader fullWidth topBottom" onclick="addNewNotice();">Save Notice</button>
            </div>
        </div>
    </div>
</div>

<script>
    $('#notice_editor').wysibb();

    var addNewNotice = function() {
        var formData = new FormData();
        formData.append('title', $('#edit-form-title').val());
        formData.append('type', $('#edit-form-type').val());
        formData.append('body', $('#notice_editor').bbcode());
        formData.append('expiry', $('#edit-form-expiry').val());
        formData.append('visibility', $('#edit-form-visibility').val());
        formData.append('noticeid', {{ $noticeid }});

        $.ajax({
            url: urlRoute.getBaseUrl() + 'admincp/notices/edit',
            type: 'post',
            data: formData,
            processData: false,
            contentType: false,
            success: function(data) {
                if(data['response'] == true) {
                    urlRoute.loadPage('/admincp/notices/list');
                    urlRoute.ohSnap('Notice Edited!', 'green');
                } else {
                    $('#'+data['field']).addClass('form-reg-error');
                    urlRoute.ohSnap(data['message'], 'red');
                }
            }
        });
    }

  var destroy = function() {
    addNewNotice = null;
  }
</script>
