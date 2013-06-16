<?php $this->renderlayout($this->template['header']); ?>
<div class="container">
    <div class="container-body" style="margin-left:100px;">
        <?php echo $this->layoutparams['content_title'];
          $this->renderlayout($this->template['content']); ?> 
    </div>
</div>
<?php $this->renderlayout($this->template['footer']); ?>
        