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
 * @since         4.2.0
 */
namespace Cipherguard\PasswordPolicies\Model\Dto;

use Cake\I18n\FrozenTime;

class PasswordPoliciesSettingsDto
{
    /**
     * The password generator password type.
     *
     * @var string
     */
    public const PASSWORD_GENERATOR_PASSWORD = 'password';

    /**
     * The password generator passphrase type.
     *
     * @var string
     */
    public const PASSWORD_GENERATOR_PASSPHRASE = 'passphrase';

    /**
     * The default password generator.
     *
     * @var string
     */
    public const DEFAULT_PASSWORD_GENERATOR = self::PASSWORD_GENERATOR_PASSWORD;

    /**
     * Source default.
     *
     * @var string
     */
    public const SOURCE_DEFAULT = 'default';

    /**
     * Source file.
     *
     * @var string
     */
    public const SOURCE_FILE = 'file';

    /**
     * Source env.
     *
     * @var string
     */
    public const SOURCE_ENV = 'env';

    /**
     * Source legacy file.
     *
     * @var string
     */
    public const SOURCE_LEGACY_FILE = 'legacyFile';

    /**
     * Source legacy env.
     *
     * @var string
     */
    public const SOURCE_LEGACY_ENV = 'legacyEnv';

    /**
     * Source db.
     *
     * @var string
     */
    public const SOURCE_DATABASE = 'db';

    /**
     * @var string|null
     */
    public $default_generator;

    /**
     * @var bool|null
     */
    public $external_dictionary_check;

    /**
     * @var \Cipherguard\PasswordPolicies\Model\Dto\PasswordGeneratorSettingsDto|null
     */
    public $password_generator_settings;

    /**
     * @var \Cipherguard\PasswordPolicies\Model\Dto\PassphraseGeneratorSettingsDto|null
     */
    public $passphrase_generator_settings;

    /**
     * @var string|null
     */
    public $id;

    /**
     * @var \Cake\I18n\FrozenTime|null
     */
    public $created;

    /**
     * @var string|null
     */
    public $created_by;

    /**
     * @var \Cake\I18n\FrozenTime|null
     */
    public $modified;

    /**
     * @var string|null
     */
    public $modified_by;

    /**
     * @var string|null
     */
    public $source;

    /**
     * @param string|null $defaultGenerator Default password generator type.
     * @param mixed $externalDictionaryCheck External services check flag.
     * @param array|null $passwordGeneratorSettings Settings used when a password is generated by password generator.
     * @param array|null $passphraseGeneratorSettings Settings used when a passphrase is generated by password generator.
     * @param string|null $id ID.
     * @param \Cake\I18n\FrozenTime|null $created Created time.
     * @param string|null $createdBy Modified by.
     * @param \Cake\I18n\FrozenTime|null $modified Modified time.
     * @param string|null $modifiedBy Modified by.
     * @param string|null $source Source of these settings(can be env, db, etc).
     */
    final public function __construct(
        ?string $defaultGenerator,
        $externalDictionaryCheck,
        ?array $passwordGeneratorSettings,
        ?array $passphraseGeneratorSettings,
        ?string $id,
        ?FrozenTime $created,
        ?string $createdBy,
        ?FrozenTime $modified,
        ?string $modifiedBy,
        ?string $source
    ) {
        $this->default_generator = $defaultGenerator;
        $this->external_dictionary_check = (bool)$externalDictionaryCheck;
        $this->password_generator_settings = PasswordGeneratorSettingsDto::createFromArray($passwordGeneratorSettings);
        $this->passphrase_generator_settings = PassphraseGeneratorSettingsDto::createFromArray($passphraseGeneratorSettings); // phpcs:ignore
        $this->source = $source ?? 'default';
        // DB fields
        $this->id = $id;
        $this->created = $created;
        $this->created_by = $createdBy;
        $this->modified = $modified;
        $this->modified_by = $modifiedBy;
    }

    /**
     * Returns object of itself from provided array.
     *
     * @param array $data Data.
     * @return static
     */
    public static function createFromArray(array $data)
    {
        return new static(
            $data['default_generator'] ?? null,
            $data['external_dictionary_check'] ?? null,
            $data['password_generator_settings'] ?? null,
            $data['passphrase_generator_settings'] ?? null,
            $data['id'] ?? null,
            $data['created'] ?? null,
            $data['created_by'] ?? null,
            $data['modified'] ?? null,
            $data['modified_by'] ?? null,
            $data['source'] ?? null
        );
    }

    /**
     * Create DTO from default.
     *
     * @param array $data The data that override the default
     * @return self
     */
    public static function createFromDefault(array $data = []): self
    {
        return self::createFromArray(array_merge([
            'default_generator' => self::DEFAULT_PASSWORD_GENERATOR,
            'source' => self::SOURCE_DEFAULT,
            'password_generator_settings' => PasswordGeneratorSettingsDto::createFromDefault()->toArray(),
            'passphrase_generator_settings' => PassphraseGeneratorSettingsDto::createFromDefault()->toArray(),
            'external_dictionary_check' => true,
        ], $data));
    }

    /**
     * Returns array representation of the object.
     *
     * @return array
     */
    public function toArray(): array
    {
        $data = [
            'default_generator' => $this->default_generator,
            'password_generator_settings' => $this->password_generator_settings->toArray(),
            'passphrase_generator_settings' => $this->passphrase_generator_settings->toArray(),
            'source' => $this->source,
            'external_dictionary_check' => $this->external_dictionary_check,
        ];

        if ($this->source === self::SOURCE_DATABASE) {
            $data += [
                'id' => $this->id,
                'created' => $this->created,
                'created_by' => $this->created_by,
                'modified' => $this->modified,
                'modified_by' => $this->modified_by,
            ];
        }

        return $data;
    }
}
