<?php namespace Apps\Components\Libraries;

/**
 *  Cygnite Framework
 *
 *  An open source application development framework for PHP 5.3x or newer
 *
 *   License
 *
 *   This source file is subject to the MIT license that is bundled
 *   with this package in the file LICENSE.txt.
 *   http://www.cygniteframework.com/license.txt
 *   If you did not receive a copy of the license and are unable to
 *   obtain it through the world-wide-web, please send an email
 *   to sanjoy@hotmail.com so I can send you a copy immediately.
 *
 * @package                          Apps
 * @subpackages                 Thumbnail image Components
 * @filename                         thumbnail
 * @description                    Thumbnail component is used to generate thumb images from given configurations
 * @author                           Sanjoy Dey
 * @copyright                      Copyright (c) 2013 - 2014,
 * @link	                    http://www.cygniteframework.com
 * @since	                    Version 1.0
 * @filesource
 * @warning                       Any changes in this library can cause abnormal behaviour of the framework
 *
 * <code>
 *    Example:
 *    $image = new \Apps\Components\Libraries\Thumbnail();
 *    $image->directory = 'Set your directory path';
 *    $image->fixedWidth  = 100;
 *    $image->fixedHeight = 100;
 *    $image->thumbPath = 'your thumb path';
 *    $image->thumbName = 'Your thumb image name';// Optional. If you doen't want to have custom name then it will generate
 *                                                 thumb as same name of original image.
 *    $image->resize();
 * </code>
 */

class Thumbnail
{
    public $thumbs = array();

    public $imageTypes = array("jpg","png","jpeg","gif");

    public function __set($key, $value)
    {
          $this->thumbs[$key] = $value;
    }

    public function __get($key)
    {
        if(isset($this->thumbs[$key]))
              return $this->thumbs[$key];
    }

    public function resize()
    {
         $path = array();
         $src = getcwd().DS.str_replace(array('/','\\'), DS, $this->directory);	 /* read the source image */

        if(file_exists($src)):
            $info = getimagesize($src); // get the image size
            $path = pathinfo($src);

                    if(!in_array(strtolower($path['extension']),$this->imageTypes))
                            throw new \Exception(404,"File type not supports");

                   $thumbName = (!is_null($this->thumbName))
                                                                             ?   $this->thumbName.'.'.$path['extension']
                                                                            :  $path['basename'];


                            switch(strtolower($path['extension'])):

                                case 'jpg'	:
                                                $sourceImage = imagecreatefromjpeg($src);
                                                $thumbImg=$this->changeDimensions($sourceImage,$this->fixedWidth,$this->fixedHeight);

                                                if(imagejpeg($thumbImg,getcwd().DS.str_replace(array('/','\\'), DS, $this->thumbPath).$thumbName))
                                                      chmod(getcwd().DS.str_replace(array('/','\\'), DS, $this->thumbPath).$thumbName, 0777);
                                                else
                                                      throw new \Exception("Unknown Exception  while generating thumb image");

                                        break;
                                case 'png'  :
                                                $sourceImage = imagecreatefrompng($src);
                                                $thumbImg=$this->changeDimensions($sourceImage,$this->fixedWidth,$this->fixedHeight);

                                                if(imagepng($thumbImg,getcwd().DS.str_replace(array('/','\\'), DS, $this->thumbPath).$thumbName))
                                                    chmod(getcwd().DS.str_replace(array('/','\\'), DS, $this->thumbPath).$thumbName, 0777);
                                                else
                                                    throw new \Exception("Unknown Exception  while generating thumb image");

                                        break;
                                case 'jpeg' :
                                                $sourceImage = imagecreatefromjpeg($src);
                                                $thumbImg=$this->changeDimensions($sourceImage,$this->fixedWidth,$this->fixedHeight);

                                                if(imagejpeg($thumbImg,getcwd().DS.str_replace(array('/','\\'), DS, $this->thumbPath).$thumbName))
                                                    chmod(getcwd().DS.str_replace(array('/','\\'), DS, $this->thumbPath).$thumbName, 0777);
                                                else
                                                    throw new \Exception("Unknown Exception  while generating thumb image");
                                        break;
                                case 'gif'  :
                                                $sourceImage = imagecreatefromjpeg($src);
                                                $thumbImg=$this->changeDimensions($sourceImage,$this->fixedWidth,$this->fixedHeight);

                                                if(imagegif($thumbImg,getcwd().DS.str_replace(array('/','\\'), DS, $this->thumbPath).$thumbName))
                                                      chmod(getcwd().DS.str_replace(array('/','\\'), DS, $this->thumbPath).$thumbName, 0777);
                                                else
                                                      throw new \Exception("Unknown Exception  while generating thumb image");

                                        break;
                           endswitch;
                                     return TRUE;
            else:
                      throw new \Exception("404 File not found on given path");
            endif;

	}

        public function changeDimensions($sourceImage,$desiredWidth,$desiredHeight)
        {
            $temp = "";
            // find the height and width of the image
                if(imagesx($sourceImage) >= imagesy($sourceImage) && imagesx($sourceImage) >= $this->fixedWidth):
                        $temp = imagesx($sourceImage) / $this->fixedWidth;
                        $desiredWidth  = imagesx($sourceImage)/$temp;
                        $desiredHeight = imagesy($sourceImage)/$temp;
                elseif(imagesx($sourceImage) <= imagesy($sourceImage) && imagesy($sourceImage) >=$this->fixedHeight):
                        $temp = imagesy($sourceImage)/$this->fixedHeight;
                        $desiredWidth  = imagesx($sourceImage) /$temp;
                        $desiredHeight = imagesy($sourceImage)/$temp;
                else:
                        $desiredWidth  = imagesx($sourceImage);
                        $desiredHeight = imagesy($sourceImage);
                endif;
                    /* create a new, "virtual" image */
                    $thumbImg = imagecreatetruecolor($desiredWidth,$desiredHeight);
                    $imgAllocate =imagecolorallocate($thumbImg, 255, 255, 255);
                    imagefill($thumbImg,0,0,$imgAllocate);
                    /* copy source image at a resized size */
                    imagecopyresampled($thumbImg,$sourceImage,0,0,0,0,$desiredWidth,$desiredHeight,imagesx($sourceImage),imagesy($sourceImage));

           return $thumbImg;
        }

      public function __destruct()
      {
           unset($this->thumbs);
      }

}