<?php
class O extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'o';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'alter_field' => array(
				'items' => array(
					'pullrequest_update' => array('type' => 'date', 'null' => true, 'default' => null),
					'tech_release_judgement' => array('type' => 'date', 'null' => true, 'default' => null),
					'supp_release_judgement' => array('type' => 'date', 'null' => true, 'default' => null),
					'sale_release_judgement' => array('type' => 'date', 'null' => true, 'default' => null),
					'elapsed' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
					'scheduled_release_date' => array('type' => 'date', 'null' => true, 'default' => null),
					'grace_days_of_verification_complete' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
					'merge_finish_date_to_master' => array('type' => 'date', 'null' => true, 'default' => null),
					'confirm_comment' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'response_to_confirm_comment' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				),
			),
		),
		'down' => array(
			'alter_field' => array(
				'items' => array(
					'pullrequest_update' => array('type' => 'date', 'null' => false, 'default' => null),
					'tech_release_judgement' => array('type' => 'date', 'null' => false, 'default' => null),
					'supp_release_judgement' => array('type' => 'date', 'null' => false, 'default' => null),
					'sale_release_judgement' => array('type' => 'date', 'null' => false, 'default' => null),
					'elapsed' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
					'scheduled_release_date' => array('type' => 'date', 'null' => false, 'default' => null),
					'grace_days_of_verification_complete' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
					'merge_finish_date_to_master' => array('type' => 'date', 'null' => false, 'default' => null),
					'confirm_comment' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'response_to_confirm_comment' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
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
