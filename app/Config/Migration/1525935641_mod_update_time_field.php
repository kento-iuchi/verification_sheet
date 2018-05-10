<?php
class ModUpdateTimeField extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'mod_update_time_field';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'alter_field' => array(
				'items' => array(
					'last_updated_time' => array('type' => 'timestamp', 'null' => true, 'default' => null),
				),
			),
		),
		'down' => array(
			'alter_field' => array(
				'items' => array(
					'last_updated_time' => array('type' => 'timestamp', 'null' => false, 'default' => '0000-00-00 00:00:00'),
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
