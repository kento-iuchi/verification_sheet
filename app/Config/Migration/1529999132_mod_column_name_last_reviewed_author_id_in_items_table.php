<?php
class ModColumnNameLastReviewedAuthorIdInItemsTable extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'mod_column_name_last_reviewed_author_id_in_items_table';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'items' => array(
					'last_reviewed_author_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'after' => 'pullrequest_update'),
				),
			),
			'drop_field' => array(
				'items' => array('last_reviewr_id'),
			),
		),
		'down' => array(
			'drop_field' => array(
				'items' => array('last_reviewed_author_id'),
			),
			'create_field' => array(
				'items' => array(
					'last_reviewr_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
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
