<script> 
    urlRoute.setTitle("TH - Radio Analytics");
    google.charts.load('current', {'packages':['corechart']});
</script>
  

<div class="small-12 column">
  <div class="content-holder">
    <div class="content contentpadding">
        <span>Radio Analytics</span>
    </div>
  </div>
</div>

<div class="medium-4 column">
  @include('staff.menu')
</div>

<div class="medium-8 column">
    <div class="content-holder">
    <div class="contentHeader headerRed">
        Radio Analytics
    </div>
        <div class="content-ct">
            <select id="amt-days" class="login-form-input">
                <option value="7">7 days</option>
                <option value="14">14 days</option>
                <option value="30">30 days</option>
                <option value="60">60 days</option>
                <option value="90">90 days</option>
            </select>
            <button class="pg-red fullWidth headerRed gradualfader shopbutton" style="margin-top: 0.5rem;" onclick="getAnalytics();">Get Analytics</button>
        </div>

        <div id="graph"></div>
    </div>
</div>

<script>
    var getAnalytics = function () {
        var days = $('#amt-days').val();
        $.ajax({
            url: urlRoute.baseUrl + 'xhrst/staff/manage/analytics/'+days,
            type: 'get',
            success: function (data) {

                var rawData = data['response'];
                var chartDataArray = [['Date','OC','EU','NA']];
                chartDataArray = chartDataArray.concat(
                    rawData.map( function(row) {
                        return [row[0],parseFloat(row[1]),parseFloat(row[2]),parseFloat(row[3])];
                    })
                );
                console.log(chartDataArray);
                var chartData = google.visualization.arrayToDataTable(chartDataArray);
                var chart = new google.visualization.LineChart(document.getElementById('graph'));
                var options = {
                    height: 700
                }
                chart.draw(chartData, options);
            }

        })
    };
</script>
