<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
<title>Welcome to Cygnite Framework</title>
<?php echo Assets::addstyle('webroot/css/cygnite/style.css'); ?>
<!--<link rel="shortcut icon" href="<?php //echo GHelper::base_path(); ?>webroot/img/cygnite/favicon.png" >-->
</head>
<body>
    <div class="container">
<div class="header">
             <div style="color: #ECECEC; padding: 31px; ">
                 <div align="center" style="font-size: 28px; ">  Welcome To Cygnite Framework </div>
                 <div align="center" style=" margin-top: 3px;"><span style="font-size: 16px;">Framework For Web Artists</span></div>
                 <div align="center" style=" margin-top: 16px;"><span style="font-size: 16px;">"Discover Cygnite to make your life simple and better."</span> </div>
             </div>
    </div>
<?php //show($this->values); ?>

  <div class="container-body">

    <hr class="featurette-divider">

    <div class="container">
                <div class="block features">
                  <h2 class="title-divider"><span><span class="title-em">Core Features OF Cygnite Framework  <?php echo CF_VERSION; ?></span></span>
                      <small>Core libraries are rapidly customised and adding with new features to full-fill all your needs.</small></h2>
                  <ul class="thumbnails">
                    <li class="span3">
                                <div class="feature-block">
                                    <div class="features-head">
                                                            <h3 class="title"><a href="">Better <span class="title-em">Performance</span></a></h3>
                                     </div>
                                      <p> With better performance and caching mechanism which makes your application applications faster then you are expected.
                                      </p>
                                </div>
                    </li>
                    <li class="span3">
                        <div class="feature-block">
                        <div class="features-head">
                                                                <h3 class="title"><a href="">User  <span class="title-em">Friendly</span></a></h3>
                         </div>
                      <p>You may be starter or experienced professional you will find very easy to work with Cygnite Framework.
                             Which boosts your productivity, simplify and minimise your code.
                     </p>
                        </div>
                    </li>

                       <li class="span3">
                           <div class="feature-block">
                            <div class="features-head">
                                                <h3 class="title"><a href="">Inbuilt <span class="title-em">Libraries</span></a></h3>
                            </div>

                      <p> Use inbuilt libraries based on your needs - encryption, x-auth, secure session, cache, pdf, form, file upload  libraries which will make your
                             work simpler then you think.
                     </p>
                           </div>
                    </li>
                      <div class="clear"> </div>
                    <li class="span3">
                        <div class="feature-block">
                        <div class="features-head">
                                    <h3 class="title"><a href="">Multiple <span class="title-em" >Databases  </span></a></h3>
                        </div>
                      <p>Connect multiple databases and generate queries with cygnite query builder.
                             Used pdo to secure your database queries with multiple features.
                      </p>
                        </div>
                    </li>
                          <li class="span3">
                              <div class="feature-block">
                                    <div class="features-head">
                                                                             <h3 class="title"><a href="">Zero  <span class="title-em">Configuration</span></a></h3>
                                </div>
                                  <p> Its very easy to use, almost zero configuration. Create and use your own libraries based upon your needs.</p>
                              </div>
                    </li>


                        <li class="span3">
                            <div class="feature-block">
                                       <div class="features-head">
                                                           <h3 class="title"><a href=""> Secure <span class="title-em">Applications </span></a></h3>
                                    </div>
                                  <p> Security is main concern of any applications. Cygnite make your session and input strings more secure with built in mechanism.</p>
                            </div>
                    </li>

                  </ul>
                </div>
    </div>



    <hr class="featurette-divider">

      <div class="clear"> </div>
  <hr class="featurette-divider">


  <div class="footer" >
      <div class="footer-inner-left"> </div>
                    <div class="footer-inner" align="center">
                                  <div class="footerrow tweets" >
                                    <p style="font-size: 16px;">If you are exploring Cygnite Framework for the first time,
                                        you should read the <br><a href="<?php echo Url::basepath(); ?>docs/">Quick guide</a> </p>

                                    <p style="font-size: 18px;">Hope you will enjoy simplicity of Cygnite Framework</p>
                                  </div>

                              <div class="footerrow" align="center" style="clear:both;padding-top: 0px;">
                                <div class="footer-hr"></div>
                                <?php echo Cygnite::powered_by(); ?>
                              </div>
                    </div>
      <div class="footer-inner-right"> </div>
      <div class="clear"> </div>
  </div>

  <!-- ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<?php echo Assets::addscript('webroot/js/cygnite/jquery.js'); ?>
</body>
</html>