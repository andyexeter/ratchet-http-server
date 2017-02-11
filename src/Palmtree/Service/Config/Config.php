<?php

namespace Palmtree\Service\Config;

/**
 * Class Config
 * @package    Palmtree\Service
 * @subpackage Config
 */

/**
 * Class Config
 * @package Palmtree\Service\Config
 */
class Config implements \ArrayAccess, \Serializable {
	/**
	 * @var array
	 */
	private $data = [];

	/**
	 * Config constructor.
	 *
	 * @param array $config
	 */
	public function __construct( array $config = [] ) {
		foreach ( $config as $key => $value ) {
			$this->set( $key, $value );
		}
	}

	public function all() {
		return $this->data;
	}

	public function merge( $parameters ) {
		$this->data = array_replace_recursive( $this->data, $parameters );
	}

	/**
	 * @param $key
	 *
	 * @return bool
	 */
	public function has( $key ) {
		return isset( $this->data[ $key ] ) || array_key_exists( $key, $this->data );
	}

	/**
	 * @param $key
	 *
	 * @return mixed|null
	 */
	public function get( $key ) {
		return $this->has( $key ) ? $this->data[ $key ] : null;
	}

	/**
	 * @param $key
	 * @param $value
	 */
	public function set( $key, $value ) {
		$this->data[ $key ] = $value;
	}

	/**
	 * @param $key
	 */
	public function remove( $key ) {
		unset( $this->data[ $key ] );
	}

	/**
	 * @inheritDoc
	 */
	public function offsetExists( $offset ) {
		return $this->has( $offset );
	}

	/**
	 * @inheritDoc
	 */
	public function offsetGet( $offset ) {
		return $this->get( $offset );
	}

	/**
	 * @inheritDoc
	 */
	public function offsetSet( $offset, $value ) {
		$this->set( $offset, $value );
	}

	/**
	 * @inheritDoc
	 */
	public function offsetUnset( $offset ) {
		$this->remove( $offset );
	}

	/**
	 * @inheritDoc
	 */
	public function serialize() {
		return serialize( $this->data );
	}

	/**
	 * @inheritDoc
	 */
	public function unserialize( $serialized ) {
		return unserialize( $serialized );
	}
}
