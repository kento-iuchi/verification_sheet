<!-- File: /app/View/Posts/index.ctp -->
<?php echo $this->Html->script('jquery-3.2.1.min.js', array( 'inline' => false )); ?>
<?php echo $this->Html->script('jquery-migrate-3.0.1.js', array( 'inline' => false )); ?>
<?php echo $this->Html->script('jquery-ui.js', array( 'inline' => false )); ?>
<?php echo $this->Html->script('index.js', array( 'inline' => false )); ?>

<?php echo $this->Html->css('jquery-ui.css', array( 'inline' => false )); ?>
<?php echo $this->Form->create('Item', array('url' => 'add'));?>
<div id="view_part">
<table id="view_part_header">
    <tr class="table_titles">
        <th>番号</th>
        <th>カテゴリ</th>
        <th>区分</th>
        <th>内容</th>
    </tr>
    <?php foreach ($items as $item): ?>
    <tr id="item_<?php echo h($item['Item']['id'] . '_head'); ?>" class="view_part_item">
        <td id="<?php echo $item['Item']['id'] . "_id";?>"><?php echo $item['Item']['id']; ?></td>
        <td id="<?php echo $item['Item']['id'] . "_category";?>"><?php echo $item['Item']['category']; ?></td>
        <td id="<?php echo $item['Item']['id'] . "_division";?>"><?php echo $item['Item']['division']; ?></td>
        <td id="<?php echo $item['Item']['id'] . "_content";?>"><?php echo $item['Item']['content']; ?></td>
    </tr>
    <?php endforeach; ?>
    <tr class="input_part">
        <td></td>
        <td><?php echo $this->Form->input('category', array('label' => false, 'style' => 'width:80px; height:40px;'));?></td>
        <td><?php echo $this->Form->input('division', array('label' => false, 'style' => 'width:60px;'));?></td>
        <td><?php echo $this->Form->input('content', array('label' => false));?></td>
    </tr>
