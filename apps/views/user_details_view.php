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



?>

<meta charset="utf-8"></meta>
<link rel="stylesheet" type="text/css" href="<?php echo GlobalHelper::base_path(); ?>webroot/css/style.css">
<link rel="stylesheet" type="text/css" href="<?php echo GlobalHelper::base_path(); ?>webroot/css/mystyle.css" />




<form name="new_innovation" action="<?php echo GlobalHelper::site_url_path('welcome/index'); ?>" enctype="multipart/form-data" method="post" id="new_innovation" class="uniform">
<input type="hidden" value="add" name="action" />
<input type="hidden" value="" name="id" />
<input type="hidden" value="52" name="videoOption" />
<dl class="inline">
		 <input type="hidden" value="" name="source"  />
	 <input type="hidden" value="" name="instance" />
    <dt><label for="source_type"> Type<span class="req_field">*</span></label></dt>
    <dd><select name="source_type" id="sourceType" class="required">
        	<option value="">-Select-</option>
                            <option value="34"   >Source Type</option>
                                <option value="35"   >Websearch</option>
                                <option value="36"   >Patent Search</option>
                                <option value="37"   >RSS Feed and Google Alerts</option>
                                <option value="39"   >Incubators</option>
                                <option value="40"   >Investors</option>
                                <option value="41"   >Investor Platforms</option>
                                <option value="42"   >Media</option>
                                <option value="43"   >Government/Research Institution</option>
                                <option value="44"   >Educational Institution</option>
                                <option value="45"   >Business Plan Competition</option>
                        </select>
      </dd>

    <dt><label for="source">Se<span class="req_field">*</span></label></dt>
    <dd>

        <select name="source"  id="sourceList">
        	<option value="">-Select-</option>
        </select>
    </dd>
    <dt><label for="instance">in</label></dt>
    <dd>

        <select name="instance"  id="instanceList">
        	<option value="">-Select-</option>
        </select>
    </dd>

    <dt><label for="name_of_entrepreneur">Name<span class="req_field">*</span></label></dt>
    <dd>
        <input type="text" size="40" name="name_of_entrepreneur" value="" class="input-text required"/>
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
    <dt><label for="cfncode"> cfn Code<span class="req_field">*</span></label></dt>
    <dd>
       <input type="text" size="40" name="cfncode" value="" class="input-text required number" />
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


<script type="text/javascript" src="<?php echo GlobalHelper::base_path(); ?>webroot/js/jquery.js"></script>