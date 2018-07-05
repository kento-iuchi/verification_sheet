<?php
class CreateConfirmCommentResponseTable extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'create_confirm_comment_response_table';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'confirm_comment_responses' => array(
					'last_updated_time' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'after' => 'comment'),
				),
				'verification_histories' => array(
					'last_updated_time' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'after' => 'comment'),
				),
				'verifiers' => array(
					'chatwork_id' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'name'),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'confirm_comment_responses' => array('last_updated_time'),
				'verification_histories' => array('last_updated_time'),
				'verifiers' => array('chatwork_id'),
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
