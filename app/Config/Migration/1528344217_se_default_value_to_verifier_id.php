<?php
class SeDefaultValueToVerifierId extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'se_default_value_to_verifier_id';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'alter_field' => array(
				'items' => array(
					'verifier_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
				),
			),
		),
		'down' => array(
			'alter_field' => array(
				'items' => array(
					'verifier_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
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
