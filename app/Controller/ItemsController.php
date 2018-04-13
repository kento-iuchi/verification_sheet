<?php
ini_set('display_errors',1);

App::uses('AppController', 'Controller');


class ItemsController extends AppController
{
    public $helpers = array('Html', 'Form', 'Flash', 'Js', 'DatePicker');

    public $paginate =  array(
        'limit'      => 20,
        'sort'       => 'id',
    );

    public $components = array(
        'Search.Prg' => array(
            'commonProcess' => array(
                'paramType' => 'querystring',
                // 'filterEmpty' =>  true,
            ),
        ),
    );
    public $presetVars = true;

    public function index()
    {
        debug(json_decode('{"action":"opened","number":1887,"pull_request":{"url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/pulls/1887","id":181372571,"html_url":"https://github.com/egrant/uchideno-kozuchi-v3/pull/1887","diff_url":"https://github.com/egrant/uchideno-kozuchi-v3/pull/1887.diff","patch_url":"https://github.com/egrant/uchideno-kozuchi-v3/pull/1887.patch","issue_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/issues/1887","number":1887,"state":"open","locked":false,"title":"[#156684215] bugfix_施策オートメーションにてDM・アウトバウンド・LINEのステップ追加が出来ない","user":{"login":"TamotsuFujioka","id":2480710,"avatar_url":"https://avatars3.githubusercontent.com/u/2480710?v=4","gravatar_id":"","url":"https://api.github.com/users/TamotsuFujioka","html_url":"https://github.com/TamotsuFujioka","followers_url":"https://api.github.com/users/TamotsuFujioka/followers","following_url":"https://api.github.com/users/TamotsuFujioka/following{/other_user}","gists_url":"https://api.github.com/users/TamotsuFujioka/gists{/gist_id}","starred_url":"https://api.github.com/users/TamotsuFujioka/starred{/owner}{/repo}","subscriptions_url":"https://api.github.com/users/TamotsuFujioka/subscriptions","organizations_url":"https://api.github.com/users/TamotsuFujioka/orgs","repos_url":"https://api.github.com/users/TamotsuFujioka/repos","events_url":"https://api.github.com/users/TamotsuFujioka/events{/privacy}","received_events_url":"https://api.github.com/users/TamotsuFujioka/received_events","type":"User","site_admin":false},"body":"*masterでステップ追加ができない状態になっているため、急ぎ目で確認・対応お願いします\r\n","created_at":"2018-04-13T02:39:42Z","updated_at":"2018-04-13T02:39:42Z","closed_at":null,"merged_at":null,"merge_commit_sha":null,"assignee":null,"assignees":[],"requested_reviewers":[],"requested_teams":[],"labels":[],"milestone":null,"commits_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/pulls/1887/commits","review_comments_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/pulls/1887/comments","review_comment_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/pulls/comments{/number}","comments_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/issues/1887/comments","statuses_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/statuses/fc2d89644959d7a90942132eb417691ecea8c3b8","head":{"label":"egrant:issue156684215_bugfix_save_step_dm","ref":"issue156684215_bugfix_save_step_dm","sha":"fc2d89644959d7a90942132eb417691ecea8c3b8","user":{"login":"egrant","id":2367748,"avatar_url":"https://avatars3.githubusercontent.com/u/2367748?v=4","gravatar_id":"","url":"https://api.github.com/users/egrant","html_url":"https://github.com/egrant","followers_url":"https://api.github.com/users/egrant/followers","following_url":"https://api.github.com/users/egrant/following{/other_user}","gists_url":"https://api.github.com/users/egrant/gists{/gist_id}","starred_url":"https://api.github.com/users/egrant/starred{/owner}{/repo}","subscriptions_url":"https://api.github.com/users/egrant/subscriptions","organizations_url":"https://api.github.com/users/egrant/orgs","repos_url":"https://api.github.com/users/egrant/repos","events_url":"https://api.github.com/users/egrant/events{/privacy}","received_events_url":"https://api.github.com/users/egrant/received_events","type":"Organization","site_admin":false},"repo":{"id":7328584,"name":"uchideno-kozuchi-v3","full_name":"egrant/uchideno-kozuchi-v3","owner":{"login":"egrant","id":2367748,"avatar_url":"https://avatars3.githubusercontent.com/u/2367748?v=4","gravatar_id":"","url":"https://api.github.com/users/egrant","html_url":"https://github.com/egrant","followers_url":"https://api.github.com/users/egrant/followers","following_url":"https://api.github.com/users/egrant/following{/other_user}","gists_url":"https://api.github.com/users/egrant/gists{/gist_id}","starred_url":"https://api.github.com/users/egrant/starred{/owner}{/repo}","subscriptions_url":"https://api.github.com/users/egrant/subscriptions","organizations_url":"https://api.github.com/users/egrant/orgs","repos_url":"https://api.github.com/users/egrant/repos","events_url":"https://api.github.com/users/egrant/events{/privacy}","received_events_url":"https://api.github.com/users/egrant/received_events","type":"Organization","site_admin":false},"private":true,"html_url":"https://github.com/egrant/uchideno-kozuchi-v3","description":"うちでのこづち（通称 Ver3）","fork":false,"url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3","forks_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/forks","keys_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/keys{/key_id}","collaborators_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/collaborators{/collaborator}","teams_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/teams","hooks_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/hooks","issue_events_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/issues/events{/number}","events_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/events","assignees_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/assignees{/user}","branches_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/branches{/branch}","tags_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/tags","blobs_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/git/blobs{/sha}","git_tags_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/git/tags{/sha}","git_refs_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/git/refs{/sha}","trees_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/git/trees{/sha}","statuses_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/statuses/{sha}","languages_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/languages","stargazers_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/stargazers","contributors_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/contributors","subscribers_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/subscribers","subscription_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/subscription","commits_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/commits{/sha}","git_commits_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/git/commits{/sha}","comments_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/comments{/number}","issue_comment_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/issues/comments{/number}","contents_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/contents/{+path}","compare_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/compare/{base}...{head}","merges_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/merges","archive_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/{archive_format}{/ref}","downloads_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/downloads","issues_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/issues{/number}","pulls_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/pulls{/number}","milestones_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/milestones{/number}","notifications_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/notifications{?since,all,participating}","labels_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/labels{/name}","releases_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/releases{/id}","deployments_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/deployments","created_at":"2012-12-26T15:28:34Z","updated_at":"2018-04-11T12:50:02Z","pushed_at":"2018-04-13T02:38:49Z","git_url":"git://github.com/egrant/uchideno-kozuchi-v3.git","ssh_url":"git@github.com:egrant/uchideno-kozuchi-v3.git","clone_url":"https://github.com/egrant/uchideno-kozuchi-v3.git","svn_url":"https://github.com/egrant/uchideno-kozuchi-v3","homepage":"","size":43101,"stargazers_count":0,"watchers_count":0,"language":"PHP","has_issues":true,"has_projects":true,"has_downloads":true,"has_wiki":true,"has_pages":false,"forks_count":0,"mirror_url":null,"archived":false,"open_issues_count":13,"license":null,"forks":0,"open_issues":13,"watchers":0,"default_branch":"master"}},"base":{"label":"egrant:master","ref":"master","sha":"d10f7a1037e40cd13e22fcb54a5cb55a6d5f759c","user":{"login":"egrant","id":2367748,"avatar_url":"https://avatars3.githubusercontent.com/u/2367748?v=4","gravatar_id":"","url":"https://api.github.com/users/egrant","html_url":"https://github.com/egrant","followers_url":"https://api.github.com/users/egrant/followers","following_url":"https://api.github.com/users/egrant/following{/other_user}","gists_url":"https://api.github.com/users/egrant/gists{/gist_id}","starred_url":"https://api.github.com/users/egrant/starred{/owner}{/repo}","subscriptions_url":"https://api.github.com/users/egrant/subscriptions","organizations_url":"https://api.github.com/users/egrant/orgs","repos_url":"https://api.github.com/users/egrant/repos","events_url":"https://api.github.com/users/egrant/events{/privacy}","received_events_url":"https://api.github.com/users/egrant/received_events","type":"Organization","site_admin":false},"repo":{"id":7328584,"name":"uchideno-kozuchi-v3","full_name":"egrant/uchideno-kozuchi-v3","owner":{"login":"egrant","id":2367748,"avatar_url":"https://avatars3.githubusercontent.com/u/2367748?v=4","gravatar_id":"","url":"https://api.github.com/users/egrant","html_url":"https://github.com/egrant","followers_url":"https://api.github.com/users/egrant/followers","following_url":"https://api.github.com/users/egrant/following{/other_user}","gists_url":"https://api.github.com/users/egrant/gists{/gist_id}","starred_url":"https://api.github.com/users/egrant/starred{/owner}{/repo}","subscriptions_url":"https://api.github.com/users/egrant/subscriptions","organizations_url":"https://api.github.com/users/egrant/orgs","repos_url":"https://api.github.com/users/egrant/repos","events_url":"https://api.github.com/users/egrant/events{/privacy}","received_events_url":"https://api.github.com/users/egrant/received_events","type":"Organization","site_admin":false},"private":true,"html_url":"https://github.com/egrant/uchideno-kozuchi-v3","description":"うちでのこづち（通称 Ver3）","fork":false,"url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3","forks_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/forks","keys_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/keys{/key_id}","collaborators_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/collaborators{/collaborator}","teams_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/teams","hooks_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/hooks","issue_events_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/issues/events{/number}","events_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/events","assignees_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/assignees{/user}","branches_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/branches{/branch}","tags_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/tags","blobs_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/git/blobs{/sha}","git_tags_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/git/tags{/sha}","git_refs_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/git/refs{/sha}","trees_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/git/trees{/sha}","statuses_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/statuses/{sha}","languages_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/languages","stargazers_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/stargazers","contributors_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/contributors","subscribers_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/subscribers","subscription_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/subscription","commits_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/commits{/sha}","git_commits_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/git/commits{/sha}","comments_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/comments{/number}","issue_comment_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/issues/comments{/number}","contents_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/contents/{+path}","compare_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/compare/{base}...{head}","merges_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/merges","archive_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/{archive_format}{/ref}","downloads_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/downloads","issues_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/issues{/number}","pulls_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/pulls{/number}","milestones_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/milestones{/number}","notifications_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/notifications{?since,all,participating}","labels_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/labels{/name}","releases_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/releases{/id}","deployments_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/deployments","created_at":"2012-12-26T15:28:34Z","updated_at":"2018-04-11T12:50:02Z","pushed_at":"2018-04-13T02:38:49Z","git_url":"git://github.com/egrant/uchideno-kozuchi-v3.git","ssh_url":"git@github.com:egrant/uchideno-kozuchi-v3.git","clone_url":"https://github.com/egrant/uchideno-kozuchi-v3.git","svn_url":"https://github.com/egrant/uchideno-kozuchi-v3","homepage":"","size":43101,"stargazers_count":0,"watchers_count":0,"language":"PHP","has_issues":true,"has_projects":true,"has_downloads":true,"has_wiki":true,"has_pages":false,"forks_count":0,"mirror_url":null,"archived":false,"open_issues_count":13,"license":null,"forks":0,"open_issues":13,"watchers":0,"default_branch":"master"}},"_links":{"self":{"href":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/pulls/1887"},"html":{"href":"https://github.com/egrant/uchideno-kozuchi-v3/pull/1887"},"issue":{"href":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/issues/1887"},"comments":{"href":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/issues/1887/comments"},"review_comments":{"href":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/pulls/1887/comments"},"review_comment":{"href":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/pulls/comments{/number}"},"commits":{"href":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/pulls/1887/commits"},"statuses":{"href":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/statuses/fc2d89644959d7a90942132eb417691ecea8c3b8"}},"author_association":"CONTRIBUTOR","merged":false,"mergeable":null,"rebaseable":null,"mergeable_state":"unknown","merged_by":null,"comments":0,"review_comments":0,"maintainer_can_modify":false,"commits":1,"additions":5,"deletions":1,"changed_files":2},"repository":{"id":7328584,"name":"uchideno-kozuchi-v3","full_name":"egrant/uchideno-kozuchi-v3","owner":{"login":"egrant","id":2367748,"avatar_url":"https://avatars3.githubusercontent.com/u/2367748?v=4","gravatar_id":"","url":"https://api.github.com/users/egrant","html_url":"https://github.com/egrant","followers_url":"https://api.github.com/users/egrant/followers","following_url":"https://api.github.com/users/egrant/following{/other_user}","gists_url":"https://api.github.com/users/egrant/gists{/gist_id}","starred_url":"https://api.github.com/users/egrant/starred{/owner}{/repo}","subscriptions_url":"https://api.github.com/users/egrant/subscriptions","organizations_url":"https://api.github.com/users/egrant/orgs","repos_url":"https://api.github.com/users/egrant/repos","events_url":"https://api.github.com/users/egrant/events{/privacy}","received_events_url":"https://api.github.com/users/egrant/received_events","type":"Organization","site_admin":false},"private":true,"html_url":"https://github.com/egrant/uchideno-kozuchi-v3","description":"うちでのこづち（通称 Ver3）","fork":false,"url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3","forks_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/forks","keys_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/keys{/key_id}","collaborators_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/collaborators{/collaborator}","teams_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/teams","hooks_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/hooks","issue_events_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/issues/events{/number}","events_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/events","assignees_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/assignees{/user}","branches_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/branches{/branch}","tags_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/tags","blobs_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/git/blobs{/sha}","git_tags_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/git/tags{/sha}","git_refs_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/git/refs{/sha}","trees_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/git/trees{/sha}","statuses_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/statuses/{sha}","languages_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/languages","stargazers_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/stargazers","contributors_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/contributors","subscribers_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/subscribers","subscription_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/subscription","commits_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/commits{/sha}","git_commits_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/git/commits{/sha}","comments_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/comments{/number}","issue_comment_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/issues/comments{/number}","contents_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/contents/{+path}","compare_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/compare/{base}...{head}","merges_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/merges","archive_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/{archive_format}{/ref}","downloads_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/downloads","issues_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/issues{/number}","pulls_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/pulls{/number}","milestones_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/milestones{/number}","notifications_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/notifications{?since,all,participating}","labels_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/labels{/name}","releases_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/releases{/id}","deployments_url":"https://api.github.com/repos/egrant/uchideno-kozuchi-v3/deployments","created_at":"2012-12-26T15:28:34Z","updated_at":"2018-04-11T12:50:02Z","pushed_at":"2018-04-13T02:38:49Z","git_url":"git://github.com/egrant/uchideno-kozuchi-v3.git","ssh_url":"git@github.com:egrant/uchideno-kozuchi-v3.git","clone_url":"https://github.com/egrant/uchideno-kozuchi-v3.git","svn_url":"https://github.com/egrant/uchideno-kozuchi-v3","homepage":"","size":43101,"stargazers_count":0,"watchers_count":0,"language":"PHP","has_issues":true,"has_projects":true,"has_downloads":true,"has_wiki":true,"has_pages":false,"forks_count":0,"mirror_url":null,"archived":false,"open_issues_count":13,"license":null,"forks":0,"open_issues":13,"watchers":0,"default_branch":"master"},"organization":{"login":"egrant","id":2367748,"url":"https://api.github.com/orgs/egrant","repos_url":"https://api.github.com/orgs/egrant/repos","events_url":"https://api.github.com/orgs/egrant/events","hooks_url":"https://api.github.com/orgs/egrant/hooks","issues_url":"https://api.github.com/orgs/egrant/issues","members_url":"https://api.github.com/orgs/egrant/members{/member}","public_members_url":"https://api.github.com/orgs/egrant/public_members{/member}","avatar_url":"https://avatars3.githubusercontent.com/u/2367748?v=4","description":""},"sender":{"login":"TamotsuFujioka","id":2480710,"avatar_url":"https://avatars3.githubusercontent.com/u/2480710?v=4","gravatar_id":"","url":"https://api.github.com/users/TamotsuFujioka","html_url":"https://github.com/TamotsuFujioka","followers_url":"https://api.github.com/users/TamotsuFujioka/followers","following_url":"https://api.github.com/users/TamotsuFujioka/following{/other_user}","gists_url":"https://api.github.com/users/TamotsuFujioka/gists{/gist_id}","starred_url":"https://api.github.com/users/TamotsuFujioka/starred{/owner}{/repo}","subscriptions_url":"https://api.github.com/users/TamotsuFujioka/subscriptions","organizations_url":"https://api.github.com/users/TamotsuFujioka/orgs","repos_url":"https://api.github.com/users/TamotsuFujioka/repos","events_url":"https://api.github.com/users/TamotsuFujioka/events{/privacy}","received_events_url":"https://api.github.com/users/TamotsuFujioka/received_events","type":"User","site_admin":false}}'));
        $this->header("Content-type: text/html; charset=utf-8");
        $this->layout = 'IndexLayout';
        $this->loadModel('VerificationHistory');
        $this->loadModel('Verifier');
        $this->loadModel('Author');
        $this->set('items', $this->paginate('Item', array('is_completed' => 0)));
        $this->set('verifier', $this->Verifier->find('all'));
        $this->set('author', $this->Author->find('all'));
    }

