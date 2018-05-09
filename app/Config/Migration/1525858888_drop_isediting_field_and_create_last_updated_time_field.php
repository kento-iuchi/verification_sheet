<?php
class DropIseditingFieldAndCreateLastUpdatedTimeField extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'drop_isediting_field_and_create_last_updated_time_field';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'items' => array(
					'last_updated_time' => array('type' => 'timestamp', 'null' => false, 'default' => '0000-00-00 00:00:00', 'after' => 'is_completed'),
				),
			),
			'drop_field' => array(
				'items' => array('is_editing'),
			),
		),
		'down' => array(
			'drop_field' => array(
				'items' => array('last_updated_time'),
			),
			'create_field' => array(
				'items' => array(
					'is_editing' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'comment' => '編集中なら１'),
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
