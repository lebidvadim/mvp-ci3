<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_pages extends CI_Migration
{
	public function __construct()
	{
		parent::__construct();
		$this->load->dbforge();
	}
	public function up() {

		// Drop table 'login_attempts' if it exists
		$this->dbforge->drop_table('pages', TRUE);

		// Table structure for table 'login_attempts'
		$this->dbforge->add_field([
			'id' => [
				'type'           => 'INT',
				'constraint'     => '11',
				'unsigned'       => TRUE,
				'auto_increment' => TRUE
			],
			'name' => [
				'type'       => 'VARCHAR',
				'constraint' => '255',
				'null'       => TRUE
			],
			'title' => [
				'type'       => 'VARCHAR',
				'constraint' => '255',
				'null'       => TRUE
			],
			'description' => [
				'type'       => 'VARCHAR',
				'constraint' => '255',
				'null'       => TRUE
			],
			'home' => [
				'type'       => 'INT',
				'constraint' => '11',
				'default'    => 0
			],
			'text' => [
				'type'       => 'TEXT',
				'null'       => TRUE
			],
			'template' => [
				'type'       => 'VARCHAR',
				'constraint' => '255',
				'null'       => TRUE
			],
			'date_creat' => [
				'type'       => 'VARCHAR',
				'constraint' => '255',
				'null'       => TRUE
			],
			'date_update' => [
				'type'       => 'VARCHAR',
				'constraint' => '255',
				'null'       => TRUE
			],
			'id_us' => [
				'type'       => 'INT',
				'constraint' => '11',
				'null'       => TRUE
			],
			'status' => [
				'type'       => 'INT',
				'constraint' => '11',
				'default'    => 1
			]

		]);
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('pages');

		// Dumping data for table 'users_groups'
		$data = [
			'name' => 'home',
			'title'  => 'Главная',
			'description' => 'Главная страница',
			'home' => 1,
			'text' => 'Страница Home',
			'template' => 'home',
			'date_creat' => time(),
			'id_ua' => 1
		];
		$this->db->insert('pages', $data);
	}

	public function down() {
		$this->dbforge->drop_table('pages', TRUE);
	}
}
