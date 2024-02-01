<?php

use Cake\Core\Configure;

$importPluginEnabled = Configure::read('cipherguard.plugins.import.enabled');
if (!isset($importPluginEnabled) || $importPluginEnabled === true) {
    Configure::load('Cipherguard/Import.config', 'default', true);
}
