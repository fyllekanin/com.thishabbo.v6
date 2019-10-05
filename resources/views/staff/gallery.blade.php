<script> urlRoute.setTitle("TH - Gallery");</script>

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
      <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span><a href="/staff" class="bold web-page">StaffCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
      <span>Gallery</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('staff.menu')
</div>

<div class="medium-8 column">
  <div class="content-holder"><div class="content">
                <div class="contentHeader headerRed">
                Gallery
              </div>
        <div class="content-ct">
      You can search for images by tags, if you want to. To search for more specific things, just add a comma as shown in the example below for multiple tag searching.
      <br /><br />
      <b>Example:</b> habbo,desk
      <br />
      <br />
      <label for="login-form-username">Tag Search</label>
      <input type="text" id="gallery-form-tags" placeholder="Habbo,Desk" class="login-form-input" value="{{ $add_to_search }}"/>
      <br />
      <button class="pg-red fullWidth headerRed gradualfader shopbutton" onclick="searchOnTags();">Search</button>
    </div>
    </div>
  </div>


  @foreach($images as $image)
    <div id="galleryid-{{ $image['galleryid'] }}" class="content-holder gallery-image" style="min-width: 13rem; width: auto; max-width: 18rem; margin-right: 0.5rem; margin-bottom: 0.5rem;">
      <div class="gallery-info">
        {{ $image['username'] }} uploaded this {{ $image['time'] }}
        @if($can_delete_others_grahics)
          <div style="position: absolute; bottom: 0; right: 0; color: #fff; padding-bottom: 0.5rem; padding-right: 0.5rem; font-size: 0.8rem;">
            <i class="fa fa-trash-o" aria-hidden="true" onclick="deleteImage({{ $image['galleryid'] }});"></i>
          </div>
        @endif
      </div>
      <img src="{{ $image['url'] }}" alt="{{ $image['tags'] }}"/>
    </div>
  @endforeach
  <div class="content-holder">
    <div class="content">
    <div class="pagination">
        @if($paginator['previous_exists'])
           <a href="/staff/graphic/gallery/page/{{ $paginator['previous'] }}{{ $add_after }}" class="web-page"><button class="pg-blue headerBlue"><i class="fa fa-arrow-left" aria-hidden="true"></i>  Previous</button></a>
        @else
           <button class="pg-blue headerBlue"><i class="fa fa-arrow-left" aria-hidden="true"></i>  Previous</button>
        @endif
        <div class="pg-pages">
          <ul>
            @if($paginator['gap_backward'])
              <a href="/staff/graphic/gallery/page/1{{ $add_after }}" class="web-page"><li>1</li></a>
              <a href="/staff/graphic/gallery/page/2{{ $add_after }}" class="web-page"><li>2</li></a>
              <a href="/staff/graphic/gallery/page/3{{ $add_after }}" class="web-page"><li>3</li></a>
              <li>...</li>
              <a href="/staff/graphic/gallery/page/{{ $paginator['current']-2 }}{{ $add_after }}" class="web-page"><li>{{ $paginator['current']-2 }}</li></a>
              <a href="/staff/graphic/gallery/page/{{ $paginator['current']-1 }}{{ $add_after }}" class="web-page"><li>{{ $paginator['current']-1 }}</li></a>
            @else
              @for($x = 1;$x < $paginator['current']; $x++)
                <a href="/staff/graphic/gallery/page/{{ $x }}{{ $add_after }}" class="web-page"><li>{{ $x }}</li></a>
              @endfor
            @endif
            <li class="pg-pages-current">{{ $paginator['current'] }}</li>
            @if($paginator['gap_forward'])
              <li>...</li>
              <a href="/staff/graphic/gallery/page/{{ $paginator['total']-2 }}{{ $add_after }}" class="web-page"><li>{{ $paginator['total']-2 }}</li></a>
              <a href="/staff/graphic/gallery/page/{{ $paginator['total']-1 }}{{ $add_after }}" class="web-page"><li>{{ $paginator['total']-1 }}</li></a>
              <a href="/staff/graphic/gallery/page/{{ $paginator['total'] }}{{ $add_after }}" class="web-page"><li>{{ $paginator['total'] }}</li></a>
            @else
              @for($x = $paginator['current']+1;$x <= $paginator['total']; $x++)
                <a href="/staff/graphic/gallery/page/{{ $x }}{{ $add_after }}" class="web-page"><li>{{ $x }}</li></a>
              @endfor
            @endif
          </ul>
        </div>
        @if($paginator['next_exists'])
          <a href="/staff/graphic/gallery/page/{{ $paginator['next'] }}{{ $add_after }}" class="web-page"><button class="pg-blue headerBlue">Next <i class="fa fa-arrow-right" aria-hidden="true"></i></button></a>
        @else
          <button class="pg-blue headerBlue floatright">Next <i class="fa fa-arrow-right" aria-hidden="true"></i></button>
        @endif
    </div>
    </a>
  </div>
</div>

<script type="text/javascript">
  $('.gallery-image').mouseover(function () {
    $(this).find('.gallery-info').fadeIn();

    $('.gallery-image').mouseleave(function () {
      $(this).find('.gallery-info').fadeOut();
    });
  });

  var searchOnTags = function() {
    var tags = $('#gallery-form-tags').val();
    if(tags === "") {
      urlRoute.loadPage('/staff/graphic/gallery/page/1');
    } else {
      urlRoute.loadPage('/staff/graphic/gallery/page/1/search/'+tags);
    }
  }

  @if($can_delete_others_grahics)
    var deleteImage = function(galleryid) {
      $.ajax({
        url: urlRoute.getBaseUrl() + 'staff/graphic/delete/'+galleryid,
        type: 'get',
        success: function(data) {
          $('#galleryid-'+galleryid).fadeOut();
          urlRoute.ohSnap('Image Removed!','green');
        }
      });
    }
  @endif


  var destroy = function() {
    searchOnTags = null;
    deleteImage = null;
  }
</script>
