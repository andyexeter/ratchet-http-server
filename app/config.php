<?php

$config = [ ];

$config['root_dir'] = realpath( dirname( __DIR__ ) );

$config['app_dir']  = $config['root_dir'] . '/app';
$config['view_dir'] = $config['app_dir'] . '/view';

$config['var_dir']  = $config['root_dir'] . '/var';
$config['log_dir']  = $config['var_dir'] . '/log';

return $config;
