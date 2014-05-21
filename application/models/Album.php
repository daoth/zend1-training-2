<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Model_Album extends Zend_Db_Table_Abstract
{

    protected $_name = 'album';

    protected $_primary = 'id';

    protected $_sequence = false;

    /**
     * Retrieve string data
     *
     * Get table name
     */
    public function getTableName()
    {
        return $this->_name;
    }


}