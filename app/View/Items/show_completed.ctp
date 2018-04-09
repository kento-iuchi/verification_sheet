<!-- File: /app/View/Posts/index.ctp -->
<?php
ini_set("display_errors", 'On');
error_reporting(E_ALL);
?>

<?php echo $this->Html->script('index.js');?>
<?php echo $this->Html->css('index.css');?>
<?php echo $this->Html->css('show_completed.css');?>
<?php echo $this->Form->create('Item', array('url' => 'add'));?>

<?php
$verifier_names = array();
$verifiers_count = count($verifier);
for ($vi=0; $vi < $verifiers_count; $vi++) {
    $verifier_names[$vi] = $verifier[$vi]['Verifier']['name'];
}

$author_names = array();
foreach($author as $author_array){
    $author_names[$author_array['Author']['id']] = $author_array['Author']['name'];
}
?>

<?php
echo $this->Html->link(
    '未完了の項目を表示',
    '/items/index',
    array('class' => 'button',)
);
?>
<div>
    <span><h2>期間</h2></span>

</div>
<div id="view_part">
<table id="view_part_header">
    <tr class="table_titles">
        <th class="id_row">
            番号</br>
            <button class="sort_button"><?php echo $this->Paginator->sort('id', '▲', array('direction' => 'desc', 'lock' => true)) ?></button>
            <button class="sort_button"><?php echo $this->Paginator->sort('id', '▼', array('direction' => 'asc',  'lock' => true)) ?></button>
        </th>
        <th class="content_row">内容</th>
        <th class="priority-row">確認優先度
            <button class="sort_button"><?php echo $this->Paginator->sort('confirm_priority', '▲', array('direction' => 'desc', 'lock' => true)) ?></button>
            <button class="sort_button"><?php echo $this->Paginator->sort('confirm_priority', '▼', array('direction' => 'asc',  'lock' => true)) ?></button>
        </th>
        <th>ステータス</br>
            <button class="sort_button"><?php echo $this->Paginator->sort('status', '▲', array('direction' => 'desc', 'lock' => true)) ?></button>
            <button class="sort_button"><?php echo $this->Paginator->sort('status', '▼', array('direction' => 'asc',  'lock' => true)) ?></button>
        </th>
    </tr>
    <?php foreach ($items as $item): ?>
    <tr id="item_<?php echo h($item['Item']['id'] . '-head'); ?>" class="view_part_item">
        <td class="record id_row">
            <span class="record_text"><?php echo $item['Item']['id']; ?></span>
        </td>
        <td class="record content_row">
            <span class="record_text"><?php echo $item['Item']['content']; ?></span>
        </td>
        <td class = "record priority-row">
            <?php
                $confirm_priority_array = array('不要', '低', '中', '高');
                echo $confirm_priority_array[$item['Item']['confirm_priority']]; ?>
        </td>
        <td class = "record">
            <span class="record_text"><?php echo str_replace("業", "業<br>", $item['Item']['status']); ?></span>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<div id='view_part_data'>
    <table id="data_table" class="data-part-main-table">
        <tr class="table_titles">
            <th class="category_row">
                カテゴリ</br>
                <button class="sort_button"><?php echo $this->Paginator->sort('category', '▲', array('direction' => 'desc', 'lock' => true)) ?></button>
                <button class="sort_button"><?php echo $this->Paginator->sort('category', '▼', array('direction' => 'asc',  'lock' => true)) ?></button>
            </th>
            <th class="division_row">
                区分</br>
                <button class="sort_button"><?php echo $this->Paginator->sort('division', '▲', array('direction' => 'desc', 'lock' => true)) ?></button>
                <button class="sort_button"><?php echo $this->Paginator->sort('division', '▼', array('direction' => 'asc',  'lock' => true)) ?></button>
            </th>
            <th class="url_row">
                chatwork URL
            </th>
            <th class="url_row">
                github URL
            </th>
            <th class="url_row">
                個別検証環境URL
            </th>
            <th class="date_row">プルリク</br>
                <button class="sort_button"><?php echo $this->Paginator->sort('pullrequest', '▲', array('direction' => 'desc', 'lock' => true)) ?></button>
                <button class="sort_button"><?php echo $this->Paginator->sort('pullrequest', '▼', array('direction' => 'asc',  'lock' => true)) ?></button>
            </th>
            <th class="date_row">
                プルリク<br>更新日</br>
                <button class="sort_button"><?php echo $this->Paginator->sort('pullrequest_update', '▲', array('direction' => 'desc', 'lock' => true)) ?></button>
                <button class="sort_button"><?php echo $this->Paginator->sort('pullrequest_update', '▼', array('direction' => 'asc',  'lock' => true)) ?></button>
            </th>
            <th class="date_row">
                技術リリース<br>OK判断日</br>
                <button class="sort_button"><?php echo $this->Paginator->sort('tech_release_judgement', '▲', array('direction' => 'desc', 'lock' => true)) ?></button>
                <button class="sort_button"><?php echo $this->Paginator->sort('tech_release_judgement', '▼', array('direction' => 'asc',  'lock' => true)) ?></button>
            </th>
            <th class="date_row">
                サポートリリース<br>OK判断日</br>
                <button class="sort_button"><?php echo $this->Paginator->sort('supp_release_judgement', '▲', array('direction' => 'desc', 'lock' => true)) ?></button>
                <button class="sort_button"><?php echo $this->Paginator->sort('supp_release_judgement', '▼', array('direction' => 'asc',  'lock' => true)) ?></button></th>
            <th class="date_row">
                営業リリース<br>OK判断日</br>
                <button class="sort_button"><?php echo $this->Paginator->sort('sale_release_judgement', '▲', array('direction' => 'desc', 'lock' => true)) ?></button>
                <button class="sort_button"><?php echo $this->Paginator->sort('sale_release_judgement', '▼', array('direction' => 'asc',  'lock' => true)) ?></button>
            </th>
            <th class="day_count_row">
                経過日数</br>
                <button class="sort_button"><?php echo $this->Paginator->sort('elapsed', '▲', array('direction' => 'desc', 'lock' => true)) ?></button>
                <button class="sort_button"><?php echo $this->Paginator->sort('elapsed', '▼', array('direction' => 'asc',  'lock' => true)) ?></button>
            </th>
            <th class="date_row">
                リリース<br>予定日</br>
                <button class="sort_button"><?php echo $this->Paginator->sort('scheduled_release_date', '▲', array('direction' => 'desc', 'lock' => true)) ?></button>
                <button class="sort_button"><?php echo $this->Paginator->sort('scheduled_release_date', '▼', array('direction' => 'asc',  'lock' => true)) ?></button>
            </th>
            <th class="day_count_row">
                検証完了<br>猶予日数</br>
                <button class="sort_button"><?php echo $this->Paginator->sort('grace_days_of_verification_complete', '▲', array('direction' => 'desc', 'lock' => true)) ?></button>
                <button class="sort_button"><?php echo $this->Paginator->sort('grace_days_of_verification_complete', '▼', array('direction' => 'asc',  'lock' => true)) ?></button>
            </th>
            <th class="date_row">
                master<br>マージ完了日</br>
                <button class="sort_button"><?php echo $this->Paginator->sort('merge_finish_date_to_master', '▲', array('direction' => 'desc', 'lock' => true)) ?></button>
                <button class="sort_button"><?php echo $this->Paginator->sort('merge_finish_date_to_master', '▼', array('direction' => 'asc',  'lock' => true)) ?></button>
            </th>
            <th class="comment_row">
                確認ポイント
            </th>
            <th class="verification-history-row" data-options='<?php echo json_encode($verifier_names)?>'>
                検証履歴
            </th>
            <th class="comment_row">
                確認コメント
            </th>
            <th class="comment_row">
                確認コメント対応
            </th>
            <th class="author-row" data-options='<?php echo json_encode($author_names)?>'>
                作成者
            </th>
            <th class="date_row">
                作成日時</br>
                <button class="sort_button"><?php echo $this->Paginator->sort('created', '▲', array('direction' => 'desc', 'lock' => true)) ?></button>
                <button class="sort_button"><?php echo $this->Paginator->sort('created', '▼', array('direction' => 'asc',  'lock' => true)) ?></button>
            </th>
            <th class="date_row">
                最終更新日時</br>
                <button class="sort_button"><?php echo $this->Paginator->sort('modified', '▲', array('direction' => 'desc', 'lock' => true)) ?></button>
                <button class="sort_button"><?php echo $this->Paginator->sort('modified', '▼', array('direction' => 'asc',  'lock' => true)) ?></button>
            </th>
            <th></th>
        </tr>

        <?php $today_date = new Datetime(date("y-m-d")); //経過日数、猶予日数の計算に使用?>
        <?php foreach ($items as $item): ?>
        <tr id="item_<?php echo h($item['Item']['id'] . '-data'); ?>" class="view_part_item">
            <td class="record">
                <span class="record_text"><?php echo $item['Item']['category']; ?></span>
            </td>
            <td class="record">
                <span class="record_text"><?php echo $item['Item']['division']; ?></span>
            </td>
            <td class = "record">
                <a href = "<?php echo $item['Item']['chatwork_url']; ?>" target="_blank">
                    <span class="record_text"><?php echo $item['Item']['chatwork_url']; ?></span>
                </a>
            </td>
            <td class = "record">
                <a href="<?php echo $item['Item']['github_url']; ?>" target="_blank">
                    <span class="record_text"><?php echo $item['Item']['github_url']; ?></span>
                </a>
            </td>
            <td class = "record">
                <a href="<?php echo $item['Item']['verification_enviroment_url']; ?>" target="_blank">
                    <span class="record_text"><?php echo $item['Item']['verification_enviroment_url']; ?></span>
                </a>
            </td>
            <td class = "record">
                <span class="record_text"><?php echo $item['Item']['pullrequest']; ?></span>
            </td>
            <td class = "record">
                <span class="record_text"><?php echo $item['Item']['pullrequest_update']; ?></span>
            </td>
            <td class = "record">
                <span class="record_text"><?php echo $item['Item']['tech_release_judgement']; ?></span>
            </td>
            <td class = "record">
                <span class="record_text"><?php echo $item['Item']['supp_release_judgement']; ?></span>
            </td>
            <td class = "record">
                <span class="record_text"><?php echo $item['Item']['sale_release_judgement']; ?></span>
            </td>
            <td class = "record">
                <span class="record_text"><?php
                    $pullrequest_date = new Datetime($item['Item']['pullrequest']);
                    echo $today_date->diff($pullrequest_date)->format('%a');;
                ?></span>
            </td>
            <td class = "record">
                <span class="record_text"><?php echo $item['Item']['scheduled_release_date']; ?></span>
            </td>
            <td class = "record">
                <span class="record_text"><?php
                    $scheduled_release_date = new Datetime($item['Item']['scheduled_release_date']);
                    echo str_replace('+', '', $today_date->diff($scheduled_release_date)->format('%R%a'));
                ?></span>
            </td>
            <td class = "record">
                <span class="record_text"><?php echo $item['Item']['merge_finish_date_to_master']; ?></span>
            </td>
            <td class = "record">
                <div class="record_text"><?php echo str_replace(array("\r\n", "\r", "\n"), '</br>', $item['Item']['confirm_points']); ?></div>
            </td>
            <td class = "record verification-history-row" id="<?php echo $item['Item']['id'] . "-verification_history";?>">
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
                <button type="button" class="add-verification-history" id="<?php echo $item['Item']['id'] . '-add-verification-history';?>">新規作成</button>
                <div id="<?php echo $item['Item']['id'];?>-verification-history-input-area"></div>
            </td>
            <td class = "record">
                <div class="record_text"><?php echo str_replace(array("\r\n", "\r", "\n"), '</br>', $item['Item']['confirm_comment']); ?></div>
            </td>
            <td class = "record">
                <div class="record_text"><?php echo str_replace(array("\r\n", "\r", "\n"), '</br>', $item['Item']['response_to_confirm_comment']); ?></div>
            </td>
            <td class = "record">
                <span class="record_text"><?php echo $author_names[$item['Item']['author_id']]; ?></span>
            </td>
            <td class = "record">
                <span class="record_text"><?php echo $item['Item']['created']; ?></span>
            </td>
            <td class = "record">
                <span class="record_text"><?php echo $item['Item']['modified']; ?></span>
            </td>
            <td><div class="complete"><button type="button" class="incomplete_button" id="<?php echo $item['Item']['id'] . "-complete_button";?>">戻す</button></div></td>
        </tr>
        <?php endforeach; ?>


    </table>
</div>
</div>
<div id="page_selecter">
    <?php echo $this->Paginator->numbers (
        array (
            'before' => $this->Paginator->hasPrev() ? $this->Paginator->first('<<').' | ' : '',
            'after' => $this->Paginator->hasNext() ? ' | '.$this->Paginator->last('>>') : '',
        )
    );
    ?>
</div>
