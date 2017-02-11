<?php

namespace Palmtree\RatchetWebServer\Controller;

/**
 * Class HomeController
 * @package    Palmtree\RatchetWebServer
 * @subpackage Controller
 */
class HomeController extends AbstractController {
	/**
	 *
	 */
	public function index() {
		$this->template['heading'] = 'Hello World!';

		$number  = $this->request->getQuery()->get( 'num' );
		$content = ( is_null( $number ) ) ? 'Enter a number...' : "You entered $number.";

		$this->template['content'] = $content;

		$this->response->setBody( $this->template );
	}
}
