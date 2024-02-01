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
 * @since         2.13.0
 * @var \App\View\AppView $this
 * @var mixed $report
 */
use Cake\Core\Configure;
?>
<!doctype html>
<html class="cipherguard no-js version launching no-cipherguardplugin" lang="en">
<head>
    <?= $this->Html->charset() ?>
    <title><?= Configure::read('cipherguard.meta.title'); ?> | <?= $this->fetch('title') ?></title>
    <?= $this->element('Header/meta') ?>
    <?= $this->Html->css('themes/default/api_reports.min.css?v=' . Configure::read('cipherguard.version')); ?>
</head>
<body spellcheck="false" class="report report-html">
    <div id="container" class="report report-html <?php echo $this->fetch('page_classes') ?>">
        <div class="grid">
    <?php echo $this->element('Common/reportHeader', [ 'report' => $report]); ?>
            <div class="report-content">
    <?php echo $this->element('Common/reportDescription', [ 'report' => $report]); ?>
    <?php echo $this->fetch('content'); ?>
            </div>
        </div>
    </div>
    <?php echo $this->fetch('scriptBottom'); ?>
</body>
</html>
