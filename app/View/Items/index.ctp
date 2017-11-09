<!-- File: /app/View/Posts/index.ctp -->


<table>
    <tr id="table_titles">
        <th>番号</th>
        <th>カテゴリ</th>
        <th>区分</th>
        <th>内容</th>
        <th>chatwork URL</th>
        <th>github URL</th>
        <th>確認優先度<br>（必須リリース日）</th>
        <th>プルリク</th>
        <th>プルリク<br>更新日</th>
        <th>ステータス</th>
        <th>技術リリース<br>OK判断日</th>
        <th>サポートリリース<br>OK判断日</th>
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
    <tr id="item_<?php echo h($item['Item']['id']); ?>">
        <td><?php echo $item['Item']['id']; ?></td>
        <td><?php echo $item['Item']['category']; ?></td>
        <td><?php echo $item['Item']['division']; ?></td>
        <td><?php echo $item['Item']['content']; ?></td>
        <td><?php echo $item['Item']['chatwork_url']; ?></td>
        <td><?php echo $item['Item']['github_url']; ?></td>
        <td><?php echo $item['Item']['confirm_priority']; ?></td>
        <td><?php echo $item['Item']['pullrequest']; ?></td>
        <td><?php echo $item['Item']['pullrequest_update']; ?></td>
        <td><?php echo $item['Item']['status']; ?></td>
        <td><?php echo $item['Item']['tech_release_judgement']; ?></td>
        <td><?php echo $item['Item']['supp_release_judgement']; ?></td>
        <td><?php echo $item['Item']['elapsed']; ?></td>
        <td><?php echo $item['Item']['scheduled_release_date']; ?></td>
        <td><?php echo $item['Item']['grace_days_of_verification_complete']; ?></td>
        <td><?php echo $item['Item']['merge_finish_date_to_master']; ?></td>
        <td><?php echo $item['Item']['confirm_points']; ?></td>
        <td><?php echo $item['Item']['confirm_comment']; ?></td>
        <td><?php echo $item['Item']['response_to_confirm_comment']; ?></td>
        <td><?php echo $item['Item']['created']; ?></td>
        <td><?php echo $item['Item']['modified']; ?></td>
        <td><?php echo $this->Html->link('編集', array('action'=>'edit', $item['Item']['id'])); ?></td>
        <td><?php echo $this->Form->postLink('削除', array('action' => 'delete', $item['Item']['id'])); ?></td>
    </tr>
    <?php endforeach; ?>
    <tr>
        <?php echo $this->Form->create('Item', array('url' => 'add'));?>
        <td></td>
        <td><?php echo $this->Form->input('category', array('label' => false));?></td>
        <td><?php echo $this->Form->input('division', array('label' => false));?></td>
        <td><?php echo $this->Form->input('content', array('label' => false));?></td>
        <td><?php echo $this->Form->input('chatwork_url', array('label' => false));?></td>
        <td><?php echo $this->Form->input('github_url', array('label' => false));?></td>
        <td><?php echo $this->Form->input('confirm_priority', array('label' => false));?></td>
        <td><?php echo $this->Form->input('pullrequest', array('label' => false));?></td>
        <td><?php echo $this->Form->input('pullrequest_update', array('label' => false));?></td>
        <td><?php echo $this->Form->input('status', array('label' => false));?></td>
        <td><?php echo $this->Form->input('tech_release_judgement', array('label' => false));?></td>
        <td><?php echo $this->Form->input('supp_release_judgement', array('label' => false));?></td>
        <td><?php echo $this->Form->input('sale_release_judgement', array('label' => false));?></td>
        <td><?php echo $this->Form->input('scheduled_release_date', array('label' => false));?></td>
        <td><?php echo $this->Form->input('grace_days_of_verification_complete', array('label' => false));?></td>
        <td><?php echo $this->Form->input('merge_finish_date_to_master', array('label' => false));?></td>
        <td><?php echo $this->Form->input('confirm_priority', array('label' => false));?></td>
        <td><?php echo $this->Form->input('confirm_comment', array('label' => false));?></td>
        <td><?php echo $this->Form->input('response_to_confirm_comment', array('label' => false));?></td>
        <td><?php echo $this->Form->end('送信');?></td>
    </tr>
    <?php unset($item); ?>
</table>
