<?php

namespace Palmtree\RatchetWebServer\Controller;

/**
 * Class AboutController
 * @package    Palmtree\RatchetWebServer
 * @subpackage Controller
 */
class AboutController extends AbstractController
{
    /**
     *
     */
    public function index()
    {
        $this->template['heading'] = 'About Us';
        $this->template['content'] = 'My about page';

        $this->response->setBody($this->template);
    }
}
