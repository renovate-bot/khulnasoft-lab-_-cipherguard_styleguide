<?php
/**
 * Cipherguard ~ Open source password manager for teams
 * Copyright (c) Khulnasoft Ltd' (https://www.cipherguard.khulnasoft.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Khulnasoft Ltd' (https://www.cipherguard.khulnasoft.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.cipherguard.khulnasoft.com Cipherguard(tm)
 * @since         2.4.0
 * @var \App\View\AppView $this
 */
use Cake\Core\Configure;

$version = Configure::read('cipherguard.version');
?>
<!DOCTYPE html>
<html class="cipherguard" lang="en">
<head>
    <?= $this->Html->charset() ?>

    <title><?= Configure::read('cipherguard.meta.title'); ?> | <?= $this->fetch('title') ?></title>
    <?= $this->element('Header/meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->Html->script('/js/app/stylesheet.js?v=' . $version, [
        'id' => 'stylesheet-manager',
        'fullBase' => true,
        'data-file' => 'api_authentication.min.css',
        'data-theme' => isset($theme) ? $theme : null,
        'cache-version' => $version]);
    ?>
    <?= $this->fetch('js') ?>

</head>
<body spellcheck="false">
<div id="container" class="container page <?= $this->fetch('pageClass') ?>">
    <div class="content">
        <div class="header">
            <div class="logo"><span class="visually-hidden">Cipherguard</span></div>
        </div>
        <?= $this->fetch('content') ?>
    </div>
</div>
</body>
</html>
