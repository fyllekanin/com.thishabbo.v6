<script>
    urlRoute.setTitle("TH - Megarate");
</script>

<div class="medium-12 column end">
    <div class="contentHeader spacer headerBlue">
        <span>Megarate results (top to lowest)</span>
    </div>
</div>

@foreach($items as $item)
    <div class="medium-12 column end">
        <div class="contentHeader spacer headerRed">
            <span>Posted: {{ $item['time'] }}</span>
        </div>
        <div class="content-holder">
            <div class="content">
                <div class="content-ct">
                    {!!$item['content']!!}
                </div>
            </div>
        </div>
    </div>
@endforeach