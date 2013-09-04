<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * @author  arie
 */

abstract class Malam_Model_Gallery extends Model_Bigcontent
{
    /**
     * Auto-update columns for updates
     *
     * @var string
     */
    protected $_updated_column  = array(
        'column'        => 'updated_at',
        'format'        => 'Y-m-d H:i:s'
    );

    /**
     * Auto-update columns for creation
     *
     * @var string
     */
    protected $_created_column  = array(
        'column'        => 'created_at',
        'format'        => 'Y-m-d H:i:s'
    );

    /**
     * @var array
     */
    protected $_sorting         = array(
        'created_at'    => 'DESC'
    );

    /**
     * Auto set for slug field
     *
     * @var bool
     */
    protected $_auto_slug       = TRUE;

    /**
     * Name Field
     *
     * @var string
     */
    protected $name_field       = 'title';

    /**
     * "Belongs to" relationships
     *
     * @var array
     */
    protected $_belongs_to      = array(
        'user'          => array('model' => 'user', 'foreign_key' => 'user_id'),
        'bigcontent'    => array('model' => 'bigcontent', 'foreign_key' => 'hierarchy_id'),
    );

    /**
     * Admin route name
     * @var string
     */
    protected $_admin_route_name = 'admin-gallery';

    /**
     * Route name
     * @var string
     */
    protected $_route_name      = 'gallery';

    protected $_has_hierarchy   = FALSE;
    protected $_tag_enable      = FALSE;
    protected $_is_direct_call  = FALSE;

    public function to_paginate()
    {
        return Paginate::factory($this)
            ->sort('created_at', Paginate::SORT_DESC)
            ->columns(array($this->primary_key(), 'title', 'description', 'state', 'photos'))
            ->search_columns(array('title'));
    }

    public function get_field($field)
    {
        switch (strtolower($field)):
            case 'description':
                return $this->content_as_featured_text();
                break;

            case 'photos':
                return $this->total_photos();
                break;

            default :
                return parent::get_field($field);
                break;
        endswitch;
    }
}
