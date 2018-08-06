<link rel="stylesheet" type="text/css" href="table.css">
<?php echo $this->Html->css('items_table.css');?>
<?php echo $this->Html->script('table_structure.js');?>
<table id="items-table" class="sticky_table">
    <thead>
        <tr class="table_titles">
            <th class="id-column header header-column1">
                番号</br>
                <button class="sort_button"><?php echo $this->Paginator->sort('id', '▲', array('direction' => 'desc', 'lock' => true)) ?></button>
                <button class="sort_button"><?php echo $this->Paginator->sort('id', '▼', array('direction' => 'asc',  'lock' => true)) ?></button>
            </th>
            <th class="header header-column-2 needs-supp-confirm-column column-for-dev">
                サポート・営業<br>確認
            </th>
            <th class="content-column header header-column-3">内容</th>
            <th class="date-column header header-column-4">必須リリース日<br>
                <button class="sort_button"><?php echo $this->Paginator->sort('due_date_for_release', '▲', array('direction' => 'desc', 'lock' => true)) ?></button>
                <button class="sort_button"><?php echo $this->Paginator->sort('due_date_for_release', '▼', array('direction' => 'asc',  'lock' => true)) ?></button>
            </th>
            <th class="status-column header header-column-5">ステータス</br>
                <button class="sort_button"><?php echo $this->Paginator->sort('status', '▲', array('direction' => 'desc', 'lock' => true)) ?></button>
                <button class="sort_button"><?php echo $this->Paginator->sort('status', '▼', array('direction' => 'asc',  'lock' => true)) ?></button>
            </th>
            <th class="grace-column header header-column-6">
                検証完了<br>猶予日数</br>
                <button class="sort_button"><?php echo $this->Paginator->sort('grace_days_of_verification_complete', '▲', array('direction' => 'desc', 'lock' => true)) ?></button>
                <button class="sort_button"><?php echo $this->Paginator->sort('grace_days_of_verification_complete', '▼', array('direction' => 'asc',  'lock' => true)) ?></button>
            </th>
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
    <!-- ... -->
    </thead>
    <tbody>
        <?php $today_date = new Datetime(date("y-m-d")); //経過日数、猶予日数の計算に使用?>
        <?php foreach ($items as $item): ?>
    <tr id="item_<?php echo h($item['Item']['id'] . '-data'); ?>" class="<?php echo $item['Item']['needs_supp_confirm'] == 0 ? 'needs-no-confirm' : '' ?>"
        data-id="<?php echo h($item['Item']['id']); ?>" data-controller="items">
        <td class="header header-column-1 record id-column" >
            <span class="record_text"><?php echo $item['Item']['id']; ?></span>
        </td>
        <td class="header header-column-2 record needs-supp-confirm-column editable-cell column-for-dev">
            <span class="record_text"><?php echo $item['Item']['needs_supp_confirm'] == 1 ? '必要' : '不要' ?></span>
        </td>
        <td class="header header-column-3 record content-column editable-cell">
            <span class="record_text"><?php echo $item['Item']['content']; ?></span>
        </td>
        <td class="header header-column-4 record date-column editable-cell">
            <span class="record_text"><?php echo $item['Item']['due_date_for_release']; ?></span>
        </td>
        <td class="header header-column-5 record status-column editable-cell">
            <span class="record_text"><?php echo str_replace("業", "業<br>", $item['Item']['status']); ?></span>
        </td>
        <td class="header header-column-6 record grace-column">
            <span class="record_text"><?php
                $due_date_for_release = new Datetime($item['Item']['due_date_for_release']);
                if (!empty($item['Item']['due_date_for_release'])) {
                    echo str_replace('+', '', $today_date->diff($due_date_for_release)->format('%R%a'));
                }
            ?></span>
        </td>
        <td class="record category-column editable-cell" id="<?php echo $item['Item']['id'] . "-category";?>" data-column="category">
            <span class="record_text"><?php echo $item['Item']['category']; ?></span>
        </td>
        <td class="record division-column editable-cell" id="<?php echo $item['Item']['id'] . "-division";?>" data-column="division">
            <span class="record_text"><?php echo $item['Item']['division']; ?></span>
        </td>
        <td class = "record editable-cell url-column" id="<?php echo $item['Item']['id'] . "-chatwork_url";?>" data-column="chatwork_url">
            <a href = "<?php echo $item['Item']['chatwork_url']; ?>" target="_blank">
                <span class="record_text"><?php echo $item['Item']['chatwork_url']; ?></span>
            </a>
        </td>
        <td class = "record editable-cell url-column column-for-dev" id="<?php echo $item['Item']['id'] . "-github_url";?>" data-column="github_url">
            <a href="<?php echo $item['Item']['github_url']; ?>" target="_blank">
                <span class="record_text"><?php echo $item['Item']['github_url']; ?></span>
            </a>
        </td>
        <td class = "record editable-cell url-column" id="<?php echo $item['Item']['id'] . "-verification_enviroment_url";?>" data-column="verification_enviroment_url">
            <a href="<?php echo $item['Item']['verification_enviroment_url']; ?>" target="_blank">
                <span class="record_text"><?php echo $item['Item']['verification_enviroment_url']; ?></span>
            </a>
        </td>
        <td class = "record editable-cell date-column" id="<?php echo $item['Item']['id'] . "-pullrequest";?>" data-column="pullrequest">
            <span class="record_text"><?php echo $item['Item']['pullrequest']; ?></span>
        </td>
        <td class = "record editable-cell date-column" id="<?php echo $item['Item']['id'] . "-pullrequest_update";?>" data-column="pullrequest_update">
            <span class="record_text"><?php echo $item['Item']['pullrequest_update']; ?></span>
        </td>
        <td class = "record editable-cell date-column" id="<?php echo $item['Item']['id'] . "-tech_release_judgement";?>" data-column="tech_release_judgement">
            <span class="record_text"><?php echo $item['Item']['tech_release_judgement']; ?></span>
        </td>
        <td class = "record editable-cell date-column" id="<?php echo $item['Item']['id'] . "-supp_release_judgement";?>" data-column="supp_release_judgement">
            <span class="record_text"><?php echo $item['Item']['supp_release_judgement']; ?></span>
        </td>
        <td class = "record editable-cell verifier-column" id="<?php echo $item['Item']['id'] . "-verifier_id";?>" data-column="verifier_id">
            <span class="record_text"><?php echo !empty($item['Item']['verifier_id']) ? $verifier_names[$item['Item']['verifier_id']] : '未設定'; ?></span>
        </td>
        <td class = "record editable-cell priority-column" id="<?php echo $item['Item']['id'] . "-manual_exists";?>" data-column="manual_exists">
            <span class="record_text"><?php echo $item['Item']['manual_exists'] == 1 ? '◯' : '✕'; ?></span>
        </td>
        <td class = "record editable-cell date-column" id="<?php echo $item['Item']['id'] . "-sale_release_judgement";?>" data-column="sale_release_judgement">
            <span class="record_text"><?php echo $item['Item']['sale_release_judgement']; ?></span>
        </td>
        <td class = "record day_count-column" id="<?php echo $item['Item']['id'] . "-elapsed";?>" data-column="data-column">
            <span class="record_text"><?php
                $pullrequest_date = new Datetime($item['Item']['pullrequest']);
                echo $today_date->diff($pullrequest_date)->format('%a');;
            ?></span>
        </td>
        <td class = "record editable-cell date-column" id="<?php echo $item['Item']['id'] . "-scheduled_release_date";?>" data-column="scheduled_release_date">
            <span class="record_text"><?php echo $item['Item']['scheduled_release_date']; ?></span>
        </td>
        <td class = "record editable-cell date-column" id="<?php echo $item['Item']['id'] . "-merge_finish_date_to_master";?>" data-column="merge_finish_date_to_master">
            <span class="record_text"><?php echo $item['Item']['merge_finish_date_to_master']; ?></span>
        </td>
        <td class = "record editable-cell comment-column" id="<?php echo $item['Item']['id'] . "-confirm_points";?>" data-column="confirm_points">
            <div class="record_text"><?php echo str_replace(array("\r\n", "\r", "\n"), '</br>', $item['Item']['confirm_points']); ?></div>
        </td>
        <td class = "comment-column" data-column_name = "confirm_comment" id="<?php echo $item['Item']['id'] . "-verification_history";?>" data-column="verification_history">
            <table class="comment-table">
                <?php if(!empty($item['verification_history'])):?>
                <?php foreach ($item['verification_history'] as $verification_history): ?>
                    <tr class="comment-table-header">
                        <td class="comment-table-header td-of-table-in-row"><?php echo $verifier_names[$verification_history['verifier_id']];?></td>
                        <td class="comment-table-header td-of-table-in-row"><?php echo $verification_history['created'];?></td>
                    </tr>
                    <tr class="comment-content" data-id="<?php echo $verification_history['id']?>" data-controller="verification_histories">
                        <td class="editable-comment td-of-table-in-row record editable-cell" colspan="2" id="<?php echo $verification_history['id']?>-verification_history-comment"
                            data-column = "verification_history">
                            <?php echo str_replace('\n', '<br>', $verification_history['comment']);?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php endif?>
            </table>
            <?php if(!$completed_mode_flag):?>
            <button type="button" class="add-comment" id="<?php echo $item['Item']['id'] . '-add-verification_history';?>">
                新規コメント
            </button>
            <?php endif?>
            <div class = "comment-input-area"></div>
        </td>
        <td class = "comment-column" data-column_name = "response_to_confirm_comment"
            id="<?php echo $item['Item']['id'] . "-response_to_confirm_comment";?>" data-column="response_to_confirm_comment">
            <table class="comment-table">
                <?php if(!empty($item['confirm_comment_response'])):?>
                <?php foreach ($item['confirm_comment_response'] as $confirm_comment_response): ?>
                    <tr class="comment-table-header">
                        <td class="comment-table-header td-of-table-in-row"><?php echo $author_names[$confirm_comment_response['author_id']];?></td>
                        <td class="comment-table-header td-of-table-in-row"><?php echo $confirm_comment_response['created'];?></td>
                    </tr>
                    <tr class="comment-content" data-id="<?php echo $confirm_comment_response['id']?>" data-controller="confirm_comment_responses">
                        <td class="editable-comment td-of-table-in-row record editable-cell" colspan="2" id="<?php echo $confirm_comment_response['id']?>-verification_history-comment"
                            data-column = "verification_history">
                            <?php echo str_replace('\n', '<br>', $confirm_comment_response['comment']);?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php endif?>
            </table>
            <?php if(!$completed_mode_flag):?>
            <button type="button" class="add-comment" id="<?php echo $item['Item']['id'] . '-add-confirm_comment_response';?>">
                新規コメント
            </button>
            <?php endif?>
            <div class = "comment-input-area"></div>
        </td>
        <td class = "record author-column" id="<?php echo $item['Item']['id'] . "-author_id";?>" data-column="author_id">
            <span class="record_text"><?php echo !is_null($item['Item']['author_id']) ? $author_names[$item['Item']['author_id']] : '未設定'; ?></span>
        </td>
        <td class = "record editable-cell point-column column-for-dev" id="<?php echo $item['Item']['id'] . "-pivotal_point";?>" data-column="pivotal_point">
            <span class="record_text"><?php echo $item['Item']['pivotal_point']; ?></span>
        </td>
        <td class = "record date-column" id="<?php echo $item['Item']['id'] . "-modified";?>" data-column="modified">
            <span class="record_text">
                <?php $modified_date = new Datetime($item['Item']['modified']);?>
                <?php echo $modified_date->format('Y-m-d');?>
            </span>
        </td>
        <td class= "complete_column">
            <div class="complete">
                <button type="button" class="<?php echo !$completed_mode_flag ? 'complete_button' : 'incomplete_button'?>" id="<?php echo $item['Item']['id'] . "-complete_button";?>" data-column="">
                    <?php echo !$completed_mode_flag ? '完了' : '戻す'?>
                </button>
            </div>
        </td>
    </tr>
    <?php endforeach?>
    <!-- ... -->
  </tbody>
</table>
