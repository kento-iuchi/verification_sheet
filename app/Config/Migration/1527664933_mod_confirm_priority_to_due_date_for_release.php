<?php
class ModConfirmPriorityToDueDateForRelease extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'mod_confirm_priority_to_due_date_for_release';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'items' => array(
					'due_date_for_release' => array('type' => 'date', 'null' => true, 'default' => null, 'after' => 'verification_enviroment_url'),
				),
			),
			'drop_field' => array(
				'items' => array('confirm_priority'),
			),
		),
		'down' => array(
			'drop_field' => array(
				'items' => array('due_date_for_release'),
			),
			'create_field' => array(
				'items' => array(
					'confirm_priority' => array('type' => 'tinyinteger', 'null' => true, 'default' => null, 'unsigned' => false),
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
