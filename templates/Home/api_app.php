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
 */
use Cake\Core\Configure;

$this->assign('title', $title);
$version = Configure::read('cipherguard.version');

// See. fetch('scriptBottom')
$this->start('scriptBottom');
// Load the javascript application.
echo $this->Html->script('/js/app/api-vendors.js?v=' . Configure::read('cipherguard.version'), ['fullBase' => true, 'cache-version' => Configure::read('cipherguard.version')]);
echo $this->Html->script('/js/app/api-app.js?v=' . Configure::read('cipherguard.version'), ['fullBase' => true, 'cache-version' => Configure::read('cipherguard.version')]);
$this->end();
echo $this->element('Loader/skeleton');

$this->start('scriptTop');

echo $this->Html->script('/js/app/stylesheet.js?v=' . $version, [
    'id' => 'stylesheet-manager',
    'fullBase' => true,
    'data-file' => 'api_main.min.css',
    'data-theme' => isset($theme) ? $theme : null,
    'cache-version' => $version]);

$this->end();
?>
