<script>
    urlRoute.setTitle("TH - VX progress");
</script>

<div class="medium-8 column end">
    <div class="contentHeader spacer headerBlue">
        <span>Latest patches merged</span>
    </div>
    @foreach($list as $item)
        <div class="content-holder">
            <div class="content">
                <div class="content-ct">
                    <div class="medium-12 column end">
                        <strong>{{$item['subject']}}</strong>
                        <br />
                        <br />
                        <em>- {{$item['submitted']}}</em>
                        <br />
                        <br />
                        <strong>Lines added:</strong> {{$item['insertions']}} <br />
                        <strong>Lines deleted:</strong> {{$item['deletions']}} <br />
                        <em>By {{$item['user']}}</em>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="medium-4 column end">
    <div class="contentHeader spacer headerBlue">
        <span>What is this</span>
    </div>
    <div class="content-holder">
        <div class="content">
            <div class="content-ct">
                This is just a live feed of how VX is progressing, it shows the last 25 patches that went in.
                A patch is a collection of code to fix a bug, add a feature or whatever. The subject should be clear enough
                to tell you this.
            </div>
        </div>

    </div>
</div>