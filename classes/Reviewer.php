<?php

require_once 'classes/utility/Database.php';
require_once 'classes/utility/Session.php';

class Reviewer {

    const PASSWORD_LIST = "assets/files/ReadablePasswordList.txt";
    
    private $_db;
    private $_reviewerID;
    private $_sessionName;

    public function __construct() {
        $this->_db = Database::getInstance();
        $this->_sessionName = 'reviewerID';
    }

    public function getData($item, $id) {
        $this->_db = $this->_db->select("reviewer_t", array('ReviewerID', '=', $id));
        $data = $this->_db->getFirstResult();
        return $data->$item;
    }

    private function getIdFromEmail($email) {
        $this->_db = $this->_db->select("reviewer_t", array('Email', '=', $email));
        $data = $this->_db->getFirstResult();
        return $data->ReviewerID;
    }

    private function generateNewPassword() {
        $word = file(self::PASSWORD_LIST, FILE_IGNORE_NEW_LINES);
        return $word[array_rand($word)] . mt_rand(1, 100);
    }

    public function isDisabled($email){
         $reviewerID = $this->getIdFromEmail($email);
         $isDisabled = $this->getData("IsDisabled", $reviewerID);
         if($isDisabled == 1){
             return true;
         }
         return false;
    }
    
    private function isLoginCredentialsMatch($email, $password) {
        $this->_db = $this->_db->select("reviewer_t", array('Email', '=', $email));
        $user = $this->_db->getFirstResult();

        if ($this->_db->getCount() > 0) {
            if ($user->Password == $password) {
                $this->_reviewerID = $user->ReviewerID;
                return true;
            }
        }
        return false;
    }

    private function isRequiredFieldsFilled($fields = array()) {
        foreach ($fields as $key => $value) {
            if ((!isset($key)) || ($value == '')) {
                return false;
            }
        }
        return true;
    }

    public function isValidLogin($fields = array()) {
        return $this->isRequiredFieldsFilled($fields);
    }

    public function login($email, $password) {
        $credentials = $this->isLoginCredentialsMatch($email, $password);

        if ($credentials) {
            Session::set($this->_sessionName, $this->_reviewerID);
            return true;
        }
        return false;
    }

    public function logout() {
        Session::delete($this->_sessionName);
    }

    public function resetPassword($email) {
        $reviewerID = $this->getIdFromEmail($email);
        $newPassword = $this->generateNewPassword();
        $isPasswordReset = $this->_db->update("reviewer_t", "ReviewerID", $reviewerID, array("Password" => $newPassword));

        //send new password to reviewer
        if (!$isPasswordReset) {
            $subject = 'Best Practices Cultural Competence New PASSWORD';
            $message = 'Hello ' . $this->getData('FirstName', $reviewerID) . 
                    ',\n\nYour New Password is: ' . $newPassword . ' . \n\nBest Practices Cultural Competence';
            mail($email, $subject, $message);
            return true;
        }
        return false;
    }
}