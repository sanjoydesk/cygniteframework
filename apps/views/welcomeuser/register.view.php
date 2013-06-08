<?php
/*
            $excfres = 60*60*24*14;
            header("Pragma: public");
            header('Content-type: text/css');
            header('Content-type: text/html');
            header('Content-type: application/javascript');
            header("Cache-Control: maxage=".$excfres);
            header('Excfres: ' . gmdate('D, d M Y H:i:s', time()+$excfres) . ' GMT');
*/
 //$data = $this->usersmodel->query();
show($this->values);
?>

<meta charset="utf-8"></meta>

<form name="new_innovation" action="<?php echo GHelper::site_url_path('welcomeuser/dbtest'); ?>" enctype="multipart/form-data" method="post" id="new_innovation" class="uniform">
<input type="hidden" value="add" name="action" />
<input type="hidden" value="" name="id" />
<input type="hidden" value="52" name="videoOption" />
<dl class="inline">

    <dt><label for="name_of_entrepreneur">Name<span class="req_field">*</span></label></dt>
    <dd>
        <input type="text" size="40" name="name_of_author" value="" class="input-text required"/>
    </dd>
    <dt><label for="phone_no">Phone Number<span class="req_field">*</span></label></dt>
    <dd>
       <input type="text" size="40" name="phone_no" value="" class="input-text required phone" />
    </dd>
    <dt><label for="mobile_no">Mobile Number<span class="req_field">*</span></label></dt>
    <dd>
       <input type="text" size="40" name="mobile_no" value="" class="input-text required mobile" />
    </dd>
    <dt><label for="email_id">Email Address<span class="req_field">*</span></label></dt>
    <dd>
       <input type="text" size="40" name="email_id" value="" class="input-text required email" />
    </dd>
    <dt><label for="address_line1">Address Line 1<span class="req_field">*</span></label></dt>
    <dd>
       <input type="text" size="40" name="address_line1" value="" class="input-text required" />
    </dd>
    <dt><label for="select1">Address Line 2<span class="req_field">*</span></label></dt>
    <dd>
       <input type="text" size="40" name="address_line2" value="" class="input-text required" />
    </dd>
    <dt><label for="city">City<span class="req_field">*</span></label></dt>
    <dd>
       <input type="text" size="40" name="city" value="" class="input-text required" />
    </dd>
    <dt><label for="district">District<span class="req_field">*</span></label></dt>
    <dd>
       <input type="text" size="40" name="district" value="" class="input-text required" />
    </dd>
	<dt><label for="state">State<span class="req_field">*</span></label></dt>
    <dd>
       <input type="text" size="40" name="state" value="" class="input-text required" />
    </dd>
    <dt><label for="country">Country<span class="req_field">*</span></label></dt>
    <dd>
        <input type="text" size="40" name="country" value="" class="input-text required" />
    </dd>
    <dt><label for="cfncode"> Zip Code<span class="req_field">*</span></label></dt>
    <dd>
       <input type="text" size="40" name="zipcode" value="" class="input-text required number" />
    </dd>
    <dt><label for="f">Supporting document </label></dt>
    <dd>
                <input type="file" name="supporting_documents"  />
    </dd>


</dl>
<div class="clear"></div>
   <div class="buttons">
                            <input type="submit" class="button"  name="btnSubmit" value="Submit" />
                            <button type="button" class="button white" name="txtReset" value="Reset" >Reset</button>
	</div>
</form>


<script type="text/javascript" src="<?php echo GHelper::base_path(); ?>webroot/js/jquery.js"></script>