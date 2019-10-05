<div class="post_username mobile_username">
    <a href="/profile/{{ $post['clean_username'] }}/page/1" class="web-page">{!! $post['username'] !!}</a>
</div>
<div class="post_avatar">
	<div class="post_username post_username_inside">
	    <a href="/profile/{{ $post['clean_username'] }}/page/1" class="web-page">{!! $post['username'] !!}</a>
	</div>
    <img src="{{ $post['avatar'] }}" alt="Avatar"/>
</div>
@if(count($post['userbars_html']))
    <div class="post_userbars">
        <center>
            @foreach($post['userbars_html'] as $userbar_html)
                {!! $userbar_html !!}
            @endforeach
        </center>
    </div>
@endif