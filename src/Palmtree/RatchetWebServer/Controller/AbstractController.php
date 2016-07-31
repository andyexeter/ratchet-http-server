<?php

namespace Palmtree\RatchetWebServer\Controller;

use Guzzle\Http\Message\RequestInterface;
use Guzzle\Http\Message\Response;
use Monolog\Logger;
use Palmtree\Service\Config;
use Palmtree\Service\Template;
use Ratchet\ConnectionInterface;
use Ratchet\Http\HttpServerInterface;

/**
 * Class AbstractController
 * @package    Palmtree\RatchetWebServer
 * @subpackage Controller
 */
abstract class AbstractController implements HttpServerInterface {
	/**
	 * @var RequestInterface
	 */
	protected $request;
	/**
	 * @var Response
	 */
	protected $response;
	/**
	 * @var Template
	 */
	protected $template;
	/**
	 * @var Config
	 */
	protected $config;
	/**
	 * @var Logger
	 */
	protected $logger;

	/**
	 * AbstractController constructor.
	 *
	 * @param Config $config
	 * @param Logger $logger
	 */
	public function __construct( Config $config, Logger $logger ) {
		$this->config = $config;
		$this->logger = $logger;

		$this->response = new Response( 200, [
			'Content-Type' => 'text/html; charset=utf-8',
		] );

		$this->template = new Template( [
			'path' => $this->config->get( 'view_dir' ),
		] );
	}

	/**
	 * @return mixed
	 */
	abstract public function index();

	/**
	 * @inheritDoc
	 */
	public function onOpen( ConnectionInterface $conn, RequestInterface $request = null ) {
		$this->request = $request;

		$this->index();

		$this->close( $conn );
	}

	/**
	 * @inheritDoc
	 */
	public function onClose( ConnectionInterface $conn ) {
		$format = '%s - - "%s %s %s" %d "%s" "%s"';

		$this->logger->addInfo( vsprintf( $format, [
			$conn->remoteAddress,
			$this->request->getMethod(),
			$this->request->getPath(),
			'HTTP/' . $this->request->getProtocolVersion(),
			$this->response->getStatusCode(),
			$this->request->getHost(),
			$this->request->getHeader( 'User-Agent' ),
		] ) );
	}

	/**
	 * @inheritDoc
	 */
	public function onError( ConnectionInterface $conn, \Exception $e ) {
	}

	/**
	 * @inheritDoc
	 */
	public function onMessage( ConnectionInterface $from, $msg ) {
	}

	/**
	 * @param ConnectionInterface $conn
	 */
	protected function close( ConnectionInterface $conn ) {
		$conn->send( $this->response );
		$conn->close();
	}
}
