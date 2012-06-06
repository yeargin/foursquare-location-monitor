<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Reset_password extends CI_Migration {

	public function up() {
		
		// Activation key column
		$fields = array(
			'activation_key' => array('type' => 'VARCHAR(32)', 'null' => false, 'default' => '')
		);
		$this->dbforge->add_column('users', $fields);
	}

	public function down() {
		$this->dbforge->drop_column('users', 'activation_key');
	}
	
}