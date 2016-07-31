<?php

namespace Palmtree\RatchetWebServer\Controller;

class AboutController extends AbstractController {
	public function index() {
		$this->template->addData( 'heading', 'About Us' );

		$this->response->setBody( $this->template->fetch( 'default' ) );
	}
}
