<script type="text/javascript">
    var stickers = [];
    @foreach($user['stickers'] as $sticker)
        stickers.push({
            transactionid: {{ $sticker['transactionid'] }},
            stickerid: {{ $sticker['stickerid'] }},
            stickername: '{{ $sticker['stickername'] }}',
            image: '{{ $sticker['image'] }}',
            visible: {{ $sticker['visible'] }},
            top: {{ $sticker['top'] }},
            left: {{ $sticker['left'] }}
        })
    @endforeach

    $(document).ready(function () {
        stickers.forEach(function(sticker){
            if(sticker.visible == 0){
                if($('#inv-'+sticker.stickerid).length){
                    var newCount = +$('#count-'+sticker.stickerid).html() + 1;
                    $('#count-'+sticker.stickerid).html(newCount);
                } else {
                    $('#sticker-holder-box').append(`
                        <div class="small-3 column end" id="inv-`+sticker.stickerid+`" onclick="addSticker('`+sticker.stickerid+`')">
                            <div  class="sticker-box"  >
                              <div id="image-`+sticker.stickerid+`" class="sticker-thumbnail" style="background-image: url('`+sticker.image+`');">
                                  <div class="article-tags">
                                    <div id="count-`+sticker.stickerid+`" class="red-tag">1</div>
                                </div>
                              </div>

                              <div class="text-sticker">
                                  <b>`+sticker.stickername+`</b><br>
                              </div>
                          </div>
                      </div>`);


                }
            } else {
                $('#stickers-wrapper').append(`
                    <div id="sticker-`+sticker.transactionid+`" class="sticker-item" style="top: ` + +sticker.top+ `px; left: ` + sticker.left + `px;">
                        <img id="`+sticker.transactionid+`" class="stickerconfig" src="` + sticker.image + `" alt="">
                    </div>

                `);

                $.contextMenu({
                    selector: '.stickeredit',
                    callback: function(key, options) {
                        var sticker = $(options.$trigger).attr('id');
                        removeSticker(sticker);
                    },
                    items: {
                        "delete": {name: "Delete"}
                    }
                });



            }

        });
    });

    var filterStickers = function(ele) {
        var filter = $(ele).val();
        $('#sticker-holder-box').html('');
        var temp_stickers = stickers.filter(function(sticker) {
            return (sticker.stickername.substring(0, filter.length).toLowerCase() === filter.toLowerCase()) && (sticker.visible==0);
        });
        temp_stickers.forEach(function(sticker){
            if($('#inv-'+sticker.stickerid).length){
                var newCount = +$('#count-'+sticker.stickerid).html() + 1;
                $('#count-'+sticker.stickerid).html(newCount);
            } else {
                $('#sticker-holder-box').append(`
                    <div class="small-3 column end" id="inv-`+sticker.stickerid+`" onclick="addSticker('`+sticker.stickerid+`')">
                        <div  class="sticker-box"  >
                          <div id="image-`+sticker.stickerid+`" class="sticker-thumbnail" style="background-image: url('`+sticker.image+`');">
                              <div id="count-`+sticker.stickerid+`" class="articles-notavailable">1</div>
                          </div>

                          <div class="text-sticker">
                              <b>`+sticker.stickername+`</b><br>
                          </div>
                      </div>
                  </div>
                `);
            }
        });
    }

  var addSticker = function(stickerid) {
    var stickerIndex = stickers.findIndex(obj => (obj.stickerid == stickerid) && (obj.visible == 0));
    if(stickerIndex === -1){
        urlRoute.ohSnap('Problem','red');
        return;
    }

    stickers[stickerIndex].visible = 1;


    $('#stickers-wrapper').append(`
        <div id="sticker-`+stickers[stickerIndex].transactionid+`" class="draggable ui-widget-content sticker-item" style="top: ` + +stickers[stickerIndex].top+ `px; left: ` + stickers[stickerIndex].left + `px;">
            <img id="`+stickers[stickerIndex].transactionid+`" class="stickerconfig stickeredit" src="` + stickers[stickerIndex].image + `" alt="">
        </div>
    `);
    saveSticker(stickers[stickerIndex].transactionid);

    $.contextMenu({
        selector: '.stickeredit',
        callback: function(key, options) {
            var sticker = $(options.$trigger).attr('id');
            removeSticker(sticker);
        },
        items: {
            "delete": {name: "Delete"}
        }
    });

    var checkFinished = stickers.findIndex(obj => (obj.stickerid == stickerid) && (obj.visible == 0));

    if(checkFinished === -1){
        $('#inv-'+stickerid).remove();
    } else {
        var newCount = $('#count-'+stickerid).html() - 1;
        $('#count-'+stickerid).html(newCount);
    }

    $( "#sticker-"+stickers[stickerIndex].transactionid ).draggable({
        stop: function(){ saveSticker(stickers[stickerIndex].transactionid) },
        containment: "#profile"
    });
  }

  var stopEditing = function() {

          $('#edit_profile').fadeIn();
          $('#add_sticker').fadeOut();
          $('.stickerconfig').removeClass('stickeredit');
       $('#sticker_collection').fadeOut();

       $('.draggable').each(function(index) {
           $(this).draggable({
               disabled: true
           });
       });
       $('.removes').remove();
   }


  var makeStickersDraggable = function() {
      $('#edit_profile').fadeOut();
      $('#add_sticker').fadeIn();
      $('.stickerconfig').addClass('stickeredit');
       $('.sticker-item').each(function(index) {
           var transactionid = $(this).attr('id').split('-')[1];
           $(this).draggable({
               stop: function(){ saveSticker(transactionid) },
               containment: "#profile",
               disabled: false
           });
           $(this).append('<i class="fa fa-times removes" aria-hidden="true" onclick="removeSticker(' + transactionid + ');"></i>');
       });
   }

   var removeSticker = function(transactionid) {
       var stickerIndex = stickers.findIndex((obj => obj.transactionid == transactionid));

       $.ajax({
           url: urlRoute.getBaseUrl() + 'profile/stickers/hide',
           type: 'post',
           data: {transactionid:transactionid},
           success: function(data) {
               if(data['response'] == false) {
                   urlRoute.ohSnap(data['message'], 'red');
               } else {
                   $('#sticker-'+transactionid).remove();
                   stickers[stickerIndex].visible = 0;
                   if($('#inv-'+stickers[stickerIndex].stickerid).length){
                       var newCount = +$('#count-'+stickers[stickerIndex].stickerid).html() + 1;
                       $('#count-'+stickers[stickerIndex].stickerid).html(newCount);
                   } else {
                       $('#sticker-holder-box').append(`
                           <div id="inv-`+stickers[stickerIndex].stickerid+`" onclick="addSticker('`+stickers[stickerIndex].stickerid+`')" class="small-3 column end">
                             <div class="sticker-box" >
                                 <div id="image-`+stickers[stickerIndex].stickerid+`" class="sticker-thumbnail" style="background-image: url('`+stickers[stickerIndex].image+`');">
                                     <div id="count-`+stickers[stickerIndex].stickerid+`" class="articles-notavailable">1</div>
                                 </div>

                                 <div class="text-sticker">
                                     <b>`+stickers[stickerIndex].stickername+`</b><br>
                                 </div>
                             </div>
                         </div>
                        `);
                   }
               }
           }
       });
   }

   var clearStickers = function() {
       $.ajax({
           url: urlRoute.getBaseUrl() + 'profile/stickers/clear',
           type: 'post',
           success: function(data) {
               if(data['response'] == true){
                   urlRoute.ohSnap('Stickers cleared!', 'green');
                   urlRoute.loadPage('profile/{{ $user["clean_username"] }}')
               } else {
                   urlRoute.ohSnap(data[$message],'red');
               }
           }
       })
   }

   var saveSticker = function (transactionid) {
       var top = $('#sticker-'+transactionid).css('top');
       var left = $('#sticker-'+transactionid).css('left');

       $.ajax({
           url: urlRoute.getBaseUrl() + 'profile/stickers/save',
           type: 'post',
           data: {transactionid:transactionid, top:top, left:left},
           success: function(data) {
               if(data['response'] == false) {
                   urlRoute.ohSnap(data['message'], 'red');
               }
           }
       });
   }
</script>

<div class="reveal large-centered" id="sticker_collection">
    <div class="modal-header">
        <button class=".modal-header .close" data-close aria-label="Close modal" onclick="closeAdd();" type="button" style="float:right; font-size: 21px; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; outline: 0; filter: alpha(opacity=20); opacity: .2;">x</button>
        <h4>Add Sticker</h4>
    </div>

<div class="modal-body" style="height: 250px; overflow:scroll;">

        <div class="small-12 column end">
            <input id="criteria" class="login-form-input" placeholder="Looking for something?" type="text" name="criteria" onkeyup="filterStickers(this)" value="">
        </div>

        <div id="sticker-holder-box" style="max-height: 300px;">

        </div>

    <br /><br /><br />

    </div>
</div>

<script>
$("#sticker_collection").draggable();
</script>
