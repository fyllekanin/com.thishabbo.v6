<div class="content-holder" id="subscribed">
    <div class="content">
        <div class="contentHeader headerRed">
            <span>Subscribed Threads</span>
            @if(Auth::user()->auto_subscribe == 1)
                <a onclick="turnOffAutomatic();" class="headerLink white_link">Automatic Subscribe (Turn Off)</a>
            @else
                <a onclick="turnOnAutomatic();" class="headerLink white_link">Automatic Subscribe (Turn On)</a>
            @endif
        </div>
        <div class="small-12">
            <table class="responsive" style="width: 100%;">
                <tr>
                    <th># Nr</th>
                    <th>Thread Title</th>
                    <th>Unsubscribe</th>
                </tr>
                <tr>
                    <td>All</td>
                    <td>All Subscribed Threads</td>
                    <td><a onclick="unsubscribeall()">Unsubscribe</a>
                </tr>
                <?php $nr = 1; ?>
                @foreach($subscribedThreads as $subscribedThread)
                <tr id="threadid-{{ $subscribedThread['threadid'] }}">
                    <td>#{{ $nr }}</td>
                    <td>{{ $subscribedThread['title'] }}</td>
                    <td><a onclick="unsubscribe({{ $subscribedThread['threadid'] }});">Unsubscribe</a></td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>
