<?php

namespace Palmtree\Service\Config;

use Symfony\Component\Yaml\Yaml;

/**
 * Class ConfigParser
 * @package Palmtree\Service\Config
 */
class ConfigParser {
	/**
	 * @var array
	 */
	protected $parameters;

	protected $config;

	/**
	 * ConfigParser constructor.
	 *
	 * @param $filename
	 */
	public function __construct( $filename, Config $config ) {
		$this->filename = $filename;

		$this->config = $config;

		$this->parameters = $this->interpolate( $this->read(), $config->all() );
	}

	/**
	 * @param array $parameters
	 *
	 * @return array
	 */
	protected function interpolate( array $parameters, $config = [] ) {
		$ret = [];
		foreach ( $parameters as $key => $value ) {
			if ( is_array( $value ) ) {
				$value = $this->interpolate( $value, $config );
			} else {
				$value = preg_replace_callback( '/\%([^\%]+)\%/', function ( $matches ) use ( $ret, $config ) {
					$parts = explode( '.', $matches[1] );
					$tmp   = $ret;
					foreach ( $parts as $part ) {
						if ( isset( $tmp[ $part ] ) ) {
							$tmp = $tmp[ $part ];
						} else if ( isset( $config[ $part ] ) ) {
							$tmp = $config[ $part ];
						}
					}

					return $tmp;

				}, $value );
			}

			$ret[ $key ]    = $value;
			$config[ $key ] = $value;
		}

		return $ret;
	}

	/**
	 * @return array|mixed
	 */
	protected function read() {
		$filename = $this->filename;

		if ( ! file_exists( $filename ) ) {
			return [];
		}

		$ret = Yaml::parse( file_get_contents( $filename ) );
		if ( empty( $ret ) ) {
			throw new \InvalidArgumentException( sprintf( 'The %s file is not valid.', $filename ) );
		}

		return $ret;
	}

	/**
	 * @return array
	 */
	public function getParameters() {
		return $this->parameters;
	}
}
