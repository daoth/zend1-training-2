<?php

class Album_IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $profiler = new Zend_Db_Profiler_Firebug('All DB Queries');
        $profiler->setEnabled(true);
    }

    public function indexAction()
    {
        $this->_helper->layout()->setLayout("album");
        //$this->_helper->viewRenderer->setNoRender(true);
    }

}

