<?php
class CreateReviewerAssigningsTable extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'create_reviewer_assignings_table';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_table' => array(
				'reviewer_assignings' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
					'item_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
					'item_closed' => array('type' => 'tinyinteger', 'null' => false, 'default' => '0', 'unsigned' => false),
					'author_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
					'is_reviewed' => array('type' => 'tinyinteger', 'null' => false, 'default' => '0', 'unsigned' => false),
					'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
					'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'reviewer_assignings'
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
