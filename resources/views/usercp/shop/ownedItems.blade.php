<script>urlRoute.setTitle("TH - Owned Items");</script>

<div class="small-12 column">
	<div class="content-holder">
		<div class="content contentpadding">
            <span>
                <a href="/home" class="bold web-page">Home</a>  
                <i class="fa fa-angle-double-right" aria-hidden="true"></i> 
                <span>
                    <a href="/usercp" class="bold web-page">UserCP</a>   
                    <i class="fa fa-angle-double-right" aria-hidden="true"></i> 
                    <span>
                        <a href="/usercp/shop" class="bold web-page">ThisHabboShop</a> 
                        <i class="fa fa-angle-double-right" aria-hidden="true"></i> 
                        Owned Items
                    </span>
                </span>
            </span>
        </div>
	</div>
</div>

<div class="medium-4 column">
	@include('usercp.menu')
</div>
<div class="medium-8 column end">
	<div class="content-holder">
        <div class="content">
            <div class="contentHeader headerRed">
                <span>Your Items</span>
            </div>
            <div class="small-12">
                <table class="responsive" style="width: 100%;">
                    <tbody>
                        <tr>
                            <th style="width:8%">#</th>
                            <th style="width:46%">Item</th>
                            <th>Type</th>
                        </tr>
                        <?php $nr = 1; ?>
                        @foreach($transactions as $transaction)
                        <tr>
                            <td>{{ $nr }}</td>
                            <td>{{ $transaction['item'] }}</td>
                            <td>{{ $transaction['category'] }}</td>
                        </tr>
                        <?php $nr++; ?>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>