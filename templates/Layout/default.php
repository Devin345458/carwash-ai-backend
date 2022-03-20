<?php

use App\Model\Entity\Myuser;
use Cake\Core\Configure; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../../../webroot/img/favicon.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>
        Car Wash AI
    </title>
        <?= $this->Html->css([
            "https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons",
            'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/fontawesome.min.css',
        ]);
    ?>

    <?= $this->Mix->htmlcss('app'); ?>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
</head>
<body>
    <noscript>
        <strong>We're sorry but Momentum Car Wash Specialists doesn't work properly without JavaScript enabled. Please enable it to continue.</strong>
    </noscript>

    <!-- Site wrapper -->
    <div id="app"></div>


    <?= $this->Html->script([
        'https://js.stripe.com/v3/',
        'https://cdn.onesignal.com/sdks/OneSignalSDK.js',
    ]); ?>
    <script>
        const apikey = '<?= env('PUSHER_APPKEY') ?>';
        var OneSignal = window.OneSignal || [];
        OneSignal.push(function() {
            OneSignal.init({
                appId: '<?= env('ONE_SIGNAL') ?>',
                allowLocalhostAsSecureOrigin: true
            });
        });
    </script>

    <?= $this->Mix->htmlScript('chunk-common'); ?>
    <?= $this->Mix->htmlScript('vendors'); ?>
    <?= $this->Mix->htmlScript('main'); ?>





</body>
</html>
