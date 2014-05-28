<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
//        $profiler = new Zend_Db_Profiler_Firebug('All DB Queries');
//        $profiler->setEnabled(true);

//        $params = array(
//            'host' => 'localhost',
//            'username' => 'root',
//            'password' => '',
//            'dbname' => 'test',
//            'profiler' => $profiler
//        );
//
//        $db = Zend_Db::factory('PDO_MYSQL', $params);

        //$db->setProfiler(array('enabled' => true, 'class' => "Zend_Db_Profiler_Firebug"));


        $keyword = 'a';
        $u = new Model_Album();
        $select = $u->select ();
        $select->where ( "`description` like ?", "%$keyword%" );
        $data = $u->fetchAll($select)->toArray();
        $this->view->data = $data;
//        //print_r($data);
//        $arr = $data->toArray();


    }

    public function search($column, $keyword)
    {
        $album = new Model_Album();
        $select = $u->select();
        $select->where("$column = ?", $keyword);
        $data = $u->fetchAll($select);
        print_r($data);exit();
    }

}

