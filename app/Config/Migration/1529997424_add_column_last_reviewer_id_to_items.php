<?php
class AddColumnLastReviewerIdToItems extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_column_last_reviewer_id_to_items';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'items' => array(
					'last_reviewr_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'after' => 'pullrequest_update'),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'items' => array('last_reviewr_id'),
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
