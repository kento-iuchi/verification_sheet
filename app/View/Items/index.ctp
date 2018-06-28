<!-- File: /app/View/Posts/index.ctp -->
<?php
ini_set("display_errors", 'On');
error_reporting(E_ALL);
?>

<?php
// jsonとしてindex.jsに与える要
$verifier_names = array();
$verifiers_count = count($verifier);
for ($vi=0; $vi < $verifiers_count; $vi++) {
    $verifier_names[$vi] = $verifier[$vi]['Verifier']['name'];
}
?>

<?php
$author_names = array();
foreach($author as $author_array){
    $author_names[$author_array['Author']['id']] = $author_array['Author']['name'];
}
?>

<?php
echo $this->Html->script('index_common.js');
if (!$completed_mode_flag) {
    echo $this->Html->script('index.js');
}
?>
<?php echo $this->Html->css('index.css');?>

<button>
<?php
if (!$completed_mode_flag) {
    echo $this->Html->link('完了済みの項目を表示', '/items/index/1', array('class' => 'button',));
} else {
    echo $this->Html->link('未完了の項目を表示', '/items/index/0', array('class' => 'button',));
}
?>
</button>
<div id="search-form">
    <?php
        echo $this->form->create('Item', array(
            'url' => '/items/index/' . $completed_mode_flag,
            'type'  => 'GET',
        ));
    ?>
    <table class="search-form <?php echo $completed_mode_flag ? "completed-item" : ''?>">
        <tr>
            <td>
                <label>作成日</label>
            </td>
            <td><div class="input">
            <?php echo $this->Datepicker->datepicker(
                'from_created',
                array(
                    'div' => false,
                    'label' => false,
                    'default' => $query['from_created'],
                    'class' => 'search-input',
                    )
                );
            ?>
            </div></td>
            <td><div class="input">
            <?php echo $this->Datepicker->datepicker(
                'to_created',
                array(
                    'div' => false,
                    'label' => false,
                    'default' => $query['to_created'],
                    'class' => 'search-input',
                    )
                );
            ?>
            </div></td>
            <td>
                <label>masterマージ完了日</label>
            </td>
            <td><div class="input">
            <?php echo $this->Datepicker->datepicker(
                'from_merge_finish_date_to_master',
                array(
                    'div' => false,
                    'label' => false,
                    'default' => $query['from_merge_finish_date_to_master'],
                    'class' => 'search-input',
                    )
                );
            ?>
            </div></td>
            <td><div class="input">
            <?php echo $this->Datepicker->datepicker(
                'to_merge_finish_date_to_master',
                array(
                    'div' => false,
                    'label' => false,
                    'default' => $query['to_merge_finish_date_to_master'],
                    'class' => 'search-input',
                    )
                );
            ?>
            </div></td>
            <td>
                <?php echo $this->form->submit('検索', array('name' => 'search_condition'))?>
            </td>
        </tr>
    </table>
    <?php echo $this->form->end();?>
</div>
<span id="hide-column-for-dev">
    <input type="checkbox"><span class="body-text">開発部用の項目を表示しない</span>
