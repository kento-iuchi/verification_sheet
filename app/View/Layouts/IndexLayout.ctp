<!DOCTYPE html>
<html lang="ja">
<head>
    <?= $this->Html->charset() ?>
    <title>
        <?= $this->fetch('title') ?>
    </title>
    <?php
        echo $this->Html->css(array(
                'jquery-ui.css',
            ));
        echo $this->Html->script(array(
                'jquery-3.2.1.min.js',
                'jquery-migrate-3.0.1.js',
                'jquery-ui.js',
            ), array( 'inline' => false ));
        echo $this->Html->script('js.cookie.js', array('inline' => false));
        echo $this->Html->script('verification_sheet.js', array('inline' => false));
    ?>
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