    public function add()
    {
        if ($this->request->is('post')) {
            $this->Item->create();
            if ($this->Item->save($this->request->data)) {
                return $this->redirect(array('action' => 'index'));
            } else {
                echo "add errot";
            }
        }
        return $this->redirect(array('action' => 'index'));
    }

    public function edit()
    {
        $this->autoRender = false;

        $this->Item->id = $this->request->data['id'];
        $column_name = $this->request->data['column_name'];
        $content = $this->request->data['content'];

        $this->request->data = $this->Item->read();
        $this->request->data['Item'][$column_name] = $content;
        if ($this->request->is(['ajax'])) {
            if ($this->Item->save($this->request->data)) {
                echo $content;
            } else {
                echo '失敗です';
            }
        }
    }

    public function toggle_complete_state($id = null)
    {
        $this->autoRender = false;

        $this->Item->id = $id;
        $this->request->data = $this->Item->read();
        $this->request->data['Item']['is_completed'] = $this->request->data['Item']['is_completed'] == 0 ? 1 : 0;
        if ($this->request->is(['ajax'])) {
            if ($this->Item->save($this->request->data)) {
                echo '変更しました';
            } else {
                echo '変更できませんでした';
            }
        }
    }

    public function show_completed()
    {
        $conditions = array(
            'is_completed' => 1,
        );

        $query_data = array(
            'from_created' => '',
            'to_created' => '',
            'from_merge_finish_date_to_master' => '',
            'to_merge_finish_date_to_master' => '',
        );
        if(!empty($this->request->query)){
            $query_data = $this->request->query['data'];
            $conditions = array_merge($conditions, $this->Item->parseCriteria($query_data));
        }
        $this->layout = 'IndexLayout';
        $this->loadModel('VerificationHistory');
        $this->loadModel('Verifier');
        $this->loadModel('Author');
        $this->set('items', $this->paginate('Item', $conditions));
        $this->set('verifier', $this->Verifier->find('all'));
        $this->set('author', $this->Author->find('all'));
        $this->set('query', $query_data);
    }

