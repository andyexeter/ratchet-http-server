<?php
namespace Palmtree\RatchetWebServer\Http;

use Palmtree\RatchetWebServer\Controller\NotFoundController;
use Ratchet\ConnectionInterface;
use Guzzle\Http\Message\RequestInterface;
use Guzzle\Http\Message\Response;
use Guzzle\Http\Url;
use Ratchet\Http\HttpServerInterface;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class Router implements HttpServerInterface {
	/**
	 * @var \Symfony\Component\Routing\Matcher\UrlMatcherInterface
	 */
	protected $_matcher;
	protected $_notFoundController;

	public function __construct( UrlMatcherInterface $matcher, HttpServerInterface $notFoundController ) {
		$this->_matcher = $matcher;
		$this->_notFoundController = $notFoundController;
	}

	/**
	 * {@inheritdoc}
	 * @throws \UnexpectedValueException If a controller is not \Ratchet\Http\HttpServerInterface
	 */
	public function onOpen( ConnectionInterface $conn, RequestInterface $request = null ) {
		if ( null === $request ) {
			throw new \UnexpectedValueException( '$request can not be null' );
		}

		$context = $this->_matcher->getContext();
		$context->setMethod( $request->getMethod() );
		$context->setHost( $request->getHost() );

		try {
			$route = $this->_matcher->match( $request->getPath() );
		} catch ( MethodNotAllowedException $nae ) {
			return $this->close( $conn, 403 );
		} catch ( ResourceNotFoundException $nfe ) {
			$route['_controller'] = $this->_notFoundController;
		}

		if ( is_string( $route['_controller'] ) && class_exists( $route['_controller'] ) ) {
			$route['_controller'] = new $route['_controller'];
		}

		if ( ! ( $route['_controller'] instanceof HttpServerInterface ) ) {
			throw new \UnexpectedValueException( 'All routes must implement Ratchet\Http\HttpServerInterface' );
		}

		$parameters = array();
		foreach ( $route as $key => $value ) {
			if ( ( is_string( $key ) ) && ( '_' !== substr( $key, 0, 1 ) ) ) {
				$parameters[ $key ] = $value;
			}
		}
		$parameters = array_merge( $parameters, $request->getQuery()->getAll() );

		$url = Url::factory( $request->getPath() );
		$url->setQuery( $parameters );
		$request->setUrl( $url );

		$conn->controller = $route['_controller'];
		$conn->controller->onOpen( $conn, $request );

		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	function onMessage( ConnectionInterface $from, $msg ) {
		$from->controller->onMessage( $from, $msg );
	}

	/**
	 * {@inheritdoc}
	 */
	function onClose( ConnectionInterface $conn ) {
		if ( isset( $conn->controller ) ) {
			$conn->controller->onClose( $conn );
		}
	}

	/**
	 * {@inheritdoc}
	 */
	function onError( ConnectionInterface $conn, \Exception $e ) {
		if ( isset( $conn->controller ) ) {
			$conn->controller->onError( $conn, $e );
		}
	}

	/**
	 * Close a connection with an HTTP response
	 *
	 * @param \Ratchet\ConnectionInterface $conn
	 * @param int                          $code HTTP status code
	 *
	 * @return null
	 */
	protected function close( ConnectionInterface $conn, $code = 400 ) {
		$response = new Response( $code, array(
			'X-Powered-By' => \Ratchet\VERSION,
		) );

		$conn->send( (string) $response );
		$conn->close();
	}
}
