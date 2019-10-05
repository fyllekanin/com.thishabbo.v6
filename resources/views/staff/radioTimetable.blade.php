<script> urlRoute.setTitle("TH - Radio Timetable");</script>

<div class="small-12 column">
    <div class="content-holder">
        <div class="content contentpadding">
            <span><a href="/forum" class="bold web-page">Forum Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
            <span><a href="/staff" class="bold web-page">StaffCP</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
            <span>Radio Timetable</span>
        </div>
    </div>
</div>

<?php
  $long_day = '';
  switch($day) {
    case 1:
      $long_day = 'Monday';
    break;
    case 2:
      $long_day = 'Tuesday';
    break;
    case 3:
      $long_day = 'Wednesday';
    break;
    case 4:
      $long_day = 'Thursday';
    break;
    case 5:
      $long_day = 'Friday';
    break;
    case 6:
      $long_day = 'Saturday';
    break;
    case 7:
      $long_day = 'Sunday';
    break;
  }
?>

<div class="reveal" id="book_radio" data-animation-in="fade-in" data-animation-out="fade-out" data-reveal>
    <div class="modal-header">
        <button class=".modal-header .close" data-close aria-label="Close modal" type="button" style="float:right; font-size: 21px; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; filter: alpha(opacity=20); opacity: .2;">x</button>
        <h4 class="modal-title">Book Radio Slot on {{ $long_day }} at <span id="time_to_book"></span></h4>
    </div>
    <div class="modal-body">
        @if($can_book_for_others)
            <label for="user_to_book_for">Booking for somebody else? <i>(leave empty to book for your self)</i></label>
            <input class="login-form-input" placeholder="Username..." id="user_to_book_for" />
        @endif
    </div>
    <div class="modal-footer">
        <button class="pg-red fullWidth headerRed gradualfader shopbutton" onclick="postBookSlot();">Book</button>
        <button id="close" class="pg-red fullWidth headerBlue floatright gradualfader" data-close aria-label="Close modal" type="button">Close</button>
    </div>
</div>

<div class="medium-4 column">
    @include('staff.menu')
</div>

