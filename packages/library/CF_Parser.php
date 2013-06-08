<?php
/*
 *  Cygnite Framework
 *
 *  An open source application development framework for PHP 5.2x or newer
 *
 *   License
 *
 *   This source file is subject to the MIT license that is bundled
 *   with this package in the file LICENSE.txt.
 *   http://www.appsntech.com/license.txt
 *   If you did not receive a copy of the license and are unable to
 *   obtain it through the world-wide-web, please send an email
 *   to sanjoy@hotmail.com so I can send you a copy immediately.
 *
 * @Package                         :  Packages
 * @Sub Packages               :  Library
 * @Filename                       : CF_Parser
 * @Description                   : This library will be available with all features in next version.
 * @Author                          :   Cygnite Dev Team
 * @Copyright                     :  Copyright (c) 2013 - 2014,
 * @Link	                  :  http://www.appsntech.com
 * @Since	                  :  Version 1.0
 * @Filesource
 * @Warning                     :  Any changes in this library can cause abnormal behaviour of the framework
 *
 *
 */

/**
 * Description of cf_FileCache
 *
 * @author SANJOY
 */


class CF_Parser
{


       function write_php_ini($array, $file)
      {
            $res = array();
            foreach($array as $key => $val)
            {
                if(is_array($val))
                {
                    $res[] = "[$key]";
                    foreach($val as $skey => $sval) $res[] = "$skey = ".(is_numeric($sval) ? $sval : '"'.$sval.'"');
                }
                else $res[] = "$key = ".(is_numeric($val) ? $val : '"'.$val.'"');
            }
            safefilerewrite($file, implode("\r\n", $res));
     }


      public function safefilerewrite($file_name, $data_to_save)
     {
          if ($fp = fopen($file_name, 'w')):
            $startTime = microtime();
            do
            {
                $canWrite = flock($fp, LOCK_EX);
               // If lock not obtained sleep for 0 - 100 milliseconds, to avoid collision and CPU load
               if(!$canWrite) usleep(round(rand(0, 100)*1000));
            } while ((!$canWrite)and((microtime()-$startTime) < 1000));

                //file was locked so now we can store information
                if ($canWrite):
                    fwrite($fp, $data_to_save);
                    flock($fp, LOCK_UN);
                endif;
              fclose($fp);
        endif;
      }

      function write_ini($assoc_arr, $path, $has_sections=FALSE) {
            $content = "";
            if ($has_sections) {
                foreach ($assoc_arr as $key=>$elem) {
                    $content .= "[".$key."]\n";
                    foreach ($elem as $key2=>$elem2) {
                        if(is_array($elem2))
                        {
                            for($i=0;$i<count($elem2);$i++)
                            {
                                $content .= $key2."[] = \"".$elem2[$i]."\"\n";
                            }
                        }
                        else if($elem2=="") $content .= $key2." = \n";
                        else $content .= $key2." = \"".$elem2."\"\n";
                    }
                }
            }
            else {
                foreach ($assoc_arr as $key=>$elem) {
                    if(is_array($elem))
                    {
                        for($i=0;$i<count($elem);$i++)
                        {
                            $content .= $key2."[] = \"".$elem[$i]."\"\n";
                        }
                    }
                    else if($elem=="") $content .= $key2." = \n";
                    else $content .= $key2." = \"".$elem."\"\n";
                }
            }

            if (!$handle = fopen($path, 'w')) {
                return false;
            }
            if (!fwrite($handle, $content)) {
                return false;
            }
            fclose($handle);
            return true;
        }

        public function read($file_name)
        {
            //CF_SYSTEM.'/Ignite/robot.ini'
             $contents =  parse_ini_file($file_name,TRUE);
             return $contents;
        }

}

/*
 $sampleData = array(
                'first' => array(
                    'first-1' => 1,
                    'first-2' => 2,
                    'first-3' => 3,
                    'first-4' => 4,
                    'first-5' => 5,
                ),
                'second' => array(
                    'second-1' => 1,
                    'second-2' => 2,
                    'second-3' => 3,
                    'second-4' => 4,
                    'second-5' => 5,
                ));
write_ini($sampleData, './data.ini', true);
 */