<?php
class ModColumnTypeModifiedToDatetimeInItems extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'mod_column_type_modified_to_datetime_in_items';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'alter_field' => array(
				'items' => array(
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
				),
			),
		),
		'down' => array(
			'alter_field' => array(
				'items' => array(
					'modified' => array('type' => 'date', 'null' => true, 'default' => null),
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
