<?php $this->renderLayout('@header'); ?>
<div class="container">
    <div class="container-body" style="margin-left:100px;">
        <?php 
          $this->renderLayout('@content'); ?>
    </div>
</div>
<?php
$this->renderLayout($this->footer);