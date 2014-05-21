<?php

/**
 * Zend Framework
 *
 * PHP version 5
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category  Zend
 * @package   NA
 * @author    John <john@domain.com>
 * @copyright 2014 John Doe
 * @license   http://framework.zend.com/license/new-bsd     New BSD License
 * @version   SVN:<1.1>
 * @link      http://framework.zend.com/license/new-bsd description
 */


/**
 * DCore_Table Only simple class CRUD table data
 * Only use for simple query
 *
 * @category  DCore
 * @package   Zend
 * @author    Tran Hieu Dao <tran.hieu.dao@gmail.com>
 * @copyright 2014 Tran Hieu Dao
 * @license   http://framework.zend.com/license/new-bsd     Training at Sutrix Media
 * @version   Release:<1.1>
 * @link      http://framework.zend.com/license/new-bsd description
 * @see       Zend
 */

class DCore_Table
{

    private $_adapter;
    private $_tableName;
    private $_column = '*';
    private $_where = '';
    private $_primary = '';  //user set
    private $_data = array();
    private $_bindData = array();
    private $_primaryKey = array();  //set by code
    private $_errorCode = 0;
    private $_errorMessage = '';

    /**
     * Construct class
     *
     * @param type $table   Table name
     * @param type $primary Primary key
     *
     * @return \DCore_Table
     */
    public function __construct($table, $primary = '')
    {
        $this->_adapter = Zend_Db_Table::getDefaultAdapter();
        $this->_tableName = $table;
        $this->_primary = $primary;
        return $this;
    }
    /**
     * Set select for query
     *
     * @param type $column Column for select
     *
     * @return \DCore_Table
     */
    public function select($column='')
    {
        $this->_column = (empty($column))?"*":$column;
        return $this;
    }

    /**
     * Set condition for query
     *
     * @param type $where Condition
     *
     * @return \DCore_Table
     */
    public function where($where)
    {
        $this->_where = $where;
        return $this;
    }

    /**
     * Fetch raw data
     *
     * @return \DCore_Table
     */
    public function fetchRawData()
    {
        $select = $this->_adapter->select();
        $select->from($this->_tableName, $this->_column);
        if ($this->_where!='') {
            $select->where($this->_where);
        }
        $data = $this->_adapter->fetchAll($select);
        $this->_data = $data;
        return $this;
    }

    /**
     * Fetch assoc list
     *
     * @return type
     */
    public function fetchAssocList()
    {
        $this->fetchRawData();
        if ($this->_column!='*') {
            $data = DCore_DataProcessor::getOneKeyDataInArrayList($this->_data, $this->_column);
        } else {
            $data = $this->_data;
        }
        return $data;
    }

    /**
     * Static function to quick query
     *
     * @param DCore_Table $table  Table name to query
     * @param type        $column Column for select, can array
     *
     * @return type
     */
    static function getAssocData($table, $column='*')
    {
        $table = new DCore_Table($table);
        return $table->select($column)->fetchAssocList();
    }

    /**
     * Bind data
     *
     * @param type $data Pair column and value
     *
     * @return \DCore_Table|boolean
     */
    public function bind($data = array())
    {
        if (empty($data)) {
            return false;
        } else {
            $this->_bindData = $data;
        }
        return $this;
    }

    /**
     * Get column info
     *
     * @return type
     */

    public function getColumnInfo()
    {
        $sql = "SHOW COLUMNS FROM {$this->_tableName}";
        $info = $this->_adapter->fetchAll($sql);
        return $info;
    }

    /**
     * Get table primary key
     *
     * @return type
     */
    public function getPrimaryKey()
    {
        $info = $this->getColumnInfo();
        $priKeyList = DCore_DataProcessor::getKeyDataByKeyDataInArrayList($info, "Field", "Key", "PRI");
        return $priKeyList;
    }

    /**
     * Get action insert or update
     *
     * @return string
     */
    public function getAction()
    {
        if (!empty($this->_where)) {
            return 'update';
        }

        $arrPrimaryKey = $this->getPrimaryKey();
        $this->_primaryKey = $arrPrimaryKey;
        foreach ($arrPrimaryKey as $primaryKey) {
            if (@$this->_bindData[$primaryKey] != '') {
                return 'update';
            }
        }

        if (@$this->_bindData[$this->_primary] != '') {
            return 'update';
        }
        return 'insert';
    }

    /**
     * Unset primary key in _bindData
     *
     * @return \DCore_Table
     */
    public function cleanPrimaryKeyValue()
    {
        $arrPrimaryKey = ($this->_primaryKey)?$this->_primaryKey:$this->getPrimaryKey();
        $this->_primaryKey = $arrPrimaryKey;
        if (!empty($this->_primaryKey)) {
            foreach ($this->_primaryKey as $key) {
                unset($this->_bindData[$key]);
            }
        }
        return $this;
    }

    /**
     * Create where variable for conditional update
     *
     * @return type
     */
    public function buildUpdateCondition()
    {
        $where = array();
        if (!empty ($this->_where)) {
            $where = $this->_where;
        } else {
            $arrPrimaryKey = ($this->_primaryKey)?$this->_primaryKey:$this->getPrimaryKey();
            $where = array();
            if (count($arrPrimaryKey) > 0) {
                foreach ($arrPrimaryKey as $key) {
                    if ($this->_bindData[$key]) {
                        $where["`$key` = ?"] = $this->_bindData[$key];
                    }
                }
            } else if ($this->_bindData[$this->_primary]) {
                $where = array("`{$this->_primary}` = ?"=>"'{$this->_bindData[$this->_primary]}'");
            }
        }
        return $where;
    }

    /**
     * Get error code
     *
     * @return type
     */
    public function getErrorCode(){
        return $this->_errorCode;
    }

    /**
     * Get error message
     *
     * @return type
     */
    public function getErrorMessage(){
        return $this->_errorMessage;
    }

    /**
     * Store data to table, auto check insert or update
     *
     * @param type $where Condition
     * @param type $data  Data to insert or new data for update
     *
     * @return \DCore_Table|boolean
     */
    public function store($where = array(), $data = array())
    {
        if (empty($data) && empty($this->_bindData)) {
            return false;
        } else if (!empty($data)) {
            $this->_bindData = $data;
        }
        if (!empty($where)) {
            $this->_where = $where;
        }

        $action = $this->getAction();

        $this->cleanPrimaryKeyValue();
        if ($action == 'insert') {

            try{
                $this->_adapter->insert($this->_tableName, $this->_bindData);
                $this->_errorCode = 0;
                $this->_errorMessage = "Insert success";
            }catch(Exception $e){
                $this->_errorCode = $e->getCode();
                $this->_errorMessage = $e->getMessage();
            }

            return $this;
        }

        $where = (!empty($where))?$where:$this->buildUpdateCondition();
        try{
            $this->_adapter->update($this->_tableName, $this->_bindData, $where);
            $this->_errorCode = 0;
            $this->_errorMessage = "Update Success";
        }catch(Exception $e){
            $this->_errorCode = $e->getCode();
            $this->_errorMessage = $e->getMessage();
        }
        return $this;


    }

}