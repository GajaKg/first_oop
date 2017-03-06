<?php

class Session {
    
    private $logged_in;
    private $user_id;
    private $message;
    
    function __construct(){
        session_start();
        $this->check_login();
        $this->check_message();
    }
    
    public function login($found_admin){
        if ($found_admin){
            $this->logged_in = true;
            $this->user_id = $_SESSION['user_id'] = $found_admin->id;
        }
    }
    
    public function logout(){
        if ($this->user_id){
            unset($this->user_id);
            unset($_SESSION['user_id']);
            $this->logged_in = false;
        }
    }
    
    public function is_logged_in(){
        return $this->logged_in;
    }
    
    private function check_login(){
        if (isset($_SESSION['user_id'])){
            $this->logged_in = true;
            $this->user_id = $_SESSION['user_id'];
        } else {
            unset($_SESSION['user_id']);
            $this->logged_in = false;
        }
    }
    
    public function message($msg=""){
        $_SESSION['message'] = $msg;
        $this->message = $_SESSION['message'];
        return $this->message;
        
    }

    private function check_message(){
        if (isset($_SESSION['message'])){
            $this->message = $_SESSION['message'];
            unset($_SESSION['message']);
        } else {
            $this->message = "";
        }
    }
    
}


$session = new Session;





?>