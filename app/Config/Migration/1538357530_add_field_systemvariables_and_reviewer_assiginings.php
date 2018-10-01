<?php
class AddFieldSystemvariablesAndReviewerAssiginings extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_field_systemvariables_and_reviewer_assiginings';

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
					'review_stage' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
					'is_reviewed' => array('type' => 'tinyinteger', 'null' => false, 'default' => '0', 'unsigned' => false),
					'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
					'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
			),
			'create_field' => array(
				'system_variables' => array(
					'stage1_next_reviewer' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'after' => 'next_release_date'),
					'stage2_next_reviewer' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'after' => 'stage1_next_reviewer'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'reviewer_assignings'
			),
			'drop_field' => array(
				'system_variables' => array('stage1_next_reviewer', 'stage2_next_reviewer'),
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
