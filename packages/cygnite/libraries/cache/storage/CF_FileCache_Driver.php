<?php

    /*
     * To change this template, choose Tools | Templates
     * and open the template in the editor.
     */

    /**
     * Description of CF_FileCache
     *This library is influnced by simple cache class
     *
     */


    class CF_FileCache_Driver
    {
             /**
           * The path to the cache file folder
           *
           * @var string
           */
          private $_cachepath =NULL;

          /**
           * The name of the default cache file
           *
           * @var string
           */
          private $_cachename = 'default';

          /**
           * The cache file extension
           *
           * @var string
           */
          private $_extension = '.tmp';


                function __construct()
                {
                       Config::getconfig('global_config','cache_name');
                       $cache_config = array(
                                                               'name' => Config::getconfig('global_config','cache_name'),
                                                               'path' => Config::getconfig('global_config','cache_directory'),
                                                               'extension' => Config::getconfig('global_config','cache_extension')
                       );
                        $this->initialize($cache_config);
                        if( Config::getconfig('global_config','cache_directory') == "none"):
                                throw new Exception('You must provide cache directory to  use cache mechanism ',E_COMPILE_ERROR ,NULL);
                        else:
                                 $this->_cachepath = APPPATH.Config::getconfig('global_config','cache_directory').'/';
                        endif;
                }

                public function initialize($config= array())
                {
                       if (isset($config) ===TRUE) :
                              if (is_string($config)) :
                                        $this->set_cache($config);
                               elseif (is_array($config)):
                                        $this->set_cache($config['name']);
                                        $this->set_path($config['path']);
                                        $this->set_cache_extension($config['extension']);
                              endif;
                     endif;
                }

                public function is_cached($key)
                {
                       if ( $this->_getcache() != FALSE) :
                                 $cachedData = $this->_getcache();
                      return isset($cachedData[$key]['data']);
                endif;
              }

                 public function get_timeout()
                {
                        return (int)ini_get('session.gc_maxlifetime');
                }
                /**
                * Save data into cache
                *
                * @param string
                * @param mixed
                * @param integer [optional]
                * @return object
                */
                public function write_cache($key, $value, $expiration = 0)
                {
                         // $this->get_timeout(); Do delete based on the session time out
                          $data = array(
                                                  'time'   => time(),
                                                  'expire' => $expiration,
                                                  'data'   => $value
                                                );
                                    if (is_array($this->_getcache())) :
                                              $dataArray = $this->_getcache();
                                              $dataArray[$key] = $data;
                                    else:
                                             $dataArray = array($key => $data);
                                    endif;
                            $cacheData = json_encode($dataArray);
                            if($this->get_cache_directory() == TRUE):
                                     @file_put_contents($this->get_cache_directory(), $cacheData);
                            endif;
                        return $this;

                }

                /**
               * Retrieve cache value from file by key
               *
               * @param string
               * @param boolean [optional]
               * @return string
               */
              public function read_cache($key, $timestamp = FALSE)
              {
                    $cachedata = $this->_getcache();
                    if($timestamp === FALSE)
                        return $cachedata[$key]['data'];
                    else
                        return $cachedData[$key][ 'time'];
              }

              private function _getcache()
              {
                    if (file_exists($this->get_cache_directory()))
                              return json_decode(file_get_contents($this->get_cache_directory()), TRUE);
                    else
                        return false;
              }

              private function get_cache_directory()
              {
                      if ($this->has_cache_dir() === TRUE) :
                              $file_name = $this->get_cache_name();
                              $filename = preg_replace('/[^0-9a-z\.\_\-]/i', '', strtolower($file_name));
                         return $this->get_path() . md5($filename) . $this->get_cache_extension();
                    endif;
              }


              function has_cache_dir()
              {
                    if (!is_dir($this->get_path()) && !mkdir($this->get_path(), 0775, TRUE)):
                               GHelper::display_errors(E_USER_NOTICE, 'Cache Path Error ','Unable to create cache directory ' . $this->get_path(), __FILE__, __LINE__);
                    elseif (!is_readable($this->get_path()) || !is_writable($this->get_path())):
                            if (!chmod($this->get_path(), 0775))
                               GHelper::display_errors(E_USER_NOTICE, 'Cache Path Error ',$this->get_path() . ' directory must be writeable', __FILE__, __LINE__);
                    endif;
                    return true;
              }

              private function get_path()
              {
                   return $this->_cachepath;
              }

               public function set_path($pathurl)
              {
                    $this->_cachepath = $pathurl;
                return $this;
              }

                public function set_cache($name)
              {
                        $this->_cachename = $name;
                return $this;
              }

              /**
               * get cache name
               *
               * @return void
               */
              public function get_cache_name()
              {
                     return $this->_cachename;
              }

                public function set_cache_extension($ext)
              {
                          $this->_extension = $ext;
                return $this;
              }

              /**
               * Cache file extension Getter
               *
               * @return string
               */
              public function get_cache_extension()
              {
                     return $this->_extension;
              }

              private function _is_expired($timestamp, $expiration)
             {
                    $result = false;
                    if ($expiration !== 0):
                      $timeDiff = time() - $timestamp;
                      ($timeDiff > $expiration) ? $result = TRUE : $result = FALSE;
                    endif;
                    return $result;
              }

               public function delete_expired_cache()
              {
                $cacheData = $this->_getcache();
                if (true === is_array($cacheData)) :
                      $counter = 0;
                      foreach ($cacheData as $key => $entry) :
                        if (TRUE === $this->_is_expired($entry['time'], $entry['expire'])) :
                          unset($cacheData[$key]);
                          $counter++;
                        endif;
                  endforeach;

                  if ($counter > 0) :
                        $cacheData = json_encode($cacheData);
                        @file_put_contents($this->get_cache_directory(), $cacheData);
                  endif;
                  return $counter;
                endif;
              }

              /**
               * Erase all cached entries
               *
               * @return object
               */
              public function delete_all_cache()
              {
                $cache_dir = $this->get_cache_directory();
                        if (file_exists($cache_dir)) :
                                $cache_file = fopen($cache_dir, 'w');
                               fclose($cache_file);
                        endif;
                return $this;
              }
    }
