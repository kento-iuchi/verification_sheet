<?php
class AddChatworkNameToAuthors extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_chatwork_name_to_authors';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'authors' => array(
					'chatwork_name' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'github_account_name'),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'authors' => array('chatwork_name'),
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
