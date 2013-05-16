
<div style="font-family:Lucida Grande,Verdana,Sans-serif;height:210px; font-size:12px;padding: 20px 20px 12px 20px;margin:40px; background:#fff; border:1px solid #D3640F;" align="center">
    <span style="color:brown;font-weight:bold;font-size:20;">
        <?php
        echo "Version ".CF_VERSION." Alpha<br>";
        echo "Welcome to Cygnite Framework";
        ?>
    </span> <br>
    <?php //var_dump($userdetails);
    ?>
    <span  style="font-weight:bold;margin-top:15px;"> <?php echo "This is Alpha version. Soon you can enjoy all its features and simplicity."; ?></span>

</div>

    <div style="font-family:Lucida Grande,Verdana,Sans-serif;height:112px; font-size:12px;margin:40px; background:#fff; border:1px solid #D3640F;">
        <?php echo GlobalHelper::copyright(); ?>
    </div>

    <?php echo $errors;  ?>
<div style="margin:40px; ">
<form method="post" action="<?php echo  GlobalHelper::site_url_path('welcome/index'); ?>" >
    <input type="text"  name="name" />
    <input type="text"  name="country" />
    <input type="text"  name="email" />
    <input type="submit" name="txtSubmit" value="Submit"/>

</form>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js">  </script>