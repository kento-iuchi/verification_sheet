<?php
class AddVerifierIdToItems extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_verifier_id_to_items';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'items' => array(
					'verifier_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'after' => 'supp_release_judgement'),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'items' => array('verifier_id'),
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
