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
        <th>Created</th>
    </tr>

    <!-- ここから、$posts配列をループして、投稿記事の情報を表示 -->

    <?php foreach ($items as $item): ?>
    <tr>
        <td><?php echo $item['Item']['id']; ?></td>
        <td>
            <?php echo $this->Html->link($item['Item']['category'],
array('controller' => 'items', 'action' => 'view', $item['Item']['category'])); ?>
        </td>
        <td><?php echo $item['Item']['created']; ?></td>
    </tr>
    <?php endforeach; ?>
    <?php unset($item); ?>
</table>