</span>
<?php echo $this->Form->create('Item', array('url' => 'add'));?>
<div id="view_part">
<div>
<table id="header_table" <?php echo $completed_mode_flag ? 'class="completed-item"' : ''?> >
    <thead class="scrollHead">
    <tr class="table_titles">
        <th class="id-column">
            番号</br>
            <button class="sort_button"><?php echo $this->Paginator->sort('id', '▲', array('direction' => 'desc', 'lock' => true)) ?></button>
            <button class="sort_button"><?php echo $this->Paginator->sort('id', '▼', array('direction' => 'asc',  'lock' => true)) ?></button>
        </th>
        <th class="needs-supp-confirm-column column-for-dev">
            サポート・営業<br>確認不要
        </th>
        <th class="content-column">内容</th>
        <th class="date-column">必須リリース日<br>
            <button class="sort_button"><?php echo $this->Paginator->sort('due_date_for_release', '▲', array('direction' => 'desc', 'lock' => true)) ?></button>
            <button class="sort_button"><?php echo $this->Paginator->sort('due_date_for_release', '▼', array('direction' => 'asc',  'lock' => true)) ?></button>
        </th>
        <th class="status-column">ステータス</br>
            <button class="sort_button"><?php echo $this->Paginator->sort('status', '▲', array('direction' => 'desc', 'lock' => true)) ?></button>
            <button class="sort_button"><?php echo $this->Paginator->sort('status', '▼', array('direction' => 'asc',  'lock' => true)) ?></button>
        </th>
        <th class="grace-column">
            検証完了<br>猶予日数</br>
            <button class="sort_button"><?php echo $this->Paginator->sort('grace_days_of_verification_complete', '▲', array('direction' => 'desc', 'lock' => true)) ?></button>
            <button class="sort_button"><?php echo $this->Paginator->sort('grace_days_of_verification_complete', '▼', array('direction' => 'asc',  'lock' => true)) ?></button>
        </th>
    </tr>
    </thead>
    <tbody class="scrollBody">
    <?php $today_date = new Datetime(date("y-m-d")); //経過日数、猶予日数の計算に使用?>
    <?php foreach ($items as $item): ?>
    <tr id="item_<?php echo h($item['Item']['id'] . '-head'); ?>" class="view_part_item <?php echo $item['Item']['needs_supp_confirm'] == 0 ? 'needs-no-confirm' : '' ?>" >
        <td class="record id-column" id="<?php echo $item['Item']['id'] . "-id";?>" data-id="<?php echo h($item['Item']['id']); ?>">
            <span class="record_text"><?php echo $item['Item']['id']; ?></span>
        </td>
        <td class="record needs-supp-confirm-column editable-cell column-for-dev" id="<?php echo $item['Item']['id'] . "-needs_supp_confirm";?>">
            <span class="record_text"><?php echo $item['Item']['needs_supp_confirm'] == 1 ? 'いいえ' : 'はい' ?></span>
        </td>
        <td class="record content-column editable-cell" id="<?php echo $item['Item']['id'] . "-content";?>">
            <span class="record_text editable-cell"><?php echo $item['Item']['content']; ?></span>
        </td>
        <td class = "record date-column editable-cell" id="<?php echo $item['Item']['id'] . '-due_date_for_release';?>">
            <span class="record_text editable-cell"><?php echo $item['Item']['due_date_for_release']; ?></span>
        </td>
        <td class = "record status-column editable-cell" id="<?php echo $item['Item']['id'] . "-status";?>">
            <span class="record_text"><?php echo str_replace("業", "業<br>", $item['Item']['status']); ?></span>
        </td>
        <td class = "record grace-column" id="<?php echo $item['Item']['id'] . "-grace_days_of_verification_complete";?>">
            <span class="record_text"><?php
                $due_date_for_release = new Datetime($item['Item']['due_date_for_release']);
                if (!empty($item['Item']['due_date_for_release'])) {
                    echo str_replace('+', '', $today_date->diff($due_date_for_release)->format('%R%a'));
                }
            ?></span>
        </td>
    </tr>
    <?php endforeach; ?>
    <?php if(!$completed_mode_flag):?>
    <tr class="input_part">
        <td class="id-column"></td>
        <td class="needs-supp-confirm-column column-for-dev">
            <?php echo $this->Form->input('needs_supp_confirm', array(
                    'label' => false,
                    'options' => array(1 => 'いいえ', 0 => 'はい')
                ));
            ?>
        </td>
        <td class="content-column"><?php echo $this->Form->input('content', array('label' => false,));?></td>
        <td class="date-column"><?php echo $this->Datepicker->datepicker('due_date_for_release', array('type' => 'text', 'label' => false));?></td>
        <td class="status-column">
            <?php echo $this->Form->input('status',array(
                  'label' => false,
                  'options' => array(
                      'コードレビュー中' => 'コードレビュー中',
                      '改修中' => '改修中',
                      '技術二重チェック中' => '技術二重チェック中',
                      'サポート・営業確認中' => 'サポート・営業確認中',
                  )
              ));
            ?>
        </td>
        <td class="grace-column"><!-- 検証完了猶予日数 --></td>
    </tr>
    <?php endif?>
    </tbody>
