<div class="top-box radio-box" style="height: 10rem; margin-top: -1.4rem;">
  <div class="radio-information">
    <div class="radio-info-box">
      <i class="fa fa-headphones" aria-hidden="true"></i> DJ: <span id="dj_name">Loading...</span> with <span id="dj_listeners">0</span> listeners
    </div>
    <div class="radio-info-box">
      <span id="dj_song">Loading...</span>
    </div>
    <div class="radio-info-box">
      <span id="dj_next_on_air">Loading...</span>
    </div>
  </div>
  <div class="radio-picture">
    <div>
      <img src="https://image.prntscr.com/image/96727f5261f544139a16453eaedc2125.png" id="dj_album"/>
      <div class="radio-picture-muted" @if(!$radio_muted) style="display:none;" @endif ><span class="radio-text-muted">Muted</span></div>
    </div>
  </div>
  <div class="top-box-bottom radio-bottom">
    <div class="radio-bottom-sound">
      <span id="play_pause_bt">
        <span class="radio-cirle cirle-orange" onclick="pausePlayer();"><i class="fa fa-pause" aria-hidden="true"></i></span>
      </span>
      <span class="radio-cirle cirle-blue" onclick="upperVolume();"><i class="fa fa-volume-up" aria-hidden="true"></i></span>

      <span class="radio-cirle cirle-blue2" onclick="lowerVolume();"><i class="fa fa-volume-down" aria-hidden="true"></i></span>

      <span class="radio-cirle cirle-pink" onclick="muteRadio();"><i class="fa fa-volume-off" aria-hidden="true"></i></span>

    </div>
    <div class="radio-bottom-inter">
      <span class="radio-cirle cirle-pink1" onclick="likeCurrentDj();"><i class="fa fa-heart" aria-hidden="true"></i></span>
      <span class="radio-cirle cirle-blue" onclick="openRequest();"><i class="fa fa-comment" aria-hidden="true"></i></span>
    </div>
  </div>
</div>