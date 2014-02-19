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
    protected $_name_field      = 'title';

    /**
     * "Belongs to" relationships
     *
     * @var array
     */
    protected $_belongs_to      = array(
        'user'          => array('model' => 'user', 'foreign_key' => 'user_id'),
        'bigcontent'    => array('model' => 'bigcontent', 'foreign_key' => 'content_id'),
    );

    protected $_has_hierarchy   = FALSE;
    protected $_tag_enable      = FALSE;
    protected $_is_direct_call  = FALSE;

    protected $_psearch_columns = array('title');

    protected $_ptable_columns  = array('id', 'title', 'description', 'state', 'photos');

    /**
     * Filter definitions for validation
     *
     * @return array
     */
    public function filters()
    {
        return parent::filters() + array(
            'content_id' => array(
                array('ORM::Check_Model', array(':value', 'bigcontent')),
            )
        );
    }

    public function set_content(Model_Bigcontent $content)
    {
        $this->bigcontent = $content;
        return $this;
    }

    public function to_paginate()
    {
        return parent::to_paginate()->sort('created_at', Paginate::SORT_DESC);
    }

    public function get_field($field)
    {
        switch (strtolower($field)):
            case 'description':
                return $this->content_as_featured_text();
                break;

            case 'photos':
                return $this->images
                        ->set_content($this)
                        ->gallery_index_url($this->total_photos());
                break;

            default :
                return parent::get_field($field);
                break;
        endswitch;
    }

    protected function link($action = 'index', $title = NULL, array $params = NULL, array $attributes = NULL, array $query = NULL)
    {
        empty($params) && $params = array();

        $model_id = $model = NULL;

        if ($this->bigcontent->loaded())
        {
            $model_id = $this->bigcontent->pk();
            $model    = $this->bigcontent->object_name();
        }

        $params += array('model_id' => $model_id, 'model' => $model);

        return parent::link($action, $title, $params, $attributes, $query);
    }

    protected function prepare_menu()
    {
        $menu = array();

        if ($this->bigcontent->loaded())
        {
            $menu[] = array(
                'title' => __(ORM::capitalize_title($this->bigcontent->object_name())),
                'url'   => $this->bigcontent->admin_update_url_only(),
            );
        }

        $menu = array_merge($menu, array(
            array(
                'title' => __('Galleries'),
                'url'   => $this->admin_index_url_only(),
            ),
            array(
                'title' => __($this->loaded() ? 'Update' : 'Add'),
                'url'   => $this->loaded()
                            ? $this->admin_update_url_only()
                            : $this->admin_create_url_only()
            ),
        ));

        if ($this->loaded() && $this->image_enable())
        {
            $menu[] = array(
                'title' => __('Images'),
                'url'   => $this->images
                                ->set_content($this)
                                ->gallery_index_url_only(),
            );
        }

        $this->_admin_menu = $menu;
    }

    /**
     * Set values from an array with support for one-one relationships.  This method should be used
     * for loading in post data, etc.
     *
     * @param  array $values   Array of column => val
     * @param  array $expected Array of keys to take from $values
     * @return ORM
     */
    public function values(array $values, array $expected = NULL)
    {
        if (isset($values['content_id']) && ($values['content_id'] == '0' || empty($values['content_id'])))
        {
            unset($values['content_id']);
        }

        if (NULL === $expected || empty($expected))
        {
            $expected = array('user_id', 'title', 'content', 'state', 'content_id');

            if ($this->featured_enable())
            {
                $expected[] = 'is_featured';
            }
        }

        return parent::values($values, $expected);
    }
}
