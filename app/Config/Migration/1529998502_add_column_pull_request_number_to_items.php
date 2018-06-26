<?php
class AddColumnPullRequestNumberToItems extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_column_pull_request_number_to_items';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'items' => array(
					'pullrequest_number' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'after' => 'pullrequest_id'),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'items' => array('pullrequest_number'),
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
