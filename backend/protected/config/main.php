<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'timeZone' => 'Asia/Jakarta',
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'runtimePath'=> dirname(__FILE__) .'/../../../hotel_assets',
    'name' => 'Sistem Manajemen Hotel',
    // preloading 'log' component
    'preload' => array('log'),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
        'ext.giix-components.*', // giix components
    ),
    'language'=>'id',
    'modules' => array(
        // uncomment the following to enable the Gii tool
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'generatorPaths' => array(
                //'ext.giix-core', // giix generators
                'ext.phpextjs'
            ),
            'password' => 'zzz',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('127.0.0.1', '::1'),
        ),
    ),
    // application components
    'components' => array(
        'assetManager'=>array(
            'basePath' => dirname(__FILE__) .'/../../../hotel_assets',
            'baseUrl'=> DIRECTORY_SEPARATOR.'hotelextjs/hotel_assets',
        ),
        'user' => array(
            'class'=>'WebUser',
            // enable cookie-based authentication
            //'allowAutoLogin' => true,
        ),
        'cache'=>array(
            'class'=>'system.caching.CFileCache',
        ),
        // uncomment the following to enable URLs in path-format
        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => false,
            'rules' => array(
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),
        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
                array(
                    'class'=>'CFileLogRoute',
                    'levels'=>'info, error, warning',
                    'categories'=>'',
                ),
            ),
        ),
        'db' => include "connection.php",
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => include "config_special.php",
);
