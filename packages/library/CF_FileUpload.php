<?php  if ( ! defined('CF_BASEPATH')) exit('No direct script access allowed');

       /*
         *===============================================================================================
         *  An open source application development framework for PHP 5.2 or newer
         *
         * @Package                         :
         * @Filename                       :
         * @Description                   :
         * @Autho                            : Appsntech Dev Team
         * @Copyright                     : Copyright (c) 2013 - 2014,
         * @License                         : http://www.appsntech.com/license.txt
         * @Link	                          : http://appsntech.com
         * @Since	                          : Version 1.0
         * @Filesource
         * @Warning                      : Any changes in this library can cause abnormal behaviour of the framework
         * ===============================================================================================
         */
class CF_FileUpload
{
        /*
         *Set the file types to upload files
         *@To do : Is it like you are setting file ext type to restrict user to upload files. If so then we need to give
         *  provision to user to set file extensions type not here.
         * @type array
         */
        private $file_requirements = array(
                                                                            "ext"=>array(
                                                                                                    "jpeg","png","jpg","gif","pdf","doc","docx" ,'txt'
                                                                                                    ),
                                                                           "params"=>array(),
                                                                           "file"=>array()
                                                                        );
        /*
         * @access private
         * @type    array
         */
        private $prefix = array('byte','kb','mb','gb','tb','pb');

         /*
         * @description :
         * @access         :  private
         * @type            :  array
         */
        private $validation_array = array(
                                                                        "size"=>array(
                                                                                                "func"=>"is_string",
                                                                                                "msg"=>"String values only valid"),
                                                                        "file"=>array(
                                                                                                "func"=>"is_array",
                                                                                                "msg"=>"Array values only valid"),
                                                                        "params"=>array(
                                                                                               "func"=>"is_array",
                                                                                               "msg"=>"Array values only valid"),
                                                                        "ext"=>array(
                                                                                                "func"=>"is_array",
                                                                                                "msg"=>"Array values only valid"));


        /*
         *  File Upload Constructor. Set the max upload size to the configuration
         * @access public
         */
        public function __construct()
        {
                  $this->file_requirements["size"] = ini_get("upload_max_filesize");
       }

       /*
        * This function is used to set the maximum file upload size
        *@access  public
        *@param integer upload size in mb
        *@return void
        */
       public function set_upload_size($size)
       {
                if(is_null($size))
                        throw new InvalidArgumentException("Cannot pass null argument to ".__FUNCTION__);
                    ini_set('upload_max_filesize', $size);
       }

        private function upload_file()
       {
                // if upload path not specified InvalidArgumentException will be throwned
                if(!(isset($this->file_requirements['params']['upload_path']) && !empty($this->file_requirements['params']['upload_path'])))
                                throw new InvalidArgumentException("Upload path required");

                $path_array = pathinfo($this->file_requirements['file']['name']);

                // if invalid file uploaded InvalidArgumentException will be throwned
                if(!in_array(strtolower($path_array['extension']),$this->file_requirements["ext"]))
                             throw new InvalidArgumentException("Invalid file upload: Following formats only allowed ".implode(",",$this->file_requirements['ext']));


                if($this->file_requirements['file']['size'] <= $this->getNumericFileSize($this->file_requirements["size"])):
                            if(move_uploaded_file($this->file_requirements['file']['tmp_name'],
                                                                      getcwd().$this->file_requirements['params']['upload_path']."/".$this->file_requirements['file']['name']))
                                    return true;
                            else // if file was not uploaded successfully  ErrorException will be throwned
                                throw new ErrorException($this->file_requirements['file']['name']." was not uploaded successfully ");
                else: // if file size was too large  OutofRange exception will be throwned
                            throw OutOfRangeException($this->file_requirements['file']['name']." was too large exceeds upload limit ".$this->file_requirements['size']);
                endif;
        }


        public function __set($key,$value)
        {
                  if(!isset($this->file_requirements[$key]))
                          throw new InvalidArgumentException("Invalid : undefined variable ".__CLASS__."::$".$key);

                  if(!call_user_func($this->validation_array[$key]['func'],$value))
                         throw new InvalidArgumentException("Invalid type : ".__CLASS__."::$".$key." ".$this->validation_array[$key]['msg']) ;
                  $this->file_requirements[$key] = $value;
        }


        public function __call($function,$arguments)
       {
                    if($function !== "upload")
                               throw new ErrorException("Undefined function call : ".__CLASS__."::{$function} function name undefined");

                    if(isset($arguments[0]) && is_array($arguments[0]) && count($arguments[0])>0)
                              $this->file_requirements['params'] = $arguments[0];

                    if(isset($arguments[1]) && is_array($arguments[1]) && count($arguments[1])>0)
                              $this->file_requirements['file'] = $arguments[1];

                    if(isset($arguments[2]) && is_array($arguments[2]) && count($arguments[2])>0)
                              $this->file_requirements['ext'] = $arguments[2];

                    if(isset($arguments[3]) && is_array($arguments[3]) && count($arguments[3])>0)
                              $this->file_requirements['size'] = $arguments[3];

                            $temp_arguments = $this->file_requirements['file'];

                    if(isset($arguments[0]) && $arguments[0]['multi_upload'] === true):
                                foreach ($temp_arguments as $key=>$value):
                                    $this->file_requirements['file'] = $value;
                                    call_user_func_array(array($this,"upload_file"),array());
                               endforeach;
                    else:
                               call_user_func_array(array($this,"upload_file"),array());
                    endif;
        }


        /***************************************
         *
         *This function to change numeric value to it binary string and to get the file size
         *@access private
         *@param integer
         * @return unknown
        */
        private function filesize($filesize)
        {
                if(is_numeric($filesize)):
                          $decr = 1024; $step = 0;
                                    while(($filesize / $decr) > 0.9):
                                            $filesize = $filesize / $decr;
                                            $step++;
                                    endwhile;
                            return round($filesize,2).' '.strtoupper($this->prefix[$step]);
                 else :
                           return 'NaN';
                endif;

     }
     /***************************************
      * TODO : please change the regular expression matching in a efficient way
      *
      * function to change binary string to it numeric value
      *
      *
      */
      private function getNumericFileSize($str)
     {

            $bytes = 0;
            $str = strtoupper($str);
            $bytes_array = array(
                                                'B' => 0,
                                                           'K' => 1,
                                                           'M' => 2,
                                                           'G' => 3,
                                                           'T' => 4,
                                                           'P' => 5,
                                                'KB' => 1,
                                                           'MB' => 2,
                                                           'GB' => 3,
                                                           'TB' => 4,
                                                           'PB' => 5,
              );

            $bytes = floatval($str);

            if (preg_match('#([KMGTP]?.)$#si', $str, $matches) && !empty($bytes_array[$matches[1]])) :
                         $bytes *= pow(1024, $bytes_array[$matches[1]]);
            endif;

                    $bytes = intval(round($bytes, 2));
           return $bytes;
        }

        public function __destruct()
        {
                unset($this->file_requirements);
                unset($this->prefix);
                unset($this->validation_array);
        }

}