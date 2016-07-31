<?php

namespace Palmtree\Service;

/**
 * Class Template
 * @package    Palmtree\Service
 */
class Template implements \ArrayAccess {
	/**
	 * @var string
	 */
	private $path = '';
	/**
	 * @var array
	 */
	private $data = [ ];

	/**
	 * Template constructor.
	 *
	 * @param array $args
	 */
	public function __construct( array $args = [ ] ) {
		if ( isset( $args['path'] ) ) {
			$this->setPath( $args['path'] );
		}

		if ( isset( $args['data'] ) ) {
			$this->setData( $args['data'] );
		}
	}

	/**
	 * @param $file
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function fetch( $file ) {
		$path = $this->getPath();

		if ( ! empty( $path ) ) {
			$file = $path . $file;
		}

		$file = dirname( $file ) . '/' . basename( $file, '.php' ) . '.php';

		if ( ! file_exists( $file ) ) {
			throw new \Exception( "The file '$file' does not exist." );
		}

		extract( $this->getData() );

		ob_start();

		include $file;

		return ob_get_clean();
	}

	/**
	 * @param string $key
	 * @param mixed  $value
	 *
	 * @return Template
	 */
	public function addData( $key, $value ) {
		$this->data[ $key ] = $value;

		return $this;
	}

	/**
	 * @param array $data
	 *
	 * @return Template
	 */
	public function setData( array $data ) {
		$this->data = $data;

		return $this;
	}

	/**
	 * @param null $key
	 *
	 * @return mixed
	 */
	public function getData( $key = null ) {
		if ( $key === null ) {
			return $this->data;
		}

		return ( isset( $this->data[ $key ] ) ) ? $this->data[ $key ] : null;
	}

	public function removeData( $key ) {
		unset( $this->data[ $key ] );
	}

	/**
	 * @param string $path
	 *
	 * @return Template
	 */
	public function setPath( $path ) {
		$this->path = rtrim( $path, DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getPath() {
		return $this->path;
	}

	public function offsetExists( $offset ) {
		$data = $this->getData( $offset );

		return $data !== null;
	}

	public function offsetGet( $offset ) {
		return $this->getData( $offset );
	}

	public function offsetSet( $offset, $value ) {
		$this->addData( $offset, $value );
	}

	public function offsetUnset( $offset ) {
		$this->removeData( $offset );
	}
}