    public function save_verification_history()
    {
        $this->autoRender = false;
        $this->loadModel('VerificationHistory');
        $this->VerificationHistory->create();
        if ($this->VerificationHistory->save($this->request->data)) {
            echo $this->VerificationHistory->id;
        } else {
            echo 'save failed';
        }
    }

    public function send_message_to_chatwork($message, $url)
    {
        $api_key = "20c9d2043b146718a2ba9352179bc10e";

        $params = array(
            'body' => $message // メッセージ内容
        );

        $options = array(
            CURLOPT_URL => $url, // URL
            CURLOPT_HTTPHEADER => array('X-ChatWorkToken: '. $api_key), // APIキー
            CURLOPT_RETURNTRANSFER => true, // 文字列で返却
            CURLOPT_SSL_VERIFYPEER => false, // 証明書の検証をしない
            CURLOPT_POST => true, // POST設定
            CURLOPT_POSTFIELDS => http_build_query($params, '', '&'), // POST内容
        );

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($response);
    }

    public function send_grace_days_alert()
    {
        $message = '[info][title]おしらせ[/title]';
        $today_date = new Datetime(date("y-m-d"));
        $contents = Hash::extract($this->Item->find('all'), '{n}.Item[is_completed=0].content');
        $scheduled_release_dates = Hash::extract($this->Item->find('all'), '{n}.Item[is_completed=0].scheduled_release_date');
        foreach ($scheduled_release_dates as $i => $schedled_release_date) {
            $scheduled_release_date = new Datetime($schedled_release_date);
            $grace_days = $today_date->diff($scheduled_release_date)->format('%r%a');
            if($grace_days <= 7){
                $message .=  '■'. $contents[$i] . "\n";
                $message .=  "　リリース予定日まで {$grace_days} 日です\n";
            }
        }

        $message.= '[/info]';

        $room_id = 99451000;
        $url = "https://api.chatwork.com/v2/rooms/{$room_id}/messages"; // API URL
        debug($url);

        $this->send_message_to_chatwork($message, $url);
    }