<div class="medium-8 column">
    <div class="content-holder">
        <div class="content">
        <div class="contentHeader headerRed">
    Timetable
    </div>
            <div class="content-ct">
                <div class="pagination">
                    <div class="pg-pages" style="position:relative; width:100%;">
                        <ul>
                            <a href="/staff/radio/timetable/1" class="web-page"><li @if($day == 1) class="pg-pages-current" @endif >Mon</li></a>
                            <a href="/staff/radio/timetable/2" class="web-page"><li @if($day == 2) class="pg-pages-current" @endif >Tues</li></a>
                            <a href="/staff/radio/timetable/3" class="web-page"><li @if($day == 3) class="pg-pages-current" @endif >Wed</li></a>
                            <a href="/staff/radio/timetable/4" class="web-page"><li @if($day == 4) class="pg-pages-current" @endif >Thurs</li></a>
                            <a href="/staff/radio/timetable/5" class="web-page"><li @if($day == 5) class="pg-pages-current" @endif >Fri</li></a>
                            <a href="/staff/radio/timetable/6" class="web-page"><li @if($day == 6) class="pg-pages-current" @endif >Sat</li></a>
                            <a href="/staff/radio/timetable/7" class="web-page"><li @if($day == 7) class="pg-pages-current" @endif >Sun</li></a>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="medium-8 column">
    <div class="content-holder">
        <div class="content">
        <div class="contentHeader headerBlue">
        Radio Timetable
    </div>
            <div class="content-ct">
                <table>
                    <tbody>
                        <tr>
                            <td>
                                12 AM -
                                @if(isset($timetable[0]))
                                    {!! $timetable[0]['username'] !!}
                                @else
                                    <span class="book_slot" onclick="bookSlot(0);" style="color: #3092c3; font-weight: bold;">Book</span>
                                @endif
                            </td>
                            <td>
                                08 AM -
                                @if(isset($timetable[8]))
                                    {!! $timetable[8]['username'] !!}
                                @else
                                    <span class="book_slot" onclick="bookSlot(8);" style="color: #3092c3; font-weight: bold;">Book</span>
                                @endif
                            </td>
                            <td>
                                04 PM -
                                @if(isset($timetable[16]))
                                    {!! $timetable[16]['username'] !!}
                                @else
                                    <span class="book_slot" onclick="bookSlot(16);" style="color: #3092c3; font-weight: bold;">Book</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>
                                01 AM -
                                @if(isset($timetable[1]))
                                    {!! $timetable[1]['username'] !!}
                                @else
                                    <span class="book_slot" onclick="bookSlot(1);" style="color: #3092c3; font-weight: bold;">Book</span>
                                @endif
                            </td>
                            <td>
                                09 AM -
                                @if(isset($timetable[9]))
                                    {!! $timetable[9]['username'] !!}
                                @else
                                    <span class="book_slot" onclick="bookSlot(9);" style="color: #3092c3; font-weight: bold;">Book</span>
                                @endif
                            </td>
                            <td>
                            05 PM -
                                @if(isset($timetable[17]))
                                    {!! $timetable[17]['username'] !!}
                                @else
                                    <span class="book_slot" onclick="bookSlot(17);" style="color: #3092c3; font-weight: bold;">Book</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>
                                02 AM -
                                @if(isset($timetable[2]))
                                    {!! $timetable[2]['username'] !!}
                                @else
                                    <span class="book_slot" onclick="bookSlot(2);" style="color: #3092c3; font-weight: bold;">Book</span>
                                @endif
                            </td>
                            <td>
                                10 AM -
                                @if(isset($timetable[10]))
                                    {!! $timetable[10]['username'] !!}
                                @else
                                    <span class="book_slot" onclick="bookSlot(10);" style="color: #3092c3; font-weight: bold;">Book</span>
                                @endif
                            </td>
                            <td>
                                06 PM -
                                @if(isset($timetable[18]))
                                    {!! $timetable[18]['username'] !!}
                                @else
                                    <span class="book_slot" onclick="bookSlot(18);" style="color: #3092c3; font-weight: bold;">Book</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>
                                03 AM -
                                @if(isset($timetable[3]))
                                    {!! $timetable[3]['username'] !!}
                                @else
                                    <span class="book_slot" onclick="bookSlot(3);" style="color: #3092c3; font-weight: bold;">Book</span>
                                @endif
                            </td>
                            <td>
                                11 AM -
                                @if(isset($timetable[11]))
                                    {!! $timetable[11]['username'] !!}
                                @else
                                    <span class="book_slot" onclick="bookSlot(11);" style="color: #3092c3; font-weight: bold;">Book</span>
                                @endif
                            </td>
                            <td>
                                07 PM -
                                @if(isset($timetable[19]))
                                    {!! $timetable[19]['username'] !!}
                                @else
                                    <span class="book_slot" onclick="bookSlot(19);" style="color: #3092c3; font-weight: bold;">Book</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>
                                04 AM -
                                @if(isset($timetable[4]))
                                    {!! $timetable[4]['username'] !!}
                                @else
                                    <span class="book_slot" onclick="bookSlot(4);" style="color: #3092c3; font-weight: bold;">Book</span>
                                @endif
                            </td>
                            <td>
                                12 PM -
                                @if(isset($timetable[12]))
                                    {!! $timetable[12]['username'] !!}
                                @else
                                    <span class="book_slot" onclick="bookSlot(12);" style="color: #3092c3; font-weight: bold;">Book</span>
                                @endif
                            </td>
                            <td>
                                08 PM -
                                @if(isset($timetable[20]))
                                    {!! $timetable[20]['username'] !!}
                                @else
                                    <span class="book_slot" onclick="bookSlot(20);" style="color: #3092c3; font-weight: bold;">Book</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>
                                05 AM -
                                @if(isset($timetable[5]))
                                    {!! $timetable[5]['username'] !!}
                                @else
                                    <span class="book_slot" onclick="bookSlot(5);" style="color: #3092c3; font-weight: bold;">Book</span>
                                @endif
                            </td>
                            <td>
                                01 PM -
                                @if(isset($timetable[13]))
                                    {!! $timetable[13]['username'] !!}
                                @else
                                    <span class="book_slot" onclick="bookSlot(13);" style="color: #3092c3; font-weight: bold;">Book</span>
                                @endif
                            </td>
                            <td>
                                09 PM -
                                @if(isset($timetable[21]))
                                    {!! $timetable[21]['username'] !!}
                                @else
                                    <span class="book_slot" onclick="bookSlot(21);" style="color: #3092c3; font-weight: bold;">Book</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>
                                06 AM -
                                @if(isset($timetable[6]))
                                    {!! $timetable[6]['username'] !!}
                                @else
                                    <span class="book_slot" onclick="bookSlot(6);" style="color: #3092c3; font-weight: bold;">Book</span>
                                @endif
                            </td>
                            <td>
                                02 PM -
                                @if(isset($timetable[14]))
                                    {!! $timetable[14]['username'] !!}
                                @else
                                    <span class="book_slot" onclick="bookSlot(14);" style="color: #3092c3; font-weight: bold;">Book</span>
                                @endif
                            </td>
                            <td>
                            10 PM -
                            @if(isset($timetable[22]))
                            {!! $timetable[22]['username'] !!}
                            @else
                            <span class="book_slot" onclick="bookSlot(22);" style="color: #3092c3; font-weight: bold;">Book</span>
                            @endif
                            </td>
                        </tr>
                        <tr>
                            <td>
                                07 AM -
                                @if(isset($timetable[7]))
                                    {!! $timetable[7]['username'] !!}
                                @else
                                    <span class="book_slot" onclick="bookSlot(7);" style="color: #3092c3; font-weight: bold;">Book</span>
                                @endif
                            </td>
                            <td>
                                03 PM -
                                @if(isset($timetable[15]))
                                    {!! $timetable[15]['username'] !!}
                                @else
                                    <span class="book_slot" onclick="bookSlot(15);" style="color: #3092c3; font-weight: bold;">Book</span>
                                @endif
                            </td>
                            <td>
                            11 PM -
                            @if(isset($timetable[23]))
                            {!! $timetable[23]['username'] !!}
                            @else
                            <span class="book_slot" onclick="bookSlot(23);" style="color: #3092c3; font-weight: bold;">Book</span>
                            @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).foundation();
    var day = {{ $day }};
    var time = 0;

    var bookSlot = function(ti) {
        time = ti;
        var ts = 0;
        switch(ti) {
            case 0:
                ts = '12 AM';
            break;
            case 13:
                ts = '01 PM';
            break;
            case 14:
                ts = '02 PM';
            break;
            case 15:
                ts = '03 PM';
            break;
            case 16:
                ts = '04 PM';
            break;
            case 17:
                ts = '05 PM';
            break;
            case 18:
                ts = '06 PM';
            break;
            case 19:
                ts = '07 PM';
            break;
            case 20:
                ts = '08 PM';
            break;
            case 21:
                ts = '09 PM';
            break;
            case 22:
                ts = '10 PM';
            break;
            case 23:
                ts = '11 PM';
            break;
            default:
            if(ti > 0 && ti < 10) {
                ts = '0' + ti + ' AM';
            } else {
                ts = ti + ' AM';
            }
            break;
        }
        $('#time_to_book').html(ts);
        $('#book_radio').foundation('open');
    }

    @if($can_book_for_others)
        var postBookSlot = function() {
            var user = $('#user_to_book_for').val();
            $.ajax({
                url: urlRoute.getBaseUrl() + 'staff/radio/book',
                type: 'post',
                data: {time:time, day:day, user:user},
                success: function(data) {
                    if(data['response'] == true) {
                        urlRoute.loadPage('/staff/radio/timetable/'+day);
                        urlRoute.ohSnap('Slot booked successfully!', 'green');
                    } else {
                        urlRoute.ohSnap(data['message'],'red');
                    }
                }
            });
        }
    @else
        var postBookSlot = function() {
            $.ajax({
                url: urlRoute.getBaseUrl() + 'staff/radio/book',
                type: 'post',
                data: {time:time, day:day},
                success: function(data) {
                    if(data['response'] == true) {
                        urlRoute.loadPage('/staff/radio/timetable/'+day);
                        urlRoute.ohSnap('Slot booked successfully!', 'green');
                    } else {
                        urlRoute.ohSnap(data['message'],'red');
                    }
                }
            });
        }
    @endif

    var unbookslot = function(timetableid) {
        var day = {{ $day }};
        if(confirm('You sure you wanna unbook this slot?')) {
            $.ajax({
            url: urlRoute.getBaseUrl() + 'staff/radio/unbook',
            type: 'post',
            data: {timetableid:timetableid},
                success: function(data) {
                    if(data['response'] == true) {
                        urlRoute.loadPage('/staff/radio/timetable/'+day);
                        urlRoute.ohSnap('Slot unbooked successfully!', 'green');
                    } else {
                        urlRoute.ohSnap(data['message'], 'red');
                    }
                }
            });
        }
    }

    var destroy = function() {
        bookSlot = null;
        postBookSlot = null;
        unbookslot = null;
    }
</script>
