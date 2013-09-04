<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * @author  arie
 */

abstract class Malam_Controller_Admin_Gallery extends Controller_Abstract_Bigcontent
{
    /**
     * Gallery
     *
     * @var Model_Gallery
     */
    protected $model            = 'gallery';

    /**
     * Bigcontent
     *
     * @var Model_Bigcontent
     */
    protected $content_id       = NULL;

    public function __construct(Request $request, Response $response)
    {
        $model    = $request->param('model');
        $model_id = $request->param('model_id');

        $gallery  = ORM::factory('gallery');
        /* @var $gallery Model_Gallery */

        $model_loaded = $model && $model_id;

        if ($model_loaded)
        {
            $content = $this->content = ORM::factory($model)
                    ->find_by_id($model_id)->find();

            if (! $content->loaded())
            {
                throw new HTTP_Exception_404();
            }

            $this->content_id = $content->pk();
            $gallery->set_content($content);
        }

        $this->model = $gallery->where('content_id', '=', $this->content_id);

        parent::__construct($request, $response);

        if ($this->model->bigcontent->loaded() && !($model_loaded))
        {
            throw new HTTP_Exception_404();
        }
    }

    public function action_index()
    {
        $this->title('Galleries index');
    }

    public function action_create()
    {
        $this->title('Create Gallery');
    }

    public function action_update()
    {
        $this->title('Update Gallery');
    }

    public function action_delete()
    {
        $this->title('Delete Gallery');
    }

    protected function _create_or_update()
    {
        $this->temporary->set(array(
            'content_id' => $this->content_id
        ));

        parent::_create_or_update();
    }
}