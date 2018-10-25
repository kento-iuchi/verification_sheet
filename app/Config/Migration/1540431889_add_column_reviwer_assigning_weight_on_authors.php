<?php
class AddColumnReviwerAssigningWeightOnAuthors extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_column_reviwer_assigning_weight_on_authors';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_table' => array(
				'active_reviewer_assignings' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'key' => 'primary'),
					'item_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
					'reviewing_author_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
					'indexes' => array(
					),
					'tableParameters' => array('comment' => 'VIEW'),
				),
			),
			'create_field' => array(
				'authors' => array(
					'review_assign_weight' => array('type' => 'integer', 'null' => true, 'default' => '1', 'unsigned' => false, 'after' => 'chatwork_id'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'active_reviewer_assignings'
			),
			'drop_field' => array(
				'authors' => array('review_assign_weight'),
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
