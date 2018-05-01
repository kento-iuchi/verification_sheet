<?php
class AddIsEditingColumnToItems extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_isEditing_column_to_items';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'items' => array(
					'is_editing' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'comment' => '編集中なら１', 'after' => 'is_completed'),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'items' => array('is_editing'),
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
