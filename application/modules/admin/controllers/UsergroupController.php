<?php

class Admin_UsergroupController extends Zend_Controller_Action{

    public function indexAction(){

    }

    public function addAction(){

    }

    public function storeAction(){
        $data = $this->getRequest()->getParam('data');
        $table = new DCore_Table('usergroup');
        $table->bind($data)->store();
        $this->_redirect($this->view->url(array("module"=>"admin", "controller"=>"usergroup", "action"=>"add")));
    }
}