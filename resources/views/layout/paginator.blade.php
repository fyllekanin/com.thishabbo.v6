<div class="pagination">
  @if(!isset($extra))
  <?php $extra = ""; ?>
  @endif
    @if($paginator['previous_exists'])
    <a href="{{ $url }}{{ $paginator['previous'] }}{{ $extra }}" class="web-page"><button class="pg-blue headerBlue"><i class="fa fa-arrow-left" aria-hidden="true"></i> Previous</button></a>
    @else
    <button class="pg-blue headerBlue"><i class="fa fa-arrow-left" aria-hidden="true"></i>  Previous</button>
    @endif
    <div class="pg-pages">
        <ul>
            @if($paginator['gap_backward'])
                <a href="{{ $url }}1{{ $extra }}" class="web-page">
                    <li>1</li>
                </a>
                <a href="{{ $url }}2{{ $extra }}" class="web-page">
                    <li>2</li>
                </a>
                <a href="{{ $url }}3{{ $extra }}" class="web-page">
                    <li>3</li>
                </a>
                <li>...</li>
                <a href="{{ $url }}{{ $paginator['current']-2 }}{{ $extra }}" class="web-page">
                    <li>{{ $paginator['current']-2 }}</li>
                </a>
                <a href="{{ $url }}{{ $paginator['current']-1 }}{{ $extra }}" class="web-page">
                    <li>{{ $paginator['current']-1 }}</li>
                </a>
            @else
                @for($x = 1;$x < $paginator['current']; $x++)
                    <a href="{{ $url }}{{ $x }}{{ $extra }}" class="web-page">
                        <li>{{ $x }}</li>
                    </a>
                @endfor
            @endif
            <li class="pg-pages-current">{{ $paginator['current'] }}</li>
            @if($paginator['gap_forward'])
                <a href="{{ $url }}{{ $paginator['current']+1 }}{{ $extra }}" class="web-page">
                    <li>{{ $paginator['current']+1 }}</li>
                </a>
                <a href="{{ $url }}{{ $paginator['current']+2 }}{{ $extra }}" class="web-page">
                    <li>{{ $paginator['current']+2 }}</li>
                </a>
                <li>...</li>
                <a href="{{ $url }}{{ $paginator['total']-2 }}{{ $extra }}" class="web-page">
                    <li>{{ $paginator['total']-2 }}</li>
                </a>
                <a href="{{ $url }}{{ $paginator['total']-1 }}{{ $extra }}" class="web-page">
                    <li>{{ $paginator['total']-1 }}</li>
                </a>
                <a href="{{ $url }}{{ $paginator['total'] }}{{ $extra }}" class="web-page">
                    <li>{{ $paginator['total'] }}</li>
                </a>
            @else
                @for($x = $paginator['current']+1;$x <= $paginator['total']; $x++)
                <a href="{{ $url }}{{ $x }}{{ $extra }}" class="web-page">
                    <li>{{ $x }}</li>
                </a>
                @endfor
            @endif
        </ul>
    </div>
    @if($paginator['next_exists'] == 1)
        <a href="{{ $url }}{{ $paginator['next'] }}{{ $extra }}" class="web-page"><button class="pg-blue headerBlue floatright">Next <i class="fa fa-arrow-right" aria-hidden="true"></i></button></a>
    @else
        <button class="pg-blue headerBlue floatright">Next <i class="fa fa-arrow-right" aria-hidden="true"></i></button>
    @endif
</div>
