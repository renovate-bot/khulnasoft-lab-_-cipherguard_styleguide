<?php

use Cake\Core\Configure;

$exportPluginEnabled = Configure::read('cipherguard.plugins.export.enabled');
if (!isset($exportPluginEnabled) || $exportPluginEnabled === true) {
    Configure::load('Cipherguard/Export.config', 'default', true);
}
