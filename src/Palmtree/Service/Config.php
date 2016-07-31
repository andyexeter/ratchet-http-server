<?php

namespace Palmtree\Service;

class Config {
	private $data = [ ];

	public function __construct( array $config = [ ] ) {
		foreach ( $config as $key => $value ) {
			$this->set( $key, $value );
		}
	}

	public function get( $key ) {
		return ( isset( $this->data[ $key ] ) ) ? $this->data[ $key ] : null;
	}

	public function set( $key, $value ) {
		$this->data[ $key ] = $value;
	}
}
