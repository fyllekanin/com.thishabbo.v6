<?php 
    $transactions = \App\Helpers\ShopHelper::getLatestTransactions();
    $transfers = \App\Helpers\ShopHelper::getLatestTransfers();
?>
<div id="content-holder">
<div class="mobileShop small-4 column end">
    <div class="content-holder">
            <div class="content">
            <div class="contentHeader headerRed">
                    Latest 25 Transactions
                </div>
                <div class="content-ct">
                    @foreach($transactions as $transaction)
                        <a href="/profile/{{ $transaction['clean_username'] }}" class="sub-menu web-page bold">
                            <span><i class="fa fa-ticket" aria-hidden="true"></i> {{ $transaction['clean_username'] }}</span>
                        </a>
                        <i class="fa fa-arrow-right" aria-hidden="true"></i>  {{ $transaction['text'] }}
                        <br />
                    @endforeach
                </div>
            </div>
    </div>
</div>
<div class="mobileFunction small-4 column end">
            <div class="content-holder">
            <div class="content">
            <div class="contentHeader headerRed">
                    Latest 25 Transfers
                </div>
                <div class="content-ct">
                    @foreach($transfers as $transfer)
                        <a href="/profile/{{ $transfer['clean_username1'] }}" class="sub-menu web-page bold">
                            <span>
                                <i class="fa fa-ticket" aria-hidden="true"></i> {{ $transfer['clean_username1'] }}
                            </span>
                        </a>
                        <i class="fa fa-arrow-right" aria-hidden="true"></i>
                        <a href="/profile/{{ $transfer['clean_username2'] }}" class="sub-menu web-page bold">
                        <span> {{ $transfer['clean_username2'] }}</span></a> <em>({{ $transfer['points'] }} Credits)</em><br />
                    @endforeach
                </div>
            </div>
    </div>
</div>
