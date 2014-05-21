<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

define('BOX_PATH', realpath(dirname(__FILE__)."/.."));

defined('BASE_PATH') || define('BASE_PATH', BOX_PATH);

defined('DS') || define('DS', DIRECTORY_SEPARATOR);
defined('PS') || define('PS', PATH_SEPARATOR);

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    BASE_PATH . '/configs/application.ini'
);
$application->bootstrap()
            ->run();