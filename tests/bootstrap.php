<?php

/* @var $loader \Composer\Autoload\ClassLoader */
$loader = require __DIR__.'/../vendor/autoload.php';

$classMap1 = \Composer\Autoload\ClassMapGenerator::createMap(__DIR__);
$loader->addClassMap($classMap1);

error_reporting(E_ALL);

$configApiFilePath = dirname(__DIR__) . '/SEOstats/Config/ApiKeys.php';

$configApi = file_get_contents($configApiFilePath);

$configApi = preg_replace("#(\s+const MOZSCAPE_ACCESS_ID\s+=) \'\';#",           "\$1 'MOZSCAPE_ACCESS_ID';", $configApi);
$configApi = preg_replace("#(\s+const MOZSCAPE_SECRET_KEY\s+=) \'\';#",          "\$1 'MOZSCAPE_SECRET_KEY';", $configApi);
$configApi = preg_replace("#(\s+const GOOGLE_SIMPLE_API_ACCESS_KEY\s+=) \'\';#", "\$1 'GOOGLE_SIMPLE_API_ACCESS_KEY';", $configApi);


file_put_contents($configApiFilePath, $configApi);
