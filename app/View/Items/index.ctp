<!-- File: /app/View/Posts/index.ctp -->
<?php
ini_set("display_errors", 'On');
error_reporting(E_ALL);
?>

<?php echo $this->Html->script('index.js');?>
<?php echo $this->Html->css('index.css');?>
<?php echo $this->Form->create('Item', array('url' => 'add'));?>

<?php echo $this->Html->link(
    '完了済みの項目を表示',
    '/items/show_completed',
    array('class' => 'button',)
);?>
<div id="view_part">
<table id="view_part_header">
    <tr class="table_titles">
        <th class="id_row">
            番号</br>
            <button class="sort_button"><?php echo $this->Paginator->sort('id', '▲', array('direction' => 'desc', 'lock' => true)) ?></button>
            <button class="sort_button"><?php echo $this->Paginator->sort('id', '▼', array('direction' => 'asc',  'lock' => true)) ?></button>
        </th>
        <th class="content_row">内容</th>
        <th>確認優先度<br>
            （必須リリース日)</br>
            <button class="sort_button"><?php echo $this->Paginator->sort('confirm_priority', '▲', array('direction' => 'desc', 'lock' => true)) ?></button>
            <button class="sort_button"><?php echo $this->Paginator->sort('confirm_priority', '▼', array('direction' => 'asc',  'lock' => true)) ?></button></th>
        <th>ステータス</br>
            <button class="sort_button"><?php echo $this->Paginator->sort('status', '▲', array('direction' => 'desc', 'lock' => true)) ?></button>
            <button class="sort_button"><?php echo $this->Paginator->sort('status', '▼', array('direction' => 'asc',  'lock' => true)) ?></button>
        </th>
    </tr>
    <?php foreach ($items as $item): ?>
    <tr id="item_<?php echo h($item['Item']['id'] . '-head'); ?>" class="view_part_item">
        <td class="record id_row" id="<?php echo $item['Item']['id'] . "-id";?>">
            <span class="record_text"><?php echo $item['Item']['id']; ?></span>
        </td>
        <td class="record content_row" id="<?php echo $item['Item']['id'] . "-content";?>">
            <span class="record_text"><?php echo $item['Item']['content']; ?></span>
        </td>
        <td class = "record <?php if($item['Item']['confirm_priority'] == "高"){ echo "high_priority";} ?>" id="<?php echo $item['Item']['id'] . '-confirm_priority';?>">
            <?php echo $item['Item']['confirm_priority']; ?>
        </td>
        <td class = "record" id="<?php echo $item['Item']['id'] . "-status";?>">
            <span class="record_text"><?php echo $item['Item']['status']; ?></span>
        </td>
    </tr>
    <?php endforeach; ?>
    <tr class="input_part">
        <td></td>
        <td><?php echo $this->Form->input('content', array('label' => false));?></td>
        <td><?php echo $this->Form->input('confirm_priority', array('label' => false));?></td>
        <td>
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
    </tr>
</table>

