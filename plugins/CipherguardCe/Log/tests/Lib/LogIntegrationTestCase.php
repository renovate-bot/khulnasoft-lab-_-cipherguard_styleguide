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
 * @since         2.8.0
 */
namespace Cipherguard\Log\Test\Lib;

use App\Model\Entity\User;
use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UserAction;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cipherguard\JwtAuthentication\Test\Utility\JwtAuthTestTrait;
use Cipherguard\Log\Test\Lib\Traits\ActionLogsTestTrait;
use Cipherguard\Log\Test\Lib\Traits\EntitiesHistoryTestTrait;

abstract class LogIntegrationTestCase extends AppIntegrationTestCase
{
    use ActionLogsTestTrait;
    use EntitiesHistoryTestTrait;
    use JwtAuthTestTrait;

    public const JWT_LOGIN = 'jwt_login';
    public const SESSION_LOGIN = 'session_login';

    /**
     * @var \App\Model\Table\ResourcesTable
     */
    protected $Resources;

    /**
     * @var \App\Model\Table\PermissionsTable
     */
    protected $Permissions;

    /**
     * @var \App\Model\Table\SecretsTable
     */
    protected $Secrets;

    /**
     * @var \Cipherguard\Log\Model\Table\SecretAccessesTable
     */
    protected $SecretAccesses;

    /**
     * @var \Cipherguard\Log\Model\Table\EntitiesHistoryTable
     */
    protected $EntitiesHistory;

    /**
     * @var \Cipherguard\Log\Model\Table\ActionLogsTable
     */
    protected $ActionLogs;

    /**
     * @var \Cipherguard\Log\Model\Table\ActionsTable
     */
    protected $Actions;

    public function setUp(): void
    {
        parent::setUp();
        Configure::write('cipherguard.plugins.log.enabled', true);

        UserAction::destroy();
        TableRegistry::getTableLocator()->clear();

        $this->Resources = TableRegistry::getTableLocator()->get('Resources');
        $this->Permissions = TableRegistry::getTableLocator()->get('Permissions');
        $this->Secrets = TableRegistry::getTableLocator()->get('Secrets');
        $this->SecretAccesses = TableRegistry::getTableLocator()->get('Cipherguard/Log.SecretAccesses');
        $this->EntitiesHistory = TableRegistry::getTableLocator()->get('Cipherguard/Log.EntitiesHistory');
        $this->ActionLogs = TableRegistry::getTableLocator()->get('Cipherguard/Log.ActionLogs');
        $this->Actions = TableRegistry::getTableLocator()->get('Cipherguard/Log.Actions');

        // Make sure associations are loaded correctly, e.g. without depending on
        // ActionListeners -> model.Initialize, as the callback will not be fired twice
        // and controller actions can be called several times
        $this->Permissions->belongsTo('Cipherguard/Log.PermissionsHistory', [
            'foreignKey' => 'foreign_key',
        ]);
        $this->Resources->belongsTo('Cipherguard/Log.EntitiesHistory', [
            'foreignKey' => 'foreign_key',
        ]);
        $this->Secrets->belongsTo('Cipherguard/Log.SecretsHistory', [
            'foreignKey' => 'foreign_key',
        ]);
        $this->Secrets->hasMany('Cipherguard/Log.SecretAccesses');

        $this->SecretAccesses->belongsTo('Cipherguard/Log.EntitiesHistory', [
            'foreignKey' => 'foreign_key',
        ]);
        $this->enableFeaturePlugin('JwtAuthentication');

        $this->Actions->clearCache();
    }

    public function tearDown(): void
    {
        // Remove dynamically added associations
        TableRegistry::getTableLocator()->clear();
        $this->disableFeaturePlugin('JwtAuthentication');
    }

    public function dataProviderForLoginType(): array
    {
        return [[self::SESSION_LOGIN], [self::JWT_LOGIN]];
    }

    /**
     * @param string $loginType Login Type (JWT or SESSION)
     * @param User $user User to log in
     */
    public function loginWithDataProviderLoginTypeValue(string $loginType, User $user)
    {
        if ($loginType === self::JWT_LOGIN) {
            $this->createJwtTokenAndSetInHeader($user->id);
        } else {
            $this->logInAs($user);
        }
    }
}
