<?php
class CreateTableReviewerTasks extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'create_table_reviewer_tasks';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'reviewer_assignings' => array(
					'reviewing_author_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'after' => 'item_closed'),
				),
			),
			'drop_field' => array(
				'reviewer_assignings' => array('author_id'),
			),
		),
		'down' => array(
			'drop_field' => array(
				'reviewer_assignings' => array('reviewing_author_id'),
			),
			'create_field' => array(
				'reviewer_assignings' => array(
					'author_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
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
