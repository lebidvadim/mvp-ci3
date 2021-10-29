<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_settings extends CI_Migration
{
	public function __construct()
	{
		parent::__construct();
		$this->load->dbforge();
	}
	public function up() {

		// Drop table 'login_attempts' if it exists
		$this->dbforge->drop_table('settings', TRUE);

		// Table structure for table 'login_attempts'
		$this->dbforge->add_field([
			'id' => [
				'type'           => 'INT',
				'constraint'     => '11',
				'unsigned'       => TRUE,
				'auto_increment' => TRUE
			],
			'title' => [
				'type'       => 'VARCHAR',
				'constraint' => '50',
				'null'       => TRUE
			],
			'desc' => [
				'type'       => 'TEXT',
				'null'       => TRUE
			],
			'status' => [
				'type'       => 'INT',
				'constraint' => '11',
				'null'       => TRUE
			]
		]);
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('settings');

		// Dumping data for table 'users_groups'
		$data = [
			'title'  => 'Каркас',
			'desc' => 'Каркас для разработки',
			'status' => '1',
		];
		$this->db->insert('settings', $data);
	}

	public function down() {
		$this->dbforge->drop_table('settings', TRUE);
	}
}
