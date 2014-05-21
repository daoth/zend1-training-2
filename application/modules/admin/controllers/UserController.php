<?php

class Admin_UserController extends Zend_Controller_Action{

    public function indexAction(){

    }

    public function addAction(){

    }

    public function storeAction(){
        $params = $this->getRequest()->getParams();
        $userPostData = $params['user'];
        $table = new DCore_Table('user');
        $table->bind($userPostData)->store();
    }
}