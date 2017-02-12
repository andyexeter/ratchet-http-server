<?php

namespace Palmtree\RatchetWebServer\Controller;

/**
 * Class NotFoundController
 * @package    Palmtree\RatchetWebServer
 * @subpackage Controller
 */
class NotFoundController extends AbstractController
{
    /**
     *
     */
    public function index()
    {
        $this->response->setStatus(404);
        $this->response->setBody('404 Not Found.');
    }
}
