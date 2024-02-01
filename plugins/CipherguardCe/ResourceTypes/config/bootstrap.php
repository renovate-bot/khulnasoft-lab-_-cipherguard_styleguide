<?php

use Cake\Core\Configure;

$pluginEnabled = Configure::read('cipherguard.plugins.resourceTypes.enabled');
if (!isset($pluginEnabled) || $pluginEnabled === true) {
    Configure::load('Cipherguard/ResourceTypes.config', 'default', true);
}
