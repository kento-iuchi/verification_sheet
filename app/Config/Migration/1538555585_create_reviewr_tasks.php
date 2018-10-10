<?php
class CreateReviewrTasks extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'create_reviewr_tasks';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_table' => array(
				'reviewer_tasks' => array(
					'reviewer_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
					'review_count' => array('type' => 'biginteger', 'null' => false, 'default' => '0', 'length' => 21, 'unsigned' => false),
					'total_pivotal_point' => array('type' => 'decimal', 'null' => true, 'default' => null, 'length' => '32,0', 'unsigned' => false),
					'indexes' => array(
					),
					'tableParameters' => array('comment' => 'VIEW'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'reviewer_tasks'
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
