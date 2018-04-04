<!-- File: /app/View/Posts/index.ctp -->
<?php
ini_set("display_errors", 'On');
error_reporting(E_ALL);
?>

<?php echo $this->Html->script('index.js');?>
<?php echo $this->Html->css('index.css');?>
<?php echo $this->Form->create('Item', array('url' => 'add'));?>

<div id="view_part">
<table id="view_part_header">
    <tr class="table_titles">
        <th class="id_row">番号</th>
        <th class="category_row">カテゴリ</th>
        <th class="division_row">区分</th>
        <th class="content_row">内容</th>
    </tr>
    <?php foreach ($items as $item): ?>
    <tr id="item_<?php echo h($item['Item']['id'] . '-head'); ?>" class="view_part_item">
        <td class="record id_row" id="<?php echo $item['Item']['id'] . "-id";?>">
            <span class="record_text"><?php echo $item['Item']['id']; ?></span>
        </td>
        <td class="record category_row" id="<?php echo $item['Item']['id'] . "-category";?>">
            <span class="record_text"><?php echo $item['Item']['category']; ?></span>
        </td>
        <td class="record division_row" id="<?php echo $item['Item']['id'] . "-division";?>">
            <span class="record_text"><?php echo $item['Item']['division']; ?></span>
        </td>
        <td class="record content_row" id="<?php echo $item['Item']['id'] . "-content";?>">
            <span class="record_text"><?php echo $item['Item']['content']; ?></span>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<div id='view_part_data'>
    <table id="data_table" class="table_view_part">
        <tr class="table_titles">
            <th class="url_row">chatwork URL</th>
            <th class="url_row">github URL</th>
            <th class="url_row">個別検証環境URL</th>
            <th>確認優先度<br>（必須リリース日）</th>
            <th class="date_row">プルリク</th>
            <th class="date_row">プルリク<br>更新日</th>
            <th>ステータス</th>
            <th class="date_row">技術リリース<br>OK判断日</th>
            <th class="date_row">サポートリリース<br>OK判断日</th>
            <th class="date_row">営業リリース<br>OK判断日</th>
            <th class="day_count_row">経過日数</th>
            <th class="date_row">リリース<br>予定日</th>
            <th class="day_count_row">検証完了<br>猶予日数</th>
            <th class="date_row">master<br>マージ完了日</th>
            <th class="comment_row">確認ポイント</th>
            <th class="comment_row">確認コメント</th>
            <th class="comment_row">確認コメント対応</th>
            <th>
                作成者
            </th>
            <th class="date_row">作成日時</th>
            <th class="date_row">最終更新日時</th>
            <th></th>
        </tr>

        <?php $today_date = new Datetime(date("y-m-d")); //経過日数、猶予日数の計算に使用?>
        <?php foreach ($items as $item): ?>
        <tr id="item_<?php echo h($item['Item']['id'] . '-data'); ?>" class="view_part_item">
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
            <td class = "record <?php if($item['Item']['confirm_priority'] == "高"){ echo "high_priority";} ?>" id="<?php echo $item['Item']['id'] . '-confirm_priority';?>">
                <?php echo $item['Item']['confirm_priority']; ?>
            </td>
            <td class = "record" id="<?php echo $item['Item']['id'] . "-pullrequest";?>">
                <span class="record_text"><?php echo $item['Item']['pullrequest']; ?></span>
            </td>
            <td class = "record" id="<?php echo $item['Item']['id'] . "-pullrequest_update";?>">
                <span class="record_text"><?php echo $item['Item']['pullrequest_update']; ?></span>
            </td>
            <td class = "record" id="<?php echo $item['Item']['id'] . "-status";?>">
                <span class="record_text"><?php echo $item['Item']['status']; ?></span>
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
            <td>
                <span class="record_text"><?php echo $item['Item']['author']; ?></span>
            </td>
            <td>
                <span class="record_text"><?php echo $item['Item']['created']; ?></span>
            </td>
            <td>
                <span class="record_text"><?php echo $item['Item']['modified']; ?></span>
            </td>
            <td><button type="button" class = "complete_button" id="<?php echo $item['Item']['id'] . "-incomplete_button";?>">未完了に戻す</button></td>
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
