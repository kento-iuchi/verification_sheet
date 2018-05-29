<?php
class AddManualExistsColumnToItems extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_manual_exists_column_to_items';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'items' => array(
					'manual_exists' => array('type' => 'boolean', 'null' => true, 'default' => '0', 'after' => 'verifier_id'),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'items' => array('manual_exists'),
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
