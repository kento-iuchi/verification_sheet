<?php
class FirstMigration extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'first_migration';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_table' => array(
				'items' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
					'category' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'division' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'content' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'chatwork_url' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'github_url' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'verification_enviroment_url' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'confirm_priority' => array('type' => 'tinyinteger', 'null' => false, 'default' => null, 'unsigned' => false),
					'pullrequest' => array('type' => 'date', 'null' => false, 'default' => null),
					'pullrequest_update' => array('type' => 'date', 'null' => false, 'default' => null),
					'status' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'tech_release_judgement' => array('type' => 'date', 'null' => false, 'default' => null),
					'supp_release_judgement' => array('type' => 'date', 'null' => false, 'default' => null),
					'sale_release_judgement' => array('type' => 'date', 'null' => false, 'default' => null),
					'elapsed' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
					'scheduled_release_date' => array('type' => 'date', 'null' => false, 'default' => null),
					'grace_days_of_verification_complete' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
					'merge_finish_date_to_master' => array('type' => 'date', 'null' => false, 'default' => null),
					'confirm_points' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'confirm_comment' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'response_to_confirm_comment' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'created' => array('type' => 'date', 'null' => false, 'default' => null),
					'modified' => array('type' => 'date', 'null' => false, 'default' => null),
					'is_completed' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'items'
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
