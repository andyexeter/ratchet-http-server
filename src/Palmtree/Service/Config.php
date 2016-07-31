<?php

namespace Palmtree\Service;

/**
 * Class Config
 * @package Palmtree\Service
 */
class Config {
	/**
	 * @var array
	 */
	private $data = [ ];

	/**
	 * Config constructor.
	 *
	 * @param array $config
	 */
	public function __construct( array $config = [ ] ) {
		foreach ( $config as $key => $value ) {
			$this->set( $key, $value );
		}
	}

	/**
	 * @param $key
	 *
	 * @return mixed|null
	 */
	public function get( $key ) {
		return ( isset( $this->data[ $key ] ) ) ? $this->data[ $key ] : null;
	}

	/**
	 * @param $key
	 * @param $value
	 */
	public function set( $key, $value ) {
		$this->data[ $key ] = $value;
	}
}
