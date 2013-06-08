<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Debug Mode: Stack Trace</title>
        <style style="text/css"> 
            .stacktrace {
                border:1px solid #D3640F;word-wrap: break-word; line-height: 28px;margin-left: 39px; width: 94%;
                padding: 12px;
            }
            h2 {
                color: #990000;
                margin-left:10px;
                font-size: 15px;
                font-weight: normal;
                height:30px; 
                border-bottom:1px solid #CCC;
            }
        </style>
    </head>
    <body>
        <div class="stacktrace">         
            <h2> DEBUG MODE: TRACE REQUEST </h2>
                <pre style='word-wrap: break-word;color:#242424;'>
                      <?php print_r(debug_print_backtrace()); ?>
                </pre>
        </div>
    </body>
</html>