<?php
class DelVerificationHistoryOfItems extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'del_verification_history_of_items';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'drop_field' => array(
				'items' => array('verification_history'),
			),
		),
		'down' => array(
			'create_field' => array(
				'items' => array(
					'verification_history' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
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
