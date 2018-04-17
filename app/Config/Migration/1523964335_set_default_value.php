<?php
class SetDefaultValue extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'set_default_value';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'authors' => array(
					'github_account_name' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'name'),
				),
			),
			'alter_field' => array(
				'items' => array(
					'category' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'division' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'content' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'chatwork_url' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'github_url' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'verification_enviroment_url' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'confirm_priority' => array('type' => 'tinyinteger', 'null' => true, 'default' => null, 'unsigned' => false),
					'pullrequest' => array('type' => 'date', 'null' => true, 'default' => null),
					'status' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'confirm_points' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'author_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
					'created' => array('type' => 'date', 'null' => true, 'default' => null),
					'modified' => array('type' => 'date', 'null' => true, 'default' => null),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'authors' => array('github_account_name'),
			),
			'alter_field' => array(
				'items' => array(
					'category' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'division' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'content' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'chatwork_url' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'github_url' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'verification_enviroment_url' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'confirm_priority' => array('type' => 'tinyinteger', 'null' => false, 'default' => null, 'unsigned' => false),
					'pullrequest' => array('type' => 'date', 'null' => false, 'default' => null),
					'status' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'confirm_points' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'author_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
					'created' => array('type' => 'date', 'null' => false, 'default' => null),
					'modified' => array('type' => 'date', 'null' => false, 'default' => null),
				),
			),
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function after($direction) {
		return true;
	}
}