<div id='view_part_data'>
    <table id="data_table" class="table_view_part">
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
            <th class="comment_row">
                確認コメント
            </th>
            <th class="comment_row">
                確認コメント対応
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
            <td class="record category_row" id="<?php echo $item['Item']['id'] . "-category";?>">
                <span class="record_text"><?php echo $item['Item']['category']; ?></span>
            </td>
            <td class="record division_row" id="<?php echo $item['Item']['id'] . "-division";?>">
                <span class="record_text"><?php echo $item['Item']['division']; ?></span>
            </td>
            <td class = "record" id="<?php echo $item['Item']['id'] . "-chatwork_url";?>">
                <a href = "<?php echo $item['Item']['chatwork_url']; ?>">
                    <span class="record_text"><?php echo $item['Item']['chatwork_url']; ?></span>
                </a>
            </td>
            <td class = "record" id="<?php echo $item['Item']['id'] . "-github_url";?>">
                <a href="<?php echo $item['Item']['github_url']; ?>">
                    <span class="record_text"><?php echo $item['Item']['github_url']; ?></span>
                </a>
            </td>
            <td class = "record" id="<?php echo $item['Item']['id'] . "-verification_enviroment_url";?>">
                <a href="<?php echo $item['Item']['verification_enviroment_url']; ?>">
                    <span class="record_text"><?php echo $item['Item']['verification_enviroment_url']; ?></span>
                </a>
            </td>
            <td class = "record" id="<?php echo $item['Item']['id'] . "-pullrequest";?>">
                <span class="record_text"><?php echo $item['Item']['pullrequest']; ?></span>
            </td>
            <td class = "record" id="<?php echo $item['Item']['id'] . "-pullrequest_update";?>">
                <span class="record_text"><?php echo $item['Item']['pullrequest_update']; ?></span>
            </td>
            <td class = "record" id="<?php echo $item['Item']['id'] . "-tech_release_judgement";?>">
                <span class="record_text"><?php echo $item['Item']['tech_release_judgement']; ?></span>
            </td>
            <td class = "record" id="<?php echo $item['Item']['id'] . "-supp_release_judgement";?>">
                <span class="record_text"><?php echo $item['Item']['supp_release_judgement']; ?></span>
            </td>
            <td class = "record" id="<?php echo $item['Item']['id'] . "-sale_release_judgement";?>">
                <span class="record_text"><?php echo $item['Item']['sale_release_judgement']; ?></span>
            </td>
            <td class = "record" id="<?php echo $item['Item']['id'] . "-elapsed";?>">
                <span class="record_text"><?php
                    $pullrequest_date = new Datetime($item['Item']['pullrequest']);
                    echo $today_date->diff($pullrequest_date)->format('%a');;
                ?></span>
            </td>
            <td class = "record" id="<?php echo $item['Item']['id'] . "-scheduled_release_date";?>">
                <span class="record_text"><?php echo $item['Item']['scheduled_release_date']; ?></span>
            </td>
            <td class = "record" id="<?php echo $item['Item']['id'] . "-grace_days_of_verification_complete";?>">
                <span class="record_text"><?php
                    $scheduled_release_date = new Datetime($item['Item']['scheduled_release_date']);
                    echo str_replace('+', '', $today_date->diff($scheduled_release_date)->format('%R%a'));
                ?></span>
            </td>
            <td class = "record" id="<?php echo $item['Item']['id'] . "-merge_finish_date_to_master";?>">
                <span class="record_text"><?php echo $item['Item']['merge_finish_date_to_master']; ?></span>
            </td>
            <td class = "record" id="<?php echo $item['Item']['id'] . "-confirm_points";?>">
                <div class="record_text"><?php echo str_replace(array("\r\n", "\r", "\n"), '</br>', $item['Item']['confirm_points']); ?></span>
            </td>
            <td class = "record" id="<?php echo $item['Item']['id'] . "-confirm_comment";?>">
                <div class="record_text"><?php echo str_replace(array("\r\n", "\r", "\n"), '</br>', $item['Item']['confirm_comment']); ?></span>
            </td>
            <td class = "record" id="<?php echo $item['Item']['id'] . "-response_to_confirm_comment";?>">
                <div class="record_text"><?php echo str_replace(array("\r\n", "\r", "\n"), '</br>', $item['Item']['response_to_confirm_comment']); ?></span>
            </td>
            <td class = "record" id="<?php echo $item['Item']['id'] . "-created";?>">
                <span class="record_text"><?php echo $item['Item']['created']; ?></span>
            </td>
            <td class = "record" id="<?php echo $item['Item']['id'] . "-created";?>">
                <span class="record_text"><?php echo $item['Item']['modified']; ?></span>
            </td>
            <td><div class="complete"><button type="button" class="complete_button" id="<?php echo $item['Item']['id'] . "-complete_button";?>">完了</button></div></td>
        </tr>
        <?php endforeach; ?>

        <tr class="input_part">
            <td><?php echo $this->Form->input('category', array('label' => false));?></td>
            <td><?php echo $this->Form->input('division', array(
                    'label' => false,
                    'options' => array(
                        '改善' => '改善',
                        '機能追加' => '機能追加',
                        'バグ' => 'バグ',
                        )
                    ));
                ?>
            </td>
            <td><?php echo $this->Form->input('chatwork_url', array('label' => false));?></td>
            <td><?php echo $this->Form->input('github_url', array('label' => false));?></td>
            <td><?php echo $this->Form->input('verification_enviroment_url', array('label' => false));?></td>
            <td><?php echo $this->Datepicker->datepicker('pullrequest', array('type' => 'text', 'label' => false));?></td>
            <td><?php echo $this->Datepicker->datepicker('pullrequest_update', array('type' => 'text', 'label' => false));?></td>
            <td><?php echo $this->Datepicker->datepicker('tech_release_judgement', array('type' => 'text', 'label' => false));?></td>
            <td><?php echo $this->Datepicker->datepicker('supp_release_judgement', array('type' => 'text', 'label' => false));?></td>
            <td><?php echo $this->Datepicker->datepicker('sale_release_judgement', array('type' => 'text', 'label' => false));?></td>
            <td><!-- 経過日数 --></td>
            <td><?php echo $this->Datepicker->datepicker('scheduled_release_date', array('type' => 'text', 'label' => false));?></td>
            <td><!-- 検証完了猶予日数 --></td>
            <td><?php echo $this->Datepicker->datepicker('merge_finish_date_to_master', array('type' => 'text', 'label' => false));?></td>
            <td><?php echo $this->Form->input('confirm_points', array('label' => false));?></td>
            <td><?php echo $this->Form->input('confirm_comment', array('label' => false));?></td>
            <td><?php echo $this->Form->input('response_to_confirm_comment', array('label' => false));?></td>
            <td><?php echo $this->Form->end('送信');?></td>
        </tr>

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
