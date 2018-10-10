<?php

class Author extends AppModel
{
    public function getIdByGitHubAccountName($github_account_name)
    {
        $author = $this->find('first', array(
            'conditions' => array(
                'github_account_name' => $github_account_name,
            ),
        ));

        return Hash::get($author, 'Author.id');
    }
}
