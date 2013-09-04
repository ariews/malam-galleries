<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * @author  arie
 */

$p  = ORM::factory('gallery');

return array(
    'admin' => array(
        // ADMIN-PROVIDER
        503    => array(
            'title'     => __('Gallery'),
            'url'       => $p->admin_index_url_only(),
        ),
    ),
);