<?php

require_once APPPATH . '/third_party/php-foursquare/src/FoursquareAPI.class.php';

class IgniteFoursquare extends FoursquareApi {

	public $redirectUrl;
	private $authenticated;

	function __construct($params) {
    parent::__construct($params['clientId'], $params['clientSecret']);
    $this->redirectUrl = $params['redirectUrl'];
    $this->authenticated = false;
  }

	function setAuthenticated($value = false) {
		$this->authenticated = $value;
		return;
	}
	
	function isAuthenticated() {
		return $this->authenticated;
	}

}