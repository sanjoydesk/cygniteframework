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

    class CF_Zip
    {
        private $zip = NULL;

        private $path = "";

        public function __construct()
        {
                 if(!extension_loaded('zip'))
                       throw new Exception("Zip extension not loaded !");

                  $this->zip = new ZipArchive();

        }

        private function open_zip_archive($filepath)
        {
                 $this->path = $filepath;
                if ($this->zip->open($filepath, ZipArchive::CREATE) == FALSE)
                            throw new Exception("Cannot open $filepath \n ");
        }

       /*
        * Prevent cloning
        */
        final private function __clone() {}
/*

 $array = array(
                            'file_name'            => '',
                            'file_location'    => '' ,
                            'zip_file_name'   => ''
                           );

 */


        public function make_zip($filename,$pathlocation, $new_location ="",$zip_name = "")
        {
                $this->open_zip_archive($filename);

                 $dir_handler = opendir($pathlocation);

                if (TRUE == $dir_handler):
                    $file = readdir($dir_handler);
                  //  var_dump($file);

                        while (TRUE == ($file = readdir($dir_handler))):
                                  if (is_file($pathlocation.$file)):
                                        $this->add_file($pathlocation.'/'.$filename, $new_location.$file);
                                 elseif ($file != '.' && $file != '..' and is_dir($directory.$file)):
                                        $this->add_dir($new_location.$file.'/');
                                        $this->make_zip($pathlocation.$file.'/', $new_location.$file.'/');
                                endif;
                        endwhile;
                endif;
                closedir($dir_handler);
                $this->close_zip_archive();// echo $file;exit;
                $this->download_zip($zip_name,$pathlocation.$file);

        }

        /**
        * adds a file to .zip file.
        * @param string $path_to_file the path on the hard drive where the file exists
        * @param string $put_into_dir the name of the directory inside the .zip file to put the file into
        */
        private function add_file($put_into_dir,$path_to_file)
        {
                 $this->zip->addFromString($put_into_dir, file_get_contents($path_to_file) );
        }

        /**
         * This function is used to add a directory inside of .zip file to put files into
         * @param string $dir_name the name of the directory to add, can be created nested directories as well like
         * dir1/dir2/dir3
         */
        private function add_dir($dir_name)
        {
                $this->zip->addEmptyDir($dir_name);
        }

        private function download_zip($zip_name,$file_path)
        {

                if(ini_get('zlib.output_compression'))
                        ini_set('zlib.output_compression', 'Off');

                // Security checks
                if(! file_exists( $zip_name) || $zip_name == "" )
                        throw new Exception("The zip archive file not specified to download.");

                  CF_AppRegistry::load_lib_class('CF_Downloader');
                  CF_AppRegistry::load('Downloader')->download($file_path);

                header("Pragma: public");
                header("Excfres: 0");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Cache-Control: private",FALSE);
                header("Content-Type: application/zip");
                header("Content-Disposition: attachment; filename=".basename($zip_name).";" );
                header("Content-Transfer-Encoding: binary");
                header("Content-Length: ".filesize($zip_name));
                readfile("$zip_name");
        }

        protected function parseDirectory($rootPath, $seperator="/")
        {
		$fileArray=array();
		$handle = opendir($rootPath);
		while( ($file = @readdir($handle))!==false) {
			if($file !='.' && $file !='..'){
				if (is_dir($rootPath.$seperator.$file)){
					$array=$this->parseDirectory($rootPath.$seperator.$file);
					$fileArray=array_merge($array,$fileArray);
				}
				else {
					$fileArray[]=$rootPath.$seperator.$file;
				}
			}
		}
		return $fileArray;
	}

	/**
	 * Function to Zip entire directory with all its files and subdirectories
	 *
	 * @param string $dirName
	 * @access public
	 * @return void
	 */
	public function zipDirectory($dirName, $outputDir)
	{
		if (!is_dir($dirName)){
			trigger_error("CreateZipFile FATAL ERROR: Could not locate the specified directory $dirName", E_USER_ERROR);
		}
		$tmp=$this->parseDirectory($dirName);
		$count=count($tmp);
		$this->addDirectory($outputDir);
		for ($i=0;$i<$count;$i++){
			$fileToZip=trim($tmp[$i]);
			$newOutputDir=substr($fileToZip,0,(strrpos($fileToZip,'/')+1));
			$outputDir=$outputDir.$newOutputDir;
			$fileContents=file_get_contents($fileToZip);
			$this->addFile($fileContents,$fileToZip);
		}
	}



        private function close_zip_archive()
        {
                $this->zip->close();
        }

        public function __destruct()
        {
                 $this->zip->close();
                 unset($this->zip);
        }
    }
