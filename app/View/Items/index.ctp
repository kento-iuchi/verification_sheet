<!-- File: /app/View/Posts/index.ctp -->

<h1>Blog posts</h1>
<?php echo $this->Html->link(
    '追加する',
    array('controller' => 'items', 'action' => 'add')
); ?>
<table>
    <tr>
        <th>番号</th>
        <th>カテゴリ</th>
        <th>区分</th>
        <th>内容</th>
        <th>chatwork URL</th>
        <th>github URL</th>
        <th>確認優先度<br>必須リリース日</th>
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
    </tr>

    <!-- ここから、$posts配列をループして、投稿記事の情報を表示 -->

    <?php foreach ($items as $item): ?>
    <tr>
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
        <td><?php echo $item['Item']['sale_release_judgement']; ?></td>
        <td><?php echo $item['Item']['scheduled_release_date']; ?></td>
        <td><?php echo $item['Item']['grace_days_of_verification_complete']; ?></td>
        <td><?php echo $item['Item']['merge_finish_date_to_master']; ?></td>
        <td><?php echo $item['Item']['confirm_points']; ?></td>
        <td><?php echo $item['Item']['confirm_comment']; ?></td>
        <td><?php echo $item['Item']['response_to_confirm_comment']; ?></td>
        <td><?php echo $item['Item']['created']; ?></td>
        <td><?php echo $item['Item']['modified']; ?></td>
    </tr>
    <?php endforeach; ?>
    <?php unset($item); ?>
</table>
