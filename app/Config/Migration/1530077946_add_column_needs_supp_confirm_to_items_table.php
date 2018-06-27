<?php
class AddColumnNeedsSuppConfirmToItemsTable extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_column_needs_supp_confirm_to_items_table';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'items' => array(
					'needs_supp_confirm' => array('type' => 'tinyinteger', 'null' => false, 'default' => '1', 'unsigned' => false, 'after' => 'id'),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'items' => array('needs_supp_confirm'),
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
