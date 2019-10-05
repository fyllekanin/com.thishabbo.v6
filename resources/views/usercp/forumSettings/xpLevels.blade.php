<div class="content-holder" id="postlevels">
    <div class="content">
        <div class="contentHeader headerPink">
            <span>XP Levels</span>
        </div>
        <div class="small-12">
            <table class="responsive" style="width: 100%;">
                <tr>
                    <th>Level Name</th>
                    <th>XP Required</th>
                    <th>Achieved</th>
                </tr>
                @foreach($levels as $level)
                <tr>
                    <td>{{ $level['name'] }}</td>
                    <td>{{ $level['posts'] }}</td>
                    <td>{!! $level['completed'] !!}</td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>