</table>
</div>

<div id='view_part_data'>
    <table id="data_table" class="data-part-main-table <?php echo $completed_mode_flag ? 'completed-item' : ''?>">
        <thead class="scrollHead">
        <tr class="table_titles">
            <th class="category-column">
                カテゴリ</br>
                <button class="sort_button"><?php echo $this->Paginator->sort('category', '▲', array('direction' => 'desc', 'lock' => true)) ?></button>
                <button class="sort_button"><?php echo $this->Paginator->sort('category', '▼', array('direction' => 'asc',  'lock' => true)) ?></button>
            </th>
            <th class="division-column">
                区分</br>
                <button class="sort_button"><?php echo $this->Paginator->sort('division', '▲', array('direction' => 'desc', 'lock' => true)) ?></button>
                <button class="sort_button"><?php echo $this->Paginator->sort('division', '▼', array('direction' => 'asc',  'lock' => true)) ?></button>
            </th>
            <th class="url-column">
                chatwork URL
            </th>
            <th class="url-column column-for-dev">
                github URL
            </th>
            <th class="url-column">
                個別検証環境URL
            </th>
            <th class="date-column">プルリク</br>
                <button class="sort_button"><?php echo $this->Paginator->sort('pullrequest', '▲', array('direction' => 'desc', 'lock' => true)) ?></button>
                <button class="sort_button"><?php echo $this->Paginator->sort('pullrequest', '▼', array('direction' => 'asc',  'lock' => true)) ?></button>
            </th>
            <th class="date-column">
                プルリク<br>更新日</br>
                <button class="sort_button"><?php echo $this->Paginator->sort('pullrequest_update', '▲', array('direction' => 'desc', 'lock' => true)) ?></button>
                <button class="sort_button"><?php echo $this->Paginator->sort('pullrequest_update', '▼', array('direction' => 'asc',  'lock' => true)) ?></button>
            </th>
            <th class="date-column">
                技術リリース<br>OK判断日</br>
                <button class="sort_button"><?php echo $this->Paginator->sort('tech_release_judgement', '▲', array('direction' => 'desc', 'lock' => true)) ?></button>
                <button class="sort_button"><?php echo $this->Paginator->sort('tech_release_judgement', '▼', array('direction' => 'asc',  'lock' => true)) ?></button>
            </th>
            <th class="date-column">
                サポートリリース<br>OK判断日</br>
                <button class="sort_button"><?php echo $this->Paginator->sort('supp_release_judgement', '▲', array('direction' => 'desc', 'lock' => true)) ?></button>
                <button class="sort_button"><?php echo $this->Paginator->sort('supp_release_judgement', '▼', array('direction' => 'asc',  'lock' => true)) ?></button></th>
            <th class="verifier-column" data-verifier-options='<?php echo json_encode($verifier_names)?>'>
                検証担当者</br>
                <button class="sort_button"><?php echo $this->Paginator->sort('verifier_id', '▲', array('direction' => 'desc', 'lock' => true)) ?></button>
                <button class="sort_button"><?php echo $this->Paginator->sort('verifier_id', '▼', array('direction' => 'asc',  'lock' => true)) ?></button></th>
            <th class="priority-column">
                手順書有無</br>
                <button class="sort_button"><?php echo $this->Paginator->sort('manual_exists', '▲', array('direction' => 'desc', 'lock' => true)) ?></button>
                <button class="sort_button"><?php echo $this->Paginator->sort('manual_exists', '▼', array('direction' => 'asc',  'lock' => true)) ?></button>
            </th>
            <th class="date-column">
                営業リリース<br>OK判断日</br>
                <button class="sort_button"><?php echo $this->Paginator->sort('sale_release_judgement', '▲', array('direction' => 'desc', 'lock' => true)) ?></button>
                <button class="sort_button"><?php echo $this->Paginator->sort('sale_release_judgement', '▼', array('direction' => 'asc',  'lock' => true)) ?></button>
            </th>
            <th class="day_count-column">
                経過日数</br>
                <button class="sort_button"><?php echo $this->Paginator->sort('elapsed', '▲', array('direction' => 'desc', 'lock' => true)) ?></button>
                <button class="sort_button"><?php echo $this->Paginator->sort('elapsed', '▼', array('direction' => 'asc',  'lock' => true)) ?></button>
            </th>
            <th class="date-column">
                リリース<br>予定日</br>
                <button class="sort_button"><?php echo $this->Paginator->sort('scheduled_release_date', '▲', array('direction' => 'desc', 'lock' => true)) ?></button>
                <button class="sort_button"><?php echo $this->Paginator->sort('scheduled_release_date', '▼', array('direction' => 'asc',  'lock' => true)) ?></button>
            </th>
            <th class="date-column">
                master<br>マージ完了日</br>
                <button class="sort_button"><?php echo $this->Paginator->sort('merge_finish_date_to_master', '▲', array('direction' => 'desc', 'lock' => true)) ?></button>
                <button class="sort_button"><?php echo $this->Paginator->sort('merge_finish_date_to_master', '▼', array('direction' => 'asc',  'lock' => true)) ?></button>
            </th>
            <th class="comment-column">
                確認ポイント
            </th>
            <th class="verification-history-column">
                検証履歴
            </th>
            <th class="comment-column">
                確認コメント
            </th>
            <th class="comment-column">
                確認コメント対応
            </th>
            <th class="author-column" data-author-options='<?php echo json_encode($author_names)?>'>
                作成者
            </th>
            <th class="point-column column-for-dev">
                pivotal<br>ポイント
            </th>
            <th class="date-column">
                最終更新日時</br>
                <button class="sort_button"><?php echo $this->Paginator->sort('modified', '▲', array('direction' => 'desc', 'lock' => true)) ?></button>
                <button class="sort_button"><?php echo $this->Paginator->sort('modified', '▼', array('direction' => 'asc',  'lock' => true)) ?></button>
            </th>
            <th class="complete_column"></th>
        </tr>
        </thead>

        <tbody class="scrollBody">
        <?php foreach ($items as $item): ?>
        <tr id="item_<?php echo h($item['Item']['id'] . '-data'); ?>" class="view_part_item <?php echo $item['Item']['needs_supp_confirm'] == 0 ? 'needs-no-confirm' : '' ?>">
            <td class="record category-column editable-cell" id="<?php echo $item['Item']['id'] . "-category";?>">
                <span class="record_text"><?php echo $item['Item']['category']; ?></span>
            </td>
            <td class="record division-column editable-cell" id="<?php echo $item['Item']['id'] . "-division";?>">
                <span class="record_text"><?php echo $item['Item']['division']; ?></span>
            </td>
            <td class = "record editable-cell url-column" id="<?php echo $item['Item']['id'] . "-chatwork_url";?>">
                <a href = "<?php echo $item['Item']['chatwork_url']; ?>" target="_blank">
                    <span class="record_text"><?php echo $item['Item']['chatwork_url']; ?></span>
                </a>
            </td>
            <td class = "record editable-cell url-column column-for-dev" id="<?php echo $item['Item']['id'] . "-github_url";?>">
                <a href="<?php echo $item['Item']['github_url']; ?>" target="_blank">
                    <span class="record_text"><?php echo $item['Item']['github_url']; ?></span>
                </a>
            </td>
            <td class = "record editable-cell url-column" id="<?php echo $item['Item']['id'] . "-verification_enviroment_url";?>">
                <a href="<?php echo $item['Item']['verification_enviroment_url']; ?>" target="_blank">
                    <span class="record_text"><?php echo $item['Item']['verification_enviroment_url']; ?></span>
                </a>
            </td>
            <td class = "record editable-cell date-column" id="<?php echo $item['Item']['id'] . "-pullrequest";?>">
                <span class="record_text"><?php echo $item['Item']['pullrequest']; ?></span>
            </td>
            <td class = "record editable-cell date-column" id="<?php echo $item['Item']['id'] . "-pullrequest_update";?>">
                <span class="record_text"><?php echo $item['Item']['pullrequest_update']; ?></span>
            </td>
            <td class = "record editable-cell date-column" id="<?php echo $item['Item']['id'] . "-tech_release_judgement";?>">
                <span class="record_text"><?php echo $item['Item']['tech_release_judgement']; ?></span>
            </td>
            <td class = "record editable-cell date-column" id="<?php echo $item['Item']['id'] . "-supp_release_judgement";?>">
                <span class="record_text"><?php echo $item['Item']['supp_release_judgement']; ?></span>
            </td>
            <td class = "record editable-cell verifier-column" id="<?php echo $item['Item']['id'] . "-verifier_id";?>">
                <span class="record_text"><?php echo $verifier_names[$item['Item']['verifier_id']]; ?></span>
            </td>
            <td class = "record editable-cell priority-column" id="<?php echo $item['Item']['id'] . "-manual_exists";?>">
                <span class="record_text"><?php echo $item['Item']['manual_exists'] == 1 ? '◯' : '✕'; ?></span>
            </td>
            <td class = "record editable-cell date-column" id="<?php echo $item['Item']['id'] . "-sale_release_judgement";?>">
                <span class="record_text"><?php echo $item['Item']['sale_release_judgement']; ?></span>
            </td>
            <td class = "record day_count-column" id="<?php echo $item['Item']['id'] . "-elapsed";?>">
                <span class="record_text"><?php
                    $pullrequest_date = new Datetime($item['Item']['pullrequest']);
                    echo $today_date->diff($pullrequest_date)->format('%a');;
                ?></span>
            </td>
            <td class = "record editable-cell date-column" id="<?php echo $item['Item']['id'] . "-scheduled_release_date";?>">
                <span class="record_text"><?php echo $item['Item']['scheduled_release_date']; ?></span>
            </td>
            <td class = "record editable-cell date-column" id="<?php echo $item['Item']['id'] . "-merge_finish_date_to_master";?>">
                <span class="record_text"><?php echo $item['Item']['merge_finish_date_to_master']; ?></span>
            </td>
            <td class = "record editable-cell comment-column" id="<?php echo $item['Item']['id'] . "-confirm_points";?>">
                <div class="record_text"><?php echo str_replace(array("\r\n", "\r", "\n"), '</br>', $item['Item']['confirm_points']); ?></div>
            </td>
            <td class = "record verification-history-column" id="<?php echo $item['Item']['id'] . "-verification_history";?>">
                <table>
                    <?php if(!empty($item['verification_history'])):?>
                        <?php foreach ($item['verification_history'] as $verification_history): ?>
                            <tr>
                            <td><?php echo $verifier_names[$verification_history['verifier_id']-1];?></td>
                            <td><?php echo $verification_history['created'];?></td>
                            <td><span class="verification-history-detail-link" data-comment="<?php echo $verification_history['comment']?>" data-id = "<?php echo $verification_history['id']?>">詳細</span></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif?>
                </table>
                <?php if(!$completed_mode_flag):?>
                <button type="button" class="add-verification-history" id="<?php echo $item['Item']['id'] . '-add-verification-history';?>">
                    新規作成
                </button>
                <?php endif?>
                <div id="<?php echo $item['Item']['id'];?>-verification-history-input-area"></div>
            </td>
            <td class = "record editable-cell comment-column" id="<?php echo $item['Item']['id'] . "-confirm_comment";?>">
                <div class="record_text"><?php echo str_replace(array("\r\n", "\r", "\n"), '</br>', $item['Item']['confirm_comment']); ?></div>
            </td>
            <td class = "record editable-cell comment-column" id="<?php echo $item['Item']['id'] . "-response_to_confirm_comment";?>">
                <div class="record_text"><?php echo str_replace(array("\r\n", "\r", "\n"), '</br>', $item['Item']['response_to_confirm_comment']); ?></div>
            </td>
            <td class = "record editable-cell author-column" id="<?php echo $item['Item']['id'] . "-author_id";?>">
                <span class="record_text"><?php echo $author_names[$item['Item']['author_id']]; ?></span>
            </td>
            <td class = "record editable-cell point-column column-for-dev" id="<?php echo $item['Item']['id'] . "-pivotal_point";?>">
                <span class="record_text"><?php echo $item['Item']['pivotal_point']; ?></span>
            </td>
            <td class = "record date-column" id="<?php echo $item['Item']['id'] . "-modified";?>">
                <span class="record_text"><?php echo $item['Item']['modified']; ?></span>
            </td>
            <td class= "complete_column">
                <div class="complete">
                    <button type="button" class="<?php echo !$completed_mode_flag ? 'complete_button' : 'incomplete_button'?>" id="<?php echo $item['Item']['id'] . "-complete_button";?>">完了</button>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>

        <?php if(!$completed_mode_flag):?>
        <tr class="input_part">
            <td class="category-column"><?php echo $this->Form->input('category', array('label' => false));?></td>
            <td class="division-column"><?php echo $this->Form->input('division', array(
                    'label' => false,
                    'options' => array(
                        '改善' => '改善',
                        '機能追加' => '機能追加',
                        'バグ' => 'バグ',
                        )
                    ));
                ?>
            </td>
            <td class="url-column"><?php echo $this->Form->input('chatwork_url', array('label' => false));?></td>
            <td class="url-column column-for-dev"><?php echo $this->Form->input('github_url', array('label' => false));?></td>
            <td class="url-column"><?php echo $this->Form->input('verification_enviroment_url', array('label' => false));?></td>
            <td class="date-column"><?php echo $this->Datepicker->datepicker('pullrequest', array('type' => 'text', 'label' => false));?></td>
            <td class="date-column"><!-- プルリク更新日 --></td>
            <td class="date-column"><!-- 技術リリースOK判断日 --></td>
            <td class="date-column"><!-- サポートリリースOK判断日 --></td>
            <td class="author-column">
            <td class="priority-column"><!-- 手順書有無 --></td>
            <td class="date-column"><!-- 営業リリースOK判断日 --></td>
            <td class="day_count-column"><!-- 経過日数 --></td>
            <td class="date-column"><?php echo $this->Datepicker->datepicker('scheduled_release_date', array('type' => 'text', 'label' => false));?></td>
            <td class="date-column"><!-- masterマージ完了日 --></td>
            <td class="comment-column"><?php echo $this->Form->input('confirm_points', array('label' => false));?></td>
            <td class="verification-history-column"></td>
            <td class="comment-column"><?php echo $this->Form->input('confirm_comment', array('label' => false));?></td>
            <td class="comment-column"><!--確認コメント対応 --></td>
            <td class="author-column">
                <?php echo $this->Form->input('author_id', array(
                    'label' => false,
                    'options' => $author_names,
                    'empty' => '選択してください',
                    ));
                ?>
            </td>
            <td class="point-column column-for-dev"><?php echo $this->Form->input('pivotal_point', array('type'=>'number', 'label' => false));?></td>
            <td class="date-column"><?php echo $this->Form->end('送信', array('name' => 'add_item'));?></td>
        </tr>
        <?php endif?>

    </tbody>
    </table>
</div>
</div>
<div id="page_selecter">
    <?php echo $this->Paginator->numbers(
        array (
            'before' => $this->Paginator->hasPrev() ? $this->Paginator->first('<<', array('tag' => 'first')).' | ' : '',
            'after' => $this->Paginator->hasNext() ? ' | '.$this->Paginator->last('>>', array('tag' => 'first')) : '',
            'tag' => 'span',
        )
    );
    ?>
</div>
