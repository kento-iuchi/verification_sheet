<?php
class AddPivotalPointColumnToItems extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_pivotal_point_column_to_items';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'items' => array(
					'pivotal_point' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'after' => 'author_id'),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'items' => array('pivotal_point'),
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
