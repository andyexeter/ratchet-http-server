<?php

namespace Palmtree\RatchetWebServer\Controller;

/**
 * Class BlahController
 * @package    Palmtree\RatchetWebServer
 * @subpackage Controller
 */
class BlahController extends AbstractController
{
    /**
     *
     */
    public function index()
    {
        $this->template['heading'] = 'Contact';
        $this->template['content'] = 'My contact page';

        $this->response->setBody($this->template);
    }
}
