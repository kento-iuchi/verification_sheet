<?php
class ModEditingItemsEditorIpRenameToEditorToken extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'mod_editing_items_editor_ip_rename_to_editor_token';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'editing_items' => array(
					'editor_token' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 32, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'item_column_name'),
				),
			),
			'drop_field' => array(
				'editing_items' => array('editor_ip'),
			),
		),
		'down' => array(
			'drop_field' => array(
				'editing_items' => array('editor_token'),
			),
			'create_field' => array(
				'editing_items' => array(
					'editor_ip' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 32, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
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
