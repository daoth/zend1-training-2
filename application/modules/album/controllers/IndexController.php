<?php

class Album_IndexController extends Zend_Controller_Action
{

    public function init()
    {


//        $profiler = new Zend_Db_Profiler_Firebug('All DB Queries');
//        $profiler->setEnabled(true);


        $acl = new Zend_Acl();

        $acl->addRole(new Zend_Acl_Role('guest'))
                ->addRole(new Zend_Acl_Role('member'))
                ->addRole(new Zend_Acl_Role('admin'));

        $parents = array('guest', 'member', 'admin');
        $acl->addRole(new Zend_Acl_Role('someUser'), $parents);

        $acl->add(new Zend_Acl_Resource('someResource'));

        $acl->deny('guest', 'someResource');
        $acl->allow('member', 'someResource');

        $data = $acl->isAllowed('someUser', 'someResource') ? 'allowed' : 'denied';

        $this->view->data = $data;


    }

    public function indexAction()
    {
        $this->_helper->layout()->setLayout("album");
        //$this->_helper->viewRenderer->setNoRender(true);
    }

    public function uploadAction(){
        $params = $this->getRequest()->getParams();
        print_r($params);
        exit();
    }
}

