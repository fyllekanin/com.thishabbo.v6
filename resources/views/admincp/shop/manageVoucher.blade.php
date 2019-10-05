<script> urlRoute.setTitle("TH - Manage Vouchers");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>    
      <span>Manage Voucher Codes</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('admincp.menu')
</div>
<div class="medium-8 column">
                  <div class="contentHeader headerRed">
                    <span>Manage Voucher Codes</span>
                  </div>
      <div class="content-holder">
        <div class="content">
          <div class="content-ct">
          <label for="voucher-add-worth">How much THC is this code worth?</label>
          <input type="number" id="voucher-add-worth" placeholder="100" class="login-form-input"/>
        </div>
        <br>
          <button class="pg-red headerRed gradualfader fullWidth topBottom" onclick="createNewCoder();">Create Code</button>
        </div>
      </div>
    </div>
  </div>
</div>

 <div class="medium-8 column">  
                   <div class="contentHeader headerBlue">
                    <span>Unused Voucher Codes</span>
                  </div>               
    <div class="content-holder">
        <div class="content">
              <div class="content-ct">
              <table class="responsive" style="width: 100%;">
            <tr>
              <th>Code</th>
              <th>Worth</th>
              <th>Created by</th>
              <th>Created at</th>
              <th>Edit</th>
            </tr>
            @foreach($unused_vouchers as $unused_voucher)
              <tr id="vouchercode-{{ $unused_voucher['voucherid'] }}">
                <td>{{ $unused_voucher['code'] }}</td>
                <td>{{ $unused_voucher['worth'] }}</td>
                <td>{{ $unused_voucher['username'] }}</td>
                <td>{{ $unused_voucher['dateline'] }}</td>
                <td>

                <i class="fa fa-trash" aria-hidden="true" onclick="deleteCode({{ $unused_voucher['voucherid'] }});"></i>

              </tr>
            @endforeach
          </table>
      </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  var runned = 0;
  var createNewCoder = function() {
    var worth = $('#voucher-add-worth').val();
    if(runned === 0) {
      runned = 1;
      $.ajax({
        url: urlRoute.getBaseUrl() + 'admincp/voucher/add',
        type: 'post',
        data: {worth:worth},
        success: function(data) {
          urlRoute.ohSnap('New code added!', 'green');
          urlRoute.loadPage('/admincp/manage/voucher');
        }
      });
    } else {
      urlRoute.ohSnap('Can\'t create new codes to quick!', 'red');
    }
  }

  var deleteCode = function(voucherid) {
    if(confirm("Sure you wanna remove this code?")) {
      $.ajax({
        url: urlRoute.getBaseUrl() + 'admincp/voucher/remove',
        type: 'post',
        data: {voucherid:voucherid},
        success: function(data) {
          urlRoute.ohSnap('Code removed!', 'green');
          $('#vouchercode-'+voucherid).fadeOut();
        }
      });
    }
  }

  var destroy = function() {
    createNewCoder = null;
    deleteCode = null;
  }
</script>