<script> urlRoute.setTitle("TH - About Us");</script>

<?php $xml = \App\Helpers\StaffHelper::getRadioStats(); ?>

<div class="medium-8 column">
    <div class="contentHeader headerRed">
      ThisHabbo - The Start
    </div>
    <div class="content-holder">
      <div class="content">
        <div class="content-ct">
          <?php echo $xml ?>
        </div>
      </div>
    </div>
</div>