</table>
<div id="view_part_data">
<table class="table_view_part">
    <tr class="table_titles">
        <th>chatwork URL</th>
        <th>github URL</th>
        <th>確認優先度<br>（必須リリース日）</th>
        <th>プルリク</th>
        <th>プルリク<br>更新日</th>
        <th>ステータス</th>
        <th>技術リリース<br>OK判断日</th>
        <th>サポートリリース<br>OK判断日</th>
        <th>営業リリース<br>OK判断日</th>
        <th>経過日数</th>
        <th>リリース<br>予定日</th>
        <th>検証完了<br>猶予日数</th>
        <th>master<br>マージ完了日</th>
        <th>確認ポイント</th>
        <th>確認コメント</th>
        <th>確認コメント対応</th>
        <th>作成日時</th>
        <th>最終更新日時</th>
        <th></th>
        <th></th>
    </tr>
    <?php foreach ($items as $item): ?>
    <tr id="item_<?php echo h($item['Item']['id'] . '_data'); ?>" class="view_part_item">
        <td id="<?php echo $item['Item']['id'] . "_chatwork_url";?>"><?php echo $this->Eip->input('Item.chatwork_url', $item); ?></td>
        <td id="<?php echo $item['Item']['id'] . "_github_url";?>"><?php echo $item['Item']['github_url']; ?></td>
        <td id="<?php echo $item['Item']['id'] . "_confirm_priority";?>"><?php echo $item['Item']['confirm_priority']; ?></td>
        <td id="<?php echo $item['Item']['id'] . "_pullrequest";?>"><?php echo $item['Item']['pullrequest']; ?></td>
        <td id="<?php echo $item['Item']['id'] . "_pullrequest_update";?>"><?php echo $item['Item']['pullrequest_update']; ?></td>
        <td id="<?php echo $item['Item']['id'] . "_status";?>"><?php echo $item['Item']['status']; ?></td>
        <td id="<?php echo $item['Item']['id'] . "_tech_release_judgement";?>"><?php echo $item['Item']['tech_release_judgement']; ?></td>
        <td id="<?php echo $item['Item']['id'] . "_supp_release_judgement";?>"><?php echo $item['Item']['supp_release_judgement']; ?></td>
        <td id="<?php echo $item['Item']['id'] . "_sale_release_judgement";?>"><?php echo $item['Item']['sale_release_judgement']; ?></td>
        <td id="<?php echo $item['Item']['id'] . "_elapsed";?>"><?php echo $item['Item']['elapsed']; ?></td>
        <td id="<?php echo $item['Item']['id'] . "_scheduled_release_date";?>"><?php echo $item['Item']['scheduled_release_date']; ?></td>
        <td id="<?php echo $item['Item']['id'] . "_grace_days_of_verification_complete";?>"><?php echo $item['Item']['grace_days_of_verification_complete']; ?></td>
        <td id="<?php echo $item['Item']['id'] . "_merge_finish_date_to_master";?>"><?php echo $item['Item']['merge_finish_date_to_master']; ?></td>
        <td id="<?php echo $item['Item']['id'] . "_confirm_points";?>"><?php echo $item['Item']['confirm_points']; ?></td>
        <td id="<?php echo $item['Item']['id'] . "_confirm_comment";?>"><?php echo $item['Item']['confirm_comment']; ?></td>
        <td id="<?php echo $item['Item']['id'] . "_response_to_confirm_comment";?>"><?php echo $item['Item']['response_to_confirm_comment']; ?></td>
        <td><?php echo $item['Item']['created']; ?></td>
        <td><?php echo $item['Item']['modified']; ?></td>
        <td><?php echo $this->Html->link('編集', array('action'=>'edit', $item['Item']['id'])); ?></td>
        <td><?php echo $this->Form->postLink('削除', array('action' => 'delete', $item['Item']['id']), array('confirm'=>'削除しますか?')); ?></td>
    </tr>
    <?php endforeach; ?>
    <tr class="input_part">
        <td><?php echo $this->Form->input('chatwork_url', array('label' => false, 'style' => 'width:160px;'));?></td>
        <td><?php echo $this->Form->input('github_url', array('label' => false));?></td>
        <td><?php echo $this->Form->input('confirm_priority', array('label' => false));?></td>
        <td><?php echo $this->Datepicker->datepicker('pullrequest', array('type' => 'text', 'label' => false));?></td>
        <td><?php echo $this->Datepicker->datepicker('pullrequest_update', array('type' => 'text', 'label' => false));?></td>
        <td><?php echo $this->Form->input('status', array('label' => false));?></td>
        <td><?php echo $this->Datepicker->datepicker('tech_release_judgement', array('type' => 'text', 'label' => false));?></td>
        <td><?php echo $this->Datepicker->datepicker('supp_release_judgement', array('type' => 'text', 'label' => false));?></td>
        <td><?php echo $this->Datepicker->datepicker('sale_release_judgement', array('type' => 'text', 'label' => false));?></td>
        <td><?php echo $this->Form->input('elapsed', array('label' => false));?></td>
        <td><?php echo $this->Datepicker->datepicker('scheduled_release_date', array('type' => 'text', 'label' => false));?></td>
        <td><?php echo $this->Form->input('grace_days_of_verification_complete', array('label' => false));?></td>
        <td><?php echo $this->Datepicker->datepicker('merge_finish_date_to_master', array('type' => 'text', 'label' => false));?></td>
        <td><?php echo $this->Form->input('confirm_priority', array('label' => false));?></td>
        <td><?php echo $this->Form->input('confirm_comment', array('label' => false));?></td>
        <td><?php echo $this->Form->input('response_to_confirm_comment', array('label' => false));?></td>
        <td><?php echo $this->Form->end('送信');?></td>
    </tr>
</table>
</div>
</div>
<?php unset($item); ?>
