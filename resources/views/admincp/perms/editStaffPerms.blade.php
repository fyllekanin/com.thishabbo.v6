<?php $ForumHelper = new \App\Helpers\ForumHelper; ?>
<script> urlRoute.setTitle("TH - Staff Permissions");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/admincp" class="bold web-page">AdminCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>    
      <span>Edit Staff Permissions: {{ $group->title }}</span>
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
                      <span>Edit Staff Permissions: {{ $group->title }}</span>
                      <a href="/admincp/usergroups" class="web-page headerLink white_link">Back</a>
                  </div>
          <div class="content-ct">

            These are the new permissions for V6. <br>
            Please tick the box if the answer is a "yes". <br>
            Unticked = "no".

          </div>
        </div>
      </div>

      <div class="content-holder">
        <div class="content">
      <div class="contentHeader headerBlue">
        <span>Access Tabs + Shoutbox</span>
      </div>
          <div class="content-ct">


          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} access shoutbox?</b><br />
             <i style="font-size: 0.7rem;">Are they Moderators, Management or Administration?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="staffperm" value="4096" @if($permissions['can_use_shoutbox'] == 1) checked="" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div> 



          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} access admin tab? </b><br />
             <i style="font-size: 0.7rem;">Are they Administration or higher?</i> 
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="staffperm" value="32768" @if($permissions['can_see_admin'] == 1) checked="" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>    

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} see management tab?</b><br />
              <i style="font-size: 0.7rem;">Are they Management or higher?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="staffperm" value="32" @if($permissions['can_use_manager_stuff'] == 1) checked="" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>                     

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} access radio tab? </b><br />
              <i style="font-size: 0.7rem;">Are they a Radio DJ or higher?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="staffperm" value="2" @if($permissions['can_use_radio_stuff'] == 1) checked="" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>



          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} access events tab?</b><br />
             <i style="font-size: 0.7rem;">Are they Events Staff or higher?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="staffperm" value="1048576" @if($permissions['can_use_event_stuff'] == 1) checked="" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>  

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} access quests tab?</b> <br />
              <i style="font-size: 0.7rem;">Are they Quests Staff or higher?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="staffperm" value="128" @if($permissions['can_use_media'] == 1) checked="" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>



          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} access staff panel?</b><br />
              <i style="font-size: 0.7rem;">Are they Staff or higher? <b>All staff need this!</b></i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="staffperm" value="1" @if($permissions['can_use_staff_panel'] == 1) checked="" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>
          </div>
      </div>
  </div>


      <div class="contentHeader headerBlack">
        <span>Graphics</span>
      </div>

      <div class="content-holder">
        <div class="content">
          <div class="content-ct">


           <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} view uploaded graphics?</b><br />
              <i style="font-size: 0.7rem;">Are they Quests Staff or higher?</i>              
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="staffperm" value="512" @if($permissions['can_see_graphics'] == 1) checked="" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>   


          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} upload new graphics?</b><br />
              <i style="font-size: 0.7rem;">Are they Quests Staff or higher?</i>   
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="staffperm" value="1024" @if($permissions['can_upload_graphic'] == 1) checked="" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>



          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can delete other users graphics?</b><br />
             <i style="font-size: 0.7rem;">Are they Quests Editor or higher?</i> 
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="staffperm" value="2048" @if($permissions['can_delete_others_graphics'] == 1) checked="" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>            

        </div>
      </div>
    </div>



      <div class="contentHeader headerRed">
        <span>Management & Administration</span>
      </div>

      <div class="content-holder">
        <div class="content">
          <div class="content-ct">

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} unbook radio slots?</b><br />
              <i style="font-size: 0.7rem;">Is this usergroup Radio Management or higher?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="staffperm" value="4" @if($permissions['can_unbook_radio_slot'] == 1) checked="" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} access everyones requests?</b><br />
              <i style="font-size: 0.7rem;">Is this usergroup Radio Management or higher?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="staffperm" value="8" @if($permissions['can_see_all_requests'] == 1) checked="" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} always see connection details?</b><br />
              <i style="font-size: 0.7rem;">Is this usergroup Radio Management or higher? </i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="staffperm" value="16" @if($permissions['can_always_see_connection'] == 1) checked="" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} manage perm shows?</b><br />
              <i style="font-size: 0.7rem;">Is this usergroup Radio Management or higher?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="staffperm" value="64" @if($permissions['can_add_perm_show'] == 1) checked="" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>               

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} manage event types?</b><br />
             <i style="font-size: 0.7rem;">Is this usergroup Events Management or higher?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="staffperm" value="524288" @if($permissions['can_manage_event_types'] == 1) checked="" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} unbook other users events?</b><br />
             <i style="font-size: 0.7rem;">Is this usergroup Events Management or higher?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="staffperm" value="16777216" @if($permissions['can_unbook_event_slot'] == 1) checked="" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} manage badge articles?</b>   <br />           
              <i style="font-size: 0.7rem;">Are they Management or higher?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="staffperm" value="256" @if($permissions['can_manage_other_articles'] == 1) checked="" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>
        
          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} access jobs?</b><br />
             <i style="font-size: 0.7rem;">Are they Management or higher?</i> 
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="staffperm" value="65536" @if($permissions['can_see_jobs'] == 1) checked="" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} manage jobs (open/close applications)?</b><br />
             <i style="font-size: 0.7rem;">Are they Management or higher?</i> 
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="staffperm" value="131072" @if($permissions['can_delete_add_jobs'] == 1) checked="" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>  

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can user manage job applications?</b><br />
             <i style="font-size: 0.7rem;">Are they Management or higher?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="staffperm" value="262144" @if($permissions['can_manage_job_apps'] == 1) checked="" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can book event/radio slot for other staff members?</b><br />
              <i style="font-size: 0.7rem;">Are they Management or higher?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="staffperm" value="67108864" @if($permissions['can_book_for_others'] == 1) checked="" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>


          </div>
        </div>
      </div>

      <div class="content-holder">
        <div class="content">
      <div class="contentHeader headerRed">
        <span>Moderation & Administration</span>
      </div>
          <div class="content-ct">
          <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can {{ $group->title }} manage radio info?</b><br />
             <i style="font-size: 0.7rem;">Are they Administration or higher?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="staffperm" value="8192" @if($permissions['can_manage_radio_info'] == 1) checked="" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div> 

                    <div class="small-12 column" style="margin-bottom: 1rem;">
            <div class="small-12 medium-6 column">
              <b>Can manage {{ $group->title }} manage reported articles?</b>   <br />           
              <i style="font-size: 0.7rem;">Are they Moderators, Management or Administration?</i>
            </div>
            <div class="small-12 medium-6 column">
              <input type="checkbox" class="staffperm" value="16384" @if($permissions['can_manage_flagged_articles'] == 1) checked="" @endif />
              <label for="formodperm7">
                <span><span></span></span>
              </label>
            </div>
          </div>

<br />
          <button class="pg-red headerRed gradualfader fullWidth topBottom" onclick="saveStaffPerms();">Save</button>

        </div>
      </div>
    </div>
  </div>
</div>
<div id=""></div>

<script type="text/javascript">
  var saveStaffPerms = function() {
    var groupid = {{ $group->usergroupid }};
    var permissions = 0;
    $('input:checkbox.staffperm').each(function() {
      var value = (this.checked ? $(this).val() : 0);

      value = parseInt(value);

      permissions += value;

    });

    $.ajax({
      url: urlRoute.getBaseUrl() + 'admincp/usergroup/staffpermissions',
      type: 'post',
      data: {groupid:groupid, permissions:permissions},
      success: function(data) {
        if(data['response'] == true) {
          urlRoute.loadPage('/admincp/usergroups');
          urlRoute.ohSnap('Permissions saved!', 'green');
        } else {
          urlRoute.ohSnap('Something went wrong!', 'red');
        }
      }
    })
  }

  var destroy = function() {
    saveStaffPerms = null;
  }
</script>
