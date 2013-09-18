<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * @author  arie
 */

/* Dashboard Prefix */
$DPRX = Kohana::$config->load('site.dashboard_prefix');

return array(
    // Gallery -----------------------------------------------------------------
    'gallery'               => array(
        'uri_callback'      => 'galleries(/<model>/<model_id>)/<action>(/<id>/<slug>)',
        'regex'             => array(
            'model_id'      => '\d+',
            'model'         => '[a-zA-Z0-9-_]+',
            'id'            => '\d+',
            'slug'          => '[a-zA-Z0-9-_]+',
            'action'        => 'read|index'
        ),
        'defaults'          => array(
            'controller'    => 'gallery',
            'action'        => 'index',
            'id'            => NULL,
            'slug'          => NULL,
            'model_id'      => NULL,
            'model'         => NULL,
        )
    ),

    'admin-gallery'         => array(
        'uri_callback'      => $DPRX.'galleries(/<model>/<model_id>)/<action>(/<id>)',
        'regex'             => array(
            'action'        => 'index|create|delete|update|read',
            'id'            => '\d+',
            'model_id'      => '\d+',
            'model'         => '[a-zA-Z0-9-_]+',
        ),
        'defaults'          => array(
            'controller'    => 'gallery',
            'directory'     => 'admin',
            'action'        => 'index',
            'id'            => NULL,
            'slug'          => NULL,
            'model_id'      => NULL,
            'model'         => NULL,
        )
    ),
);
