<?php
class ModReviewAssigningColumnType extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'mod_review_assigning_column_type';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'alter_field' => array(
				'authors' => array(
					'review_assign_weight' => array('type' => 'float', 'null' => true, 'default' => '1', 'unsigned' => false),
				),
			),
		),
		'down' => array(
			'alter_field' => array(
				'authors' => array(
					'review_assign_weight' => array('type' => 'integer', 'null' => true, 'default' => '1', 'unsigned' => false),
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
