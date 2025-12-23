<?php
/*--------------------------------------------------------------
 Copyright (C) dj-extensions.com
 Website: https://dj-extensions.com
 Support: contact@dj-extensions.com
---------------------------------------------------------------*/

defined( 'ABSPATH' ) || exit;

if( ! class_exists( 'DJAccUpdate' ) ) {

	class DJAccUpdate {

		public $plugin_slug;
		public $plugin_basename;
		public $version;

		public function __construct() {

			//clear cache
			if( !empty($_GET['force-check']) ) {
				set_site_transient('update_plugins', null);
			}

			$this->plugin_slug = 'dj-popup';
			$this->plugin_basename = plugin_basename(DJACC_PATH . '/dj-popup.php');

			$this->version = DJACC_VERSION;

			add_filter( 'plugins_api', array( $this, 'info' ), 20, 3 );
			add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'update' ) );
		}

		public function request() {

			$remote = wp_remote_get(
				'https://dj-extensions.com/index.php?option=com_ars&view=update&task=stream&format=xml&id=16',
				array(
					'timeout' => 10,
					'headers' => array(
						'Accept' => 'application/xml'
					)
				)
			);

		
			if(
				is_wp_error( $remote )
				|| 200 !== wp_remote_retrieve_response_code( $remote )
				|| empty( wp_remote_retrieve_body( $remote ) )
			) {
				return false;
			}

			$remote = wp_remote_retrieve_body( $remote );
			$remote = $this->parseXML($remote);

			$remote = $remote['updates'][0]; //first element from xml

			if( empty($remote) ) return false;

			$file_url = $remote['downloads']['downloadurl'];

			$dlid = DJAcc::getDID();
			if( !empty($dlid) ) $file_url .= '&dlid=' . $dlid;

			//all needed data by info and update methods
			$data = array(
							'name' => 'DJ-Accessibility Pro',
							'plugin' => $this->plugin_basename,
							'slug' => $this->plugin_slug,
							'author' => '<a href="https://dj-extensions.com">DJ-Extensions.com</a>',
							'author_profile' => 'https://dj-extensions.com',
							'version' => $remote['version'],
							'new_version'=> $remote['version'],
							'download_url' => $file_url,
							'package' => $file_url,
							'download_link' => $file_url,
							'trunk' => $file_url,
							'requires' => '5.5',
							//'tested' => '5.8',
							'requires_php' => '5.6',
							//'last_updated' => '2021-01-30 02:10:00',
							'sections' => array(
											//'description' => 'some description',
											//'installation' => 'installation tips',
											'changelog' => '<a href="https://dj-extensions.com/support/changelogs/dj-popup/" target="_blank">Click here to check changelog</a>',
										),
							'banners' => array(
											'low' => 'https://dj-extensions.com/images/wordpress/dj-popup/banner-772x250.png',
											'high' => 'https://dj-extensions.com/images/wordpress/dj-popup/banner-1544x500.png',
										),
						);

			return (object) $data;
		}

		function xml2array( $xmlObject, $out = array() ) {
			foreach ( (array) $xmlObject as $index => $node ) {
				$out[$index] = (is_object($node) ||  is_array($node)) ? $this->xml2array($node) : $node;
			}
			return $out;
		}

		function parseXML( $xml ) {
			$data = array();

			if ( !empty($xml) ) {
				//libxml_use_internal_errors(true); //for debug
				$xml = simplexml_load_string($xml);

				if (is_object($xml) && $xml instanceof SimpleXMLElement) {
					$updates = $xml->xpath('//updates/update');
					$data['updates'] = $this->xml2array($updates);
				}
			}
			return $data;
		}


		function info( $res, $action, $args ) {

			// do nothing if you're not getting plugin information right now
			if( 'plugin_information' !== $action ) {
				return false;
			}

			// do nothing if it is not our plugin
			if( $this->plugin_slug !== $args->slug ) {
				return false;
			}

			// get updates
			$remote = $this->request();

			if( ! $remote ) {
				return false;
			}

			return $remote;

		}

		public function update( $transient ) {

			if ( empty($transient->checked ) ) {
				return $transient;
			}

			$remote = $this->request();

			if(
				$remote
				&& version_compare( $this->version, $remote->version, '<' )
				&& version_compare( $remote->requires, get_bloginfo( 'version' ), '<' )
				&& version_compare( $remote->requires_php, PHP_VERSION, '<' )
			) {
				$transient->response[ $remote->plugin ] = $remote;
			} else {
				$transient->no_update[ $remote->plugin ] = $remote;
			}

			return $transient;

		}

	}

	new DJAccUpdate();

}