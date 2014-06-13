<?php
namespace Cygnite\Common\SessionManager\Flash;

use Cygnite\Common\SessionManager\Session;
use Cygnite\Common\SessionManager;

class FlashMessage
{
    private $msgString;

    private $validFlashTypes = array( 'help', 'info', 'warning', 'success', 'error' );

    private static $sessionArray;

    private $flashMessageWrapper = "<div class='%s %s'><a href='javascript:void(0);' class='closeFlash'></a>\n%s</div>\n";


    public function __construct()
    {
        if( !session_id() ) session_start();
        // Create the session array if it doesnt already exist
        if( !array_key_exists('flashMessages', $_SESSION) ) $_SESSION['flashMessages'] = array();
    }

    public function setSession($value)
    {
        $_SESSIONArray = $value;
    }

    public function getSession()
    {
        return !empty($_SESSIONArray) ? $_SESSIONArray : null;
    }

    public function setFlash($key, $message)
    {

        echo "$key  $message";

        /*if( !isset($_SESSION['flashMessages'])) {
            echo "ssssssssss";
            return false;
        }*/

        //if( !isset($key) || !isset($message[0]) ) return false;

        // Make sure it's a valid message type
        if( !in_array($key, $this->validFlashTypes) ) {
            die('"'.strip_tags($key).'" is not a valid message type!' );
        }

        $_SESSION['flashMessages'] = array();

        // If the session array doesn't exist, create it
        if( !array_key_exists( $key, $_SESSION['flashMessages'] ) ) {

            $_SESSION['flashMessages'][$key] = array();
        }

        $_SESSION['flashMessages'][$key][] = $message;

        show($_SESSION);

        return true;
    }

    /**
     *
     * @param $key
     * @return bool|string
     */
    public function getFlash($key = '')
    {
        $messages = $this->msgString = '';

        show($_SESSION);exit;
        // Print a certain type of message?
        if( in_array($key, $this->validFlashTypes) ) {
            echo "in arry";

            show($_SESSION);

            if (isset($_SESSION['flashMessages'])) {
                echo "here";
                foreach( $_SESSION['flashMessages'][$key] as $message ) {
                    $messages .= "<p>".$message."</p>\n";
                }
            }

            echo $this->msgString .= $this->getMessageString($key, $messages);
            //$this->msgString .= sprintf($this->msgWrapper, $this->msgClass, $key, $messages);

            // Clear All viewed messages
            $this->clearViewedMessage($key);

            // Print ALL queued messages
        } elseif( $key == '' ) {

            echo "$key empty";

            foreach ( $_SESSION['flashMessages'] as $type => $msgArray ) {

                $messages = '';
                foreach ( $msgArray as $message ) {
                    $messages .= $this->msgBefore . $message . $this->msgAfter;
                }

                $this->msgString .= $this->getMessageString($key, $messages);
                //$this->msgString .= sprintf($this->flasWrapper, 'flash', $key, $messages);
            }

            // Clear All viewed messages
            $this->clearViewedMessage();

            // Invalid Message Type?
        } else {
            echo "else";exit;
            return false;
        }

        echo $this->msgString;exit;

        return $this->msgString;

    }

    private function getMessageString($key, $messages)
    {
        return sprintf($this->flashMessageWrapper, 'flash', $key, $messages);
    }

    public function hasFlash($key, $default = 'flashMessages')
    {
        $flashMessage = isset($_SESSION[$default]) ?
            $_SESSION[$default] :
            array();
        show($_SESSION);
echo $key." has flash";
        show($flashMessage);
        return isset($flashMessage[$key]) ? true : false;
    }

    private function clearViewedMessage($key = '', $default = 'flashMessages')
    {
        $message = '';
        $message = ( $key !== '') ?
            !isset($_SESSION[$default]) ?:
            $_SESSION[$default][$key]
            :
            $_SESSION[$default];

        unset($message);

        return true;
    }

    /**
     * Display the Flash Message to browser
     *
     * @return mixed
     */
    public function __toString()
    {
        return $this->msgString;
    }
}