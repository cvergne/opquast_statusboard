<?php
    /*
        Config
    */
    define('CONFIG_DEFAULT_LANG', 'en');
    define('CONFIG_DATASOURCES_DIR', './datasources/');
    define('CONFIG_DATATARGET_DIRNAME', '/rss/');
    define('CONFIG_DATATARGET_DIR', '.' . CONFIG_DATATARGET_DIRNAME);
    define('CONFIG_DEFAULT_FILENAME', 'opquast-list-');
    define('CONFIG_DEFAULT_FILEEXT', '.csv');
    define('CONFIG_DEFAULT_GROUPBY', 2);
    define('CONFIG_SOURCE_PROVIDER', 'Opquast');

    $config_langs = array('en', 'fr');
    $config_langs_iso = array(
        'en' => 'en-us',
        'fr' => 'fr-fr'
    );
    $config_groupbys = array(
        'level' => 2,
        'category' => 1
    );

    /*
        Vars
    */
        $out = array();

    /*
        Functions
    */
    function filter_filename($src) {
        return preg_replace('/[^A-Za-z0-9\.\-\_]/', '', mb_convert_encoding($src, 'UTF-8', 'HTML-ENTITIES'));
    }

    function getURL($file) {
        $pageURL = 'http';
        if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
            $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
        }
        $pageURL = pathinfo($pageURL);
        return $pageURL['dirname'] . CONFIG_DATATARGET_DIRNAME . $file;
    }