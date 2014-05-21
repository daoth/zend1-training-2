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
 * DCore_DataProcessor Some simple function to get or manipulate data
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

class DCore_DataProcessor{

    public function test(){
        print "ok";
    }

    static function getOneKeyDataInArrayList($array, $childKey){
        $newArray = array();
        array_walk($array, function($item, $key) use(&$newArray, $childKey){
            $newArray[$key] = $item[$childKey];
        });
        return $array;
    }
    static function getKeyDataByKeyDataInArrayList($array, $keyGet, $keyCheck, $keyCheckValue){
        $newArray = array();
        array_walk($array, function($item, $key) use(&$newArray, $keyGet, $keyCheck, $keyCheckValue){
            if($item[$keyCheck] == $keyCheckValue){
                $newArray[$key] = $item[$keyGet];
            }
        });
        return $newArray;
    }
}