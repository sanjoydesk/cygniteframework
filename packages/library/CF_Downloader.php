<?php
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
class CF_Downloader
{

	private $mime_type = NULL;
	private $file_path = NULL;

	public function __construct() { }

	/*
	|---------------------------
	| Get file information from file path
	|
	| @access private
	| @param  string $file_name
	| @return array - array
	|---------------------------
	*/
	private function get_file_info($file_path)
	{
		$path_info = array();
		$path_info = pathinfo($file_path);
		//$path_info['dirname'];$path_info['basename'];$path_info['extension'];$path_info['filename'];
		return $path_info;
	}

	/*
	|---------------------------
	| This function is to get mime type
	|
	| @access public
	| @param  NULL
	| @return string
	|---------------------------
	*/
	public function get_mime_type()
	{
		if(is_null($this->mime_type) || $this->mime_type == "")
			throw new InvalidArgumentException("Empty argument passed to ".__FUNCTION__);

		if(isset($this->mime_type))
			 return $this->mime_type;
	}

                            /*
                            |---------------------------
                            | This function is to set mime type of requested file
                            |
                            | @access private
                            | @param  string $file
                            | @return boolean
                            |---------------------------
                            */
	private function set_mime_type($file = "")
	{
	    $ext = explode(".", $file);

	    switch($ext[sizeof($ext)-1]):

	      case 'jpeg':
	      			$this->mime_type = "image/jpeg";
	      		break;
	      case 'jpg':
                                                                                                               $this->mime_type = "image/jpg";
	      		break;
	      case "gif":
	      			$this->mime_type = "image/gif";
	      		break;
	      case "png":
	      			$this->mime_type = "image/png";
	      		break;
	      case "pdf":
	      			$this->mime_type = "application/pdf";
	      		break;
	      case "txt":
	      			$this->mime_type = "text/plain";
	      		break;
                                 case 'jad':
	      			$this->mime_type = "text/vnd.sun.j2me.app-descriptor";
	      		break;
                                  case 'jar':
	      			$this->mime_type = "application/java-archive";
	      		break;
	      case 'zip':
	      			$this->mime_type = "application/zip";
	      		break;
	      case "doc":
	      			$this->mime_type = "application/msword";
	      		break;
                                  case "docx":
	      			$this->mime_type = "application/msword";
	      		break;
                                  case "xls":
	      			$this->mime_type = "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
	      		break;
	      case "ppt":
	      			$this->mime_type = "application/vnd.ms-powerpoint";
	      		break;
	      case "wbmp":
	      			$this->mime_type = "image/vnd.wap.wbmp";
	      		break;
	      case "wmlc":
	      			$this->mime_type = "application/vnd.wap.wmlc";
	      		break;
	      case "mp4s":
	      			$this->mime_type = "application/mp4";
	      		break;
	      case "ogg":
	      			$this->mime_type = "application/ogg";
	      		break;
	      case "pls":
	      			$this->mime_type = "application/pls+xml";
	      		break;
	      case "asf":
	      			$this->mime_type = "application/vnd.ms-asf";
	      		break;
	      case "swf":
	      			$this->mime_type = "application/x-shockwave-flash";
	      		break;
	      case "mp4":
	      			$this->mime_type = "video/mp4";
	      		break;
	      case "m4a":
	      			$this->mime_type = "audio/mp4";
	      		break;
	      case "m4p":
	      			$this->mime_type = "audio/mp4";
	      		break;
	      case "mp4a":
	      			$this->mime_type = "audio/mp4";
	      		break;
	      case "mp3":
	      			$this->mime_type = "audio/mpeg";
	      		break;
	      case "m3a":
	      			$this->mime_type = "audio/mpeg";
	      		break;
	      case "m2a":
	      			$this->mime_type = "audio/mpeg";
	      		break;
	      case "mp2a":
	      			$this->mime_type = "audio/mpeg";
	      		break;
	      case "mp2":
	      			$this->mime_type = "audio/mpeg";
	      		break;
	      case "mpga":
	      			$this->mime_type = "audio/mpeg";
	      		break;
	      case "wav":
	      			$this->mime_type = "audio/wav";
	      		break;
	      case "m3u":
	      			$this->mime_type = "audio/x-mpegurl";
	      		break;
	      case "bmp":
	      			$this->mime_type = "image/bmp";
	      		break;
	      case "ico":
	      			$this->mime_type = "image/x-icon";
	      		break;
	      case "3gp":
	      			$this->mime_type = "video/3gpp";
	      		break;
	      case "3g2":
	      			$this->mime_type = "video/3gpp2";
	      		break;
	      case "mp4v":
	      			$this->mime_type = "video/mp4";
	      		break;
	      case "mpg4":
	      			$this->mime_type = "video/mp4";
	      		break;
	      case "m2v":
	      			$this->mime_type = "video/mpeg";
	      		break;
	      case "m1v":
	      			$this->mime_type = "video/mpeg";
	      		break;
	      case "mpe":
	      			$this->mime_type = "video/mpeg";
	      		break;
	      case "mpeg":
	      			$this->mime_type = "video/mpeg";
	      		break;
	      case "mpg":
	      			$this->mime_type = "video/mpeg";
	     		break;
	      case "mov":
	      			$this->mime_type = "video/quicktime";
	      		break;
	      case "qt":
	      			$this->mime_type = "video/quicktime";
	      		break;
	      case "avi":
	      			$this->mime_type = "video/x-msvideo";
	      		break;
	      case "midi":
	      			$this->mime_type = "audio/midi";
	      		break;
	      case "mid":
	      			$this->mime_type = "audio/mid";
	      		break;
	      case "amr":
	      			$this->mime_type = "audio/amr";
	      		break;
	      default:
	      			$this->mime_type = "application/force-download";
	   endswitch;

	   return TRUE;
	}

	public function download($file_path)
	{
                                    if(is_null($this->file_path) && $file_path != "")
                                            $this->file_path = $file_path;
                                    $is_set_filetype= $this->set_mime_type($this->file_path);
                                    if($is_set_filetype)
                                            $this->set_headers();//$this->file_path
	}

                            /*
	|---------------------------
	| This function is to set headers for file download
	|
	| @access private
	| @param  string $file
	| @return void
	|---------------------------
	*/
	private function set_headers()
	{
                                /*Execution Time unlimited*/
                                set_time_limit(0);

                               $file_size = filesize($this->file_path);
                                if($file_size === false)
                                        GlobalHelper::display_errors(E_USER_WARNING, "Invalid path ", "Invalid path exception", __FILE__,__LINE__);
                                $mime_type = $this->get_mime_type();

                                ob_start();
                                header('Content-Description: File Transfer');
	    header('Content-Type: '.$mime_type);
                                //header("Content-type: ".mime_content_type($value));
	    header('Content-Disposition: attachment; filename='.rawurlencode(basename($this->file_path)));
	    header('Content-Transfer-Encoding: binary');
	    header('Excfres: 0');
	    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	    header('Pragma: public');
	    header('Content-Length: '.$file_size);
	    ob_clean();
	    ob_end_flush();
	    readfile($this->file_path);
                                exit;
	}
}