    public function retrieve_github_push()
    {
        $this->log('post successed 15');

        $this->autoRender = false;

        include(__DIR__.'/../Config/webhook_key.php');
        $this->log(json_decode($this->request->data['payload']));

        $payload = json_decode($this->request->data['payload']);
        $key = $this->request->query['key'];
        if($key == $GITHUB_WEBHOOK_KEY){
            $this->log('activation successd');
        }

        if ($this->request->is('post')) {
            if($key == $GITHUB_WEBHOOK_KEY){
                $this->log('activation successd');
                $this->Item->create();

                if($payload['action']=='opened'){
                    $new_item = array(
                        'Item' => array(
                            'content' => $payload['pullrequest']['title'],
                            'github_url' => $payload['pullrequest']['html_url'],
                            'chatwork_url' => '',
                            'status' => 'コードレビュー中',
                            'verification_enviroment_url' => '',
                            'pullrequest' => explode('T', $payload['pullrequest']['created_at'])[0], // payloadの中身をformatする
                            'scheduled_release_date' => '',
                            'confirm_comment' => $payload['pullrequest']['body'],
                            'pivotal_point' => 1,
                        )
                    );
                    if ($this->Item->save($new_item)) {
                        $this->log('save from github: successed');
                    } else {
                        $this->log('save from github: failed');
                    }
                }

            }
            // if ($this->Item->save($this->request->data)) {
            //     return $this->redirect(array('action' => 'index'));
            // } else {
            //     echo "add errot";
            // }
        }
    }

}
