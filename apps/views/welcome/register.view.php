<?php
use Cygnite\Libraries\Forms;
use Cygnite\Helpers\Url;

$form = Forms::getInstance(
    "registration_form",
    function ($instance) {
            echo $instance->open(
                array(
                    'method' => 'post',
                    'action' => Url::sitePath('welcomeuser/index'),
                    'id' => '',
                    'class' => 'uniform',
                    'enctype' => 'multipart/form-data'
                )
            );

        return $instance;
    }
);

?>
<input type="hidden" value="add" name="action" />
<input type="hidden" value="" name="id" />
<input type="hidden" value="52" name="videoOption" />
<dl class="inline">

    <dt>
               <?php echo $form->label()->value('Username')->class('req_field'); ?><span class="req_field">*</span>
    </dt>
    <dd>
               <?php echo $form->input("name_of_author",array("type"=>"text"))->class("input-text","required")->id("name")->size(40); ?>
    </dd>
    <dt>
                  <?php echo $form->label()->value('Email Address'); ?><span class="req_field">*</span>
   </dt>
    <dd>
                <?php echo $form->input("email_id",array("type"=>"text"))->class("input-text","required",'email')->id("email_id")->size(40); ?>
    </dd>
     <dt>
                <?php echo $form->label()->value('Mobile Number'); ?><span class="req_field">*</span>
    </dt>
    <dd>
                 <?php echo $form->input("mobile_no",array("type"=>"text"))->class("input-text","required",'mobile')->id("mobile_no")->size(40); ?>
    </dd>
    <dt>
                <?php echo $form->label()->value('Address Line '); ?><span class="req_field">*</span>
    </dt>
    <dd>
                 <?php echo $form->input("address",array("type"=>"text"))->class("input-text","required")->id("address")->size(40); ?>
    </dd>

     <dt>
                <?php echo $form->label()->value('Country'); ?><span class="req_field">*</span>
    </dt>
    <dd>
                <?php echo $form->input("country",array("type"=>"text"))->class("input-text","required")->id("country")->size(40); ?>
    </dd>
     <dt>
                <?php echo $form->label()->value('City'); ?><span class="req_field">*</span>
   </dt>
    <dd>
                <?php echo $form->input("city",array("type"=>"text"))->class("input-text","required")->id("city")->size(40); ?>
    </dd>
    <dt>
                <?php echo $form->label()->value('State'); ?><span class="req_field">*</span>
    </dt>
    <dd>
                <?php echo $form->input("city",array("type"=>"text"))->class("input-text","required")->id("city")->size(40); ?>
    </dd>
    <dt>

            <?php echo $form->label()->value('Zip Code'); ?><span class="req_field">*</span>
   </dt>
    <dd>
              <?php echo $form->input("zipcode",array("type"=>"text"))->class("input-text","required",'number')->id("zipcode")->size(40); ?>
    </dd>
    <dt>

                <?php echo $form->label()->value('Document'); ?>
   </dt>
    <dd>
                <?php echo $form->input("supporting_documents",array("type"=>"file")); ?>
    </dd>


</dl>
<div class="clear"></div>
   <div class="buttons">
    <?php
    echo $form->input("btnSubmit", array("type" => "submit"))->value('Submit')->class("button");
    echo $form->input("txtReset", array("type" => "button"))->value('Reset')->class("button", 'white');
    ?>
  </div>
<?php   echo $form->close(); ?>
<script type="text/javascript" src="<?php echo Url::basepath(); ?>webroot/js/cygnite/jquery.js"></script>