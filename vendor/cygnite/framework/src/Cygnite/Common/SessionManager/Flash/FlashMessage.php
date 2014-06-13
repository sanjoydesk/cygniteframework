<?php
namespace Cygnite\Common\SessionManager\Flash;

use Cygnite\Common\SessionManager\Session;
use Cygnite\Common\SessionManager;

class FlashMessage
{
    private $msgString;

    private $validFlashTypes = array( 'help', 'info', 'warning', 'success', 'error' );

    private $msgTypes = array( 'help', 'info', 'warning', 'success', 'error' );

    private static $sessionArray;

    var $msgClass = 'messages';
    var $msgWrapper = "<div class='%s %s'><a href='#' class='closeMessage'></a>\n%s</div>\n";
    var $msgBefore = '<p>';
    var $msgAfter = "</p>\n";

    private $flashMessageWrapper = "<div class='%s %s'><a href='javascript:void(0);' class='closeFlash'></a>\n%s</div>\n";


    public function __construct()
    {
       // if( !session_id() ) session_start();
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

    public function setFlash($type, $message)
    {

        if( !isset($_SESSION['flash_messages']) ) return false;

        if( !isset($type) || !isset($message[0]) ) return false;

        // Replace any shorthand codes with their full version
        if( strlen(trim($type)) == 1 ) {
            $type = str_replace( array('h', 'i', 'w', 'e', 's'), array('help', 'info', 'warning', 'error', 'success'), $type );

            // Backwards compatibility...
        } elseif( $type == 'information' ) {
            $type = 'info';
        }

        // Make sure it's a valid message type
        if( !in_array($type, $this->msgTypes) ) die('"' . strip_tags($type) . '" is not a valid message type!' );

        // If the session array doesn't exist, create it
        if( !array_key_exists( $type, $_SESSION['flash_messages'] ) ) $_SESSION['flash_messages'][$type] = array();



        $_SESSION['flash_messages'][$type][] = $message;

        if( !is_null($redirect_to) ) {
            header("Location: $redirect_to");
            exit();
        }

        show($_SESSION);


        exit;

        return true;
    }

    /**
     *
     * @param $key
     * @return bool|string
     */
    public function getFlash($type = '', $print = false)
    {
        $messages = '';
        $data = '';

        if( !isset($_SESSION['flash_messages']) ) return false;

        if( $type == 'g' || $type == 'growl' ) {
            $this->displayGrowlMessages();
            return true;
        }

        // Print a certain type of message?
        if( in_array($type, $this->msgTypes) ) {
            foreach( $_SESSION['flash_messages'][$type] as $msg ) {
                $messages .= $this->msgBefore . $msg . $this->msgAfter;
            }

            $data .= sprintf($this->msgWrapper, $this->msgClass, $type, $messages);

            // Clear the viewed messages
            $this->clear($type);

            // Print ALL queued messages
        } elseif( $type == 'all' ) {
            foreach( $_SESSION['flash_messages'] as $type => $msgArray ) {
                $messages = '';
                foreach( $msgArray as $msg ) {
                    $messages .= $this->msgBefore . $msg . $this->msgAfter;
                }
                $data .= sprintf($this->msgWrapper, $this->msgClass, $type, $messages);
            }

            // Clear ALL of the messages
            $this->clear();

            // Invalid Message Type?
        } else {
            return false;
        }

        // Print everything to the screen or return the data
        if( $print ) {
            echo $data;
        } else {
            return $data;
        }

    }

    private function getMessageString($key, $messages)
    {
        return sprintf($this->flashMessageWrapper, 'flash', $key, $messages);
    }

    public function hasFlash($key, $default = 'flashMessages')
    {
        if( !is_null($key) ) {
            if( !empty($_SESSION['flash_messages'][$key]) ) return $_SESSION['flash_messages'][$key];
        } else {
            foreach( $this->msgTypes as $type ) {
                if( !empty($_SESSION['flash_messages']) ) return true;
            }
        }
        return false;
    }

    private function clearViewedMessage($key = '', $default = 'flashMessages')
    {
        if( $key == 'all' ) {
            unset($_SESSION['flash_messages']);
        } else {
            unset($_SESSION['flash_messages'][$key]);
        }
        return true;
    }

    /**
     * Display the Flash Message to browser
     *
     * @return mixed
     */
    public function __toString()
    {
        return $this->hasMessages();
    }
}