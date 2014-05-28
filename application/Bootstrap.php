<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected $_frontController;

    protected function _initSetupModularDirectoryStructure(){
        $this->bootstrap('frontController');
        $this->_frontController = $this->getResource('frontController');
        $this->_frontController->throwExceptions(true);

        $path = BASE_PATH . DS . 'configs' . DS . 'application.ini';
        $conf = new Zend_Config_Ini($path, 'production');

        $moduleControllerDirectoryName = $conf->resources->frontController->moduleControllerDirectoryName;
        $controllerDirectory = $this->_frontController->getControllerDirectory();
        foreach($controllerDirectory as $k=>$v){
            $newPath = str_replace('controllers', $moduleControllerDirectoryName, $v);
            $this->_frontController->addControllerDirectory($newPath, $k);
        }
    }
    protected function _initAutoload()
    {
        // Ensure front controller instance is present
        $controllerDirectory = $this->_frontController->getControllerDirectory();

        $moduleLoader = new Zend_Application_Module_Autoloader(array('namespace' => '', 'basePath' => APPLICATION_PATH));



    }

    protected function _initConfig()
    {
        $path = BASE_PATH . DS . 'configs' . DS . 'configuration.ini';
        $conf = new Zend_Config_Ini($path, 'bootstrap', array('skipExtends' => false, 'allowModifications' => true));
        Zend_Registry::set('configuration', $conf);
    }

    protected function _initView()
    {
        $view = new Zend_View();

        //ZendX Jquery
        $view->addHelperPath('ZendX/JQuery/View/Helper/', 'ZendX_JQuery_View_Helper');
        $viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
        $viewRenderer->setView($view);
        Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);
        //ZendX Jquery

        // Instantiate and add the helper in one go
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
        $viewRenderer->setView($view);
        $viewRenderer->setViewSuffix('phtml');
        // Initialise Zend_Layout's MVC helpers
        // by default with layout config option in ../configs/application.ini
        return $view;
    }

    protected function _initViewSettings()
    {
        try {
            $config = Zend_Registry::get('configuration');
        } catch (Exception $e) {
            $this->_initConfig();
            $config = Zend_Registry::get('configuration');
        }

        $this->bootstrap('view');
        $this->_view = $this->getResource('view');
        $this->_view->addScriptPath(APPLICATION_PATH . "/layouts/partial");

        $this->_view->headMeta()->appendHttpEquiv('Content-Type', 'text/html;charset=' . $config->charset);
        $this->_view->headMeta()->appendHttpEquiv('Content-Language', 'en-US');
        $this->_view->headMeta()->appendName('keywords', $config->meta->keywords);
        $this->_view->headMeta()->appendName('author', $config->meta->author);
        $this->_view->headMeta()->appendName('description', $config->meta->description);
        $this->_view->headMeta()->appendName('robots', $config->meta->robots);
        $this->_view->headMeta()->appendName('revisit-after', $config->meta->revisit_after);
        $this->_view->headMeta()->appendName('copyright', $config->meta->copyright);

        $this->_view->setEncoding('UTF-8');
        $this->_view->doctype('XHTML1_STRICT');
        // Setting up title
//        $this->_view->headTitle()->setSeparator(' - ');
//        $this->_view->headTitle($config->novabox->title);
        // Load CSS
//        $this->_view->headLink()->appendStylesheet('/public/css/screen.css', 'screen');
//        $this->_view->headLink()->appendStylesheet('/public/css/print.css', 'print');
//        $this->_view->headLink(
//            array('rel' => 'shortcut icon', 'href' => $config->base->url . 'public/favicon.ico', 'type' => 'image/x-icon'), 'PREPEND'
//        );


//        require_once 'Zend/Loader.php';
//        Zend_Loader::loadClass('Zend_Translate');
//
//        $fr = array(
//            'good_morning' => 'Bon jour',
//            'how_are_you' => 'Comment allez-vous?'
//        );
//
//        $tr = new Zend_Translate('array', $fr, 'fr');
//        echo $tr->translate('good_morning', 'fr') . ', Pascal!';


    }

    protected function _initTranslate()
    {


//        $translate = new Zend_Translate('gettext',
//                    APPLICATION_PATH . "/languages/",
//                    null,
//                    array('scan' => Zend_Translate::LOCALE_DIRECTORY));
//        $registry = Zend_Registry::getInstance();
//        $registry->set('Zend_Translate', $translate);
//        $translate->setLocale('en');
    }

    protected function _initZFDebug()
    {

        $path = BASE_PATH . DS . 'configs' . DS . 'application.ini';
        $conf = new Zend_Config_Ini($path, 'production', array('skipExtends' => false, 'allowModifications' => true));
        //Zend_Registry::set('configuration', $conf);

        $db = $conf->resources->db;
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace('ZFDebug');

        $options = array(
            'plugins' => array('Variables',
                'Database' => array('adapter' => $db),
                'File' => array('basePath' => '/path/to/project'),
                //'Cache' => array('backend' => $cache->getBackend()),
                'Exception')
        );
        $debug = new ZFDebug_Controller_Plugin_Debug($options);

        $this->bootstrap('frontController');
        $frontController = $this->getResource('frontController');
        $frontController->registerPlugin($debug);
    }

}

