<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class DCore_Controller_Abc extends Zend_Controller_Plugin_Abstract{

    public function init(){

    }
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
        $lang = "en";
        $languageDir = APPLICATION_PATH . "/languages/$lang/";
        $options = array('separator' => '=');
        $config = Zend_Registry::get('configuration');
        try {
            $p_controller = $this->_request->getControllerName();

            $p_module = $this->_request->getModuleName();

            // get language file
            try {
                $languageDir = $config->site->frontend->language_dir . DS . $lang . DS;
                $languageModuleDir = $languageDir . $p_module . DS;
                $translate = new Zend_Translate('ini', $languageDir . "$lang.ini", "$lang", $options);
                $translate->getAdapter()->addTranslation($languageModuleDir . "module.lang.ini", "$lang", $options);

                // add script path for each module
                //$this->view->addScriptPath(APPLICATION_PATH . "/modules/".$p_module."/views/helpers/partial");
            } catch (Exception $e) {}
            //Zend_Registry::set('Zend_Translate', $translate);
            //$this->view->assign('translate', $translate);

            $registry = Zend_Registry::getInstance();
            $registry->set('Zend_Translate', $translate);
            //$translate->setLocale('fr');
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}