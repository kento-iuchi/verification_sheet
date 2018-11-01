<?php
class AddColumnReviewerNameOnReviewerTasks extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_column_reviewer_name_on_reviewer_tasks';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'reviewer_tasks' => array(
					'reviewer_name' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'reviewer_id'),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'reviewer_tasks' => array('reviewer_name'),
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
