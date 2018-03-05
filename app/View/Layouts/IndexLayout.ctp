<!DOCTYPE html>
<html lang="ja">
<head>
    <?= $this->Html->charset() ?>
    <title>
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->css('index.css') ?>
    <?= $this->fetch('script'); ?>
    <script>
        var WEBROOT = '<?=$this->webroot?>';
    </script>
</head>
<body>
    <section class="container">
        <?= $this->fetch('content') ?>
    </section>
</body>
</html>
