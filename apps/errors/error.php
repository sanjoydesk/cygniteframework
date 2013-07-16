<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title><?php echo $title; ?></title>
        <style style="text/css">
          .errcontent { font-family:Lucida Grande,Verdana,Sans-serif; margin: 40px; font-size:12px;padding: 20px 20px 12px 20px; background:#fff; border:1px solid #A7A788; }
          .errcontent h2 { color: #990000 !important;  font-size: 15px;font-weight: normal;margin: 5px 5px 5px 13px;   height:30px;
                border-bottom:1px solid #CCC; }
          .errcontent p { margin:6px; padding: 9px; color:#888 !important;}
        </style>
    </head>
    <body>
        <div class="errcontent">

            <h2> <?php
                        if($type === 'fatal'):
                            echo 'FATAL ERROR :  ' .strtoupper($title);
                        elseif(($type === 'warning')):
                            echo 'WARNING :  ' .strtoupper($title);;
                        else:
                            echo 'NOTICE :  '.strtoupper($title);
                        endif; ?> </h2>

                        <p >LINE NUMBER : <?php echo $line; ?> </p>
                        <p> EXCEPTION MESSAGE : <?php echo $message; ?></p>
                        <p> FILE PATH : <?php echo $file; ?></p>
       </div>
    </body>
</html>