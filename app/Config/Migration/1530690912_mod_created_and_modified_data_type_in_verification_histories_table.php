<?php
class ModCreatedAndModifiedDataTypeInVerificationHistoriesTable extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'mod_created_and_modified_data_type_in_verification_histories_table';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'alter_field' => array(
				'verification_histories' => array(
					'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
					'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
				),
			),
		),
		'down' => array(
			'alter_field' => array(
				'verification_histories' => array(
					'created' => array('type' => 'date', 'null' => false, 'default' => null),
					'modified' => array('type' => 'date', 'null' => false, 'default' => null),
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
