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
 * @since         2.0.0
 */
use Cake\Core\Configure;
?>
<!DOCTYPE html>
<html class="cipherguard no-js no-cipherguardplugin version" lang="en">
<head>
    <?= $this->Html->charset() ?>

    <title><?= Configure::read('cipherguard.meta.title'); ?> | <?= $this->fetch('title') ?></title>
    <?= $this->element('Header/meta') ?>
    <?= $this->Html->css('themes/default/api_main.min.css?v=' . Configure::read('cipherguard.version'), ['block' => 'css', 'fullBase' => true]); ?>
<?= $this->fetch('css') ?>
</head>
<body spellcheck="false">
<div id="container" class="error page <?= $this->fetch('pageClass') ?>">
<?= $this->element('Navigation/default'); ?>
<div id="content">
<?= $this->Flash->render() ?>
<?= $this->fetch('content') ?>
</div>
<?= $this->element('Footer/default'); ?>
</div>
</body>
</html>
