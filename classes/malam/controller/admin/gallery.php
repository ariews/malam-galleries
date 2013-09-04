<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * @author  arie
 */

abstract class Malam_Controller_Admin_Gallery extends Controller_Abstract_Bigcontent
{
    /**
     * Bigcontent
     *
     * @var Model_Gallery
     */
    protected $model    = 'gallery';

    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
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

        echo Debug::vars($this->model->images->find_all()->count());
    }

    public function action_delete()
    {
        $this->title('Delete Gallery');
    }
}