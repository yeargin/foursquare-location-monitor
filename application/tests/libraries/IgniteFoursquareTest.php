<?php

require_once APPPATH . 'config/database.php-dist';
require_once APPPATH . 'config/application.php-dist';

class IgniteFoursquareTest extends CI_TestCase {
	
	public static $test_venues = array(
		'ugly-mugs' => '4b06a0fef964a520eced22e3',
		'times-square' => '49b7ed6df964a52030531fe3'
	);

	/**
	 * Test Setup
	 */
	public function setup() {

		global $config, $db;

		// Load from application configuration
		$this->ci_config = $config;

		$this->ci_instance = (object) get_instance();
		
		$params = array(
			'clientId' => $this->ci_config['foursquare_consumer_key'],
			'clientSecret' => $this->ci_config['foursquare_consumer_secret'],
			'redirectUrl' => $this->ci_config['foursquare_callback_url']
		);
		
		// Library Setup
		require_once APPPATH . '/libraries/IgniteFoursquare.php';
		$this->ci_instance->ignitefoursquare = new IgniteFoursquare($params);
		
		// Database Setup
		$config = Mock_Database_DB::config($db['default']['dbdriver']);
		$connection = new Mock_Database_DB($config);
		$this->ci_instance->db = Mock_Database_DB::DB($connection->set_dsn($db['default']['dbdriver']), TRUE);
	}
	
	/**
	 * Does Load Foursquare Library
	 */
	public function testDoesLoadFoursquareLibrary() {

		// Ensures library loads properly, basic authentication fails without further configuraiton
		$this->assertInternalType('object', $this->ci_instance->ignitefoursquare, 'Type is object.');
		$this->assertFalse($this->ci_instance->ignitefoursquare->isAuthenticated(), 'Authentication fails on initial pass.');
	}

	/**
	 * Check Connection to Foursquare
	 */
	public function testCheckConnectionToFoursquare() {
		$venue_id = 'footbar';
		
		// Test Known Failure Responses
		$this->ci_instance->ignitefoursquare->SetAccessToken('foobar');
		$response = json_decode($this->ci_instance->ignitefoursquare->GetPrivate(sprintf('/venues/%s', static::$test_venues['ugly-mugs'] )));

		$this->assertEquals(401, $response->meta->code, 'Throws 401 status code.');
		$this->assertEquals('invalid_auth', $response->meta->errorType, 'Throws invalid_auth error.');
	}
	
}
