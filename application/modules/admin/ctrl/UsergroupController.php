<?php

class Admin_UsergroupController extends Zend_Controller_Action{

    public function indexAction(){

    }

    public function addAction(){

    }

    public function storeAction(){
        $data = $this->getRequest()->getParam('data');
        $table = new DCore_Table('usergroup');
        $method = $this->getRequest()->getMethod();
        if ($this->getRequest()->isPost()) {
            $result = $table->bind($data)->store()->getErrorCode();
        }else{
            $this->_redirect($this->view->url(array("module"=>"admin", "controller"=>"usergroup", "action"=>"add")));
        }

        $this->renderScript("usergroup/add.phtml");
        $message = $table->getErrorMessage();
        $this->view->errorCode = $result;
        $this->view->errorMessage = $message;

    }
}