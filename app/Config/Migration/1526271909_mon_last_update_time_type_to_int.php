<?php
class MonLastUpdateTimeTypeToInt extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'mon_last_update_time_type_to_int';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'alter_field' => array(
				'items' => array(
					'last_updated_time' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
				),
			),
		),
		'down' => array(
			'alter_field' => array(
				'items' => array(
					'last_updated_time' => array('type' => 'timestamp', 'null' => true, 'default' => null),
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
