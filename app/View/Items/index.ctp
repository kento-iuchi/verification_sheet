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
        <td class="record id_row" id="<?php echo $item['Item']['id'] . "-id";?>"><?php echo $item['Item']['id']; ?></td>
        <td class="record category_row" id="<?php echo $item['Item']['id'] . "-category";?>"><?php echo $item['Item']['category']; ?></td>
        <td class="record division_row" id="<?php echo $item['Item']['id'] . "-division";?>"><?php echo $item['Item']['division']; ?></td>
        <td class="record content_row" id="<?php echo $item['Item']['id'] . "-content";?>"><?php echo $item['Item']['content']; ?></td>
    </tr>
    <?php endforeach; ?>
    <tr class="input_part">
        <td></td>
        <td><?php echo $this->Form->input('category', array('label' => false, 'style' => 'width:80px; height:40px;'));?></td>
        <td><?php echo $this->Form->input('division', array(
                'label' => false,
                'style' => 'width:60px;',
                'options' => array(
                    '改善' => '改善',
                    '機能追加' => '機能追加',
                    'バグ' => 'バグ',
                    )
                ));
            ?></td>
        <td><?php echo $this->Form->input('content', array('label' => false));?></td>
    </tr>
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
            <th class="date_row">作成日時</th>
            <th class="date_row">最終更新日時</th>
            <th></th>
        </tr>

        <?php $today_date = new Datetime(date("y-m-d")); //経過日数、猶予日数の計算に使用?>
        <?php foreach ($items as $item): ?>
        <tr id="item_<?php echo h($item['Item']['id'] . '-data'); ?>" class="view_part_item">
            <td class = "record" id="<?php echo $item['Item']['id'] . "-chatwork_url";?>">
                <a href = "<?php echo $item['Item']['chatwork_url']; ?>">
                    <span><?php echo $item['Item']['chatwork_url']; ?></span>
                </a>
            </td>
            <td class = "record" id="<?php echo $item['Item']['id'] . "-github_url";?>">
                <a href="<?php echo $item['Item']['github_url']; ?>">
                    <span><?php echo $item['Item']['github_url']; ?></span>
                </a>
            </td>
            <td class = "record" id="<?php echo $item['Item']['id'] . "-verification_enviroment_url";?>">
                <a href="<?php echo $item['Item']['verification_enviroment_url']; ?>">
                    <span><?php echo $item['Item']['verification_enviroment_url']; ?></span>
                </a>
            </td>
            <td class = "record <?php if($item['Item']['confirm_priority'] == "高"){ echo "high_priority";} ?>" id="<?php echo $item['Item']['id'] . '-confirm_priority';?>">
                <?php echo $item['Item']['confirm_priority']; ?>
            </td>
            <td class = "record" id="<?php echo $item['Item']['id'] . "-pullrequest";?>"><?php echo $item['Item']['pullrequest']; ?></td>
            <td class = "record" id="<?php echo $item['Item']['id'] . "-pullrequest_update";?>"><?php echo $item['Item']['pullrequest_update']; ?></td>
            <td class = "record" id="<?php echo $item['Item']['id'] . "-status";?>"><?php echo $item['Item']['status']; ?></td>
            <td class = "record" id="<?php echo $item['Item']['id'] . "-tech_release_judgement";?>"><?php echo $item['Item']['tech_release_judgement']; ?></td>
            <td class = "record" id="<?php echo $item['Item']['id'] . "-supp_release_judgement";?>"><?php echo $item['Item']['supp_release_judgement']; ?></td>
            <td class = "record" id="<?php echo $item['Item']['id'] . "-sale_release_judgement";?>"><?php echo $item['Item']['sale_release_judgement']; ?></td>
            <td class = "record" id="<?php echo $item['Item']['id'] . "-elapsed";?>">
                <?php
                    $pullrequest_date = new Datetime($item['Item']['pullrequest']);
                    echo $today_date->diff($pullrequest_date)->format('%a');;
                ?>
            </td>
            <td class = "record" id="<?php echo $item['Item']['id'] . "-scheduled_release_date";?>"><?php echo $item['Item']['scheduled_release_date']; ?></td>
            <td class = "record" id="<?php echo $item['Item']['id'] . "-grace_days_of_verification_complete";?>">
                <?php
                    $scheduled_release_date = new Datetime($item['Item']['scheduled_release_date']);
                    echo str_replace('+', '', $today_date->diff($scheduled_release_date)->format('%R%a'));
                ?>
            </td>
            <td class = "record" id="<?php echo $item['Item']['id'] . "-merge_finish_date_to_master";?>"><?php echo $item['Item']['merge_finish_date_to_master']; ?></td>
            <td class = "record" id="<?php echo $item['Item']['id'] . "-confirm_points";?>">
                <?php echo str_replace(array("\r\n", "\r", "\n"), '</br>', $item['Item']['confirm_points']); ?>
            </td>
            <td class = "record" id="<?php echo $item['Item']['id'] . "-confirm_comment";?>">
                <?php echo str_replace(array("\r\n", "\r", "\n"), '</br>', $item['Item']['confirm_comment']); ?>
            </td>
            <td class = "record" id="<?php echo $item['Item']['id'] . "-response_to_confirm_comment";?>">
                <?php echo str_replace(array("\r\n", "\r", "\n"), '</br>', $item['Item']['response_to_confirm_comment']); ?>
            </td>
            <td><?php echo $item['Item']['created']; ?></td>
            <td><?php echo $item['Item']['modified']; ?></td>
            <td><button type="button" class = "complete_button" id="<?php echo $item['Item']['id'] . "-complete_button";?>">完了</button></td>
        </tr>
        <?php endforeach; ?>

        <tr class="input_part">
            <td><?php echo $this->Form->input('chatwork_url', array('label' => false, 'style' => 'width:160px;'));?></td>
            <td><?php echo $this->Form->input('github_url', array('label' => false));?></td>
            <td><?php echo $this->Form->input('verification_enviroment_url', array('label' => false));?></td>
            <td><?php echo $this->Form->input('confirm_priority', array('label' => false));?></td>
            <td><?php echo $this->Datepicker->datepicker('pullrequest', array('type' => 'text', 'label' => false));?></td>
            <td><?php echo $this->Datepicker->datepicker('pullrequest_update', array('type' => 'text', 'label' => false));?></td>
            <td>
                <?php echo $this->Form->input('status',array(
                      'label' => false,
                      'style' => 'width:160px;',
                      'options' => array(
                          'コードレビュー中' => 'コードレビュー中',
                          '改修中' => '改修中',
                          '技術二重チェック中' => '技術二重チェック中',
                          'サポート・営業確認中' => 'サポート・営業確認中',
                      )
                  ));
                ?>
            </td>
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
