<?php
declare(strict_types=1);

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
 */

namespace Cipherguard\EmailNotificationSettings\Utility\NotificationSettingsSource;

use Cake\Form\Form as CakeForm;
use Cake\Form\Schema;
use Cake\Utility\Hash;
use Cipherguard\EmailNotificationSettings\Utility\EmailNotificationSettings;
use Cipherguard\EmailNotificationSettings\Utility\EmailNotificationSettingsDefinitionInterface;

class DefaultEmailNotificationSettingsSource implements ReadableEmailNotificationSettingsSourceInterface
{
    /**
     * @var \Cake\Form\Schema
     */
    private $schema;

    /**
     * @param \Cake\Form\Schema $schema Schema to use to build notification settings
     */
    final public function __construct(Schema $schema)
    {
        $this->schema = $schema;
    }

    /**
     * Return a new instance of DefaultEmailNotificationSettingsSource from a Cake Form instance
     *
     * @param \Cake\Form\Form $form An instance of Cake form
     * @return \Cipherguard\EmailNotificationSettings\Utility\NotificationSettingsSource\DefaultEmailNotificationSettingsSource
     */
    public static function fromCakeForm(CakeForm $form)
    {
        return new static($form->getSchema());
    }

    /**
     * Return a new instance of DefaultEmailNotificationSettingsSource from a EmailNotificationSettingsDefinitionInterface instance
     *
     * @param \Cipherguard\EmailNotificationSettings\Utility\EmailNotificationSettingsDefinitionInterface $formDefinition Form definition
     * @return \Cipherguard\EmailNotificationSettings\Utility\NotificationSettingsSource\DefaultEmailNotificationSettingsSource
     */
    public static function fromSettingsFormDefinition(EmailNotificationSettingsDefinitionInterface $formDefinition)
    {
        $schema = new Schema();
        $formDefinition->buildSchema($schema);

        return new static($schema);
    }

    /**
     * @return array
     */
    public function read()
    {
        $defaultSettings = [];
        $fieldsList = $this->schema->fields();

        /**
         * A Field is an array formatted as follow:
         * [
         *  type' => null,
         * 'length' => null,
         * 'precision' => null,
         * 'default' => null,
         * ]
         */
        foreach ($fieldsList as $fieldName) {
            $field = $this->schema->field($fieldName);
            $defaultSettings[EmailNotificationSettings::underscoreToDottedFormat($fieldName)] = $field['default'];
        }

        return Hash::expand($defaultSettings);
    }
}
