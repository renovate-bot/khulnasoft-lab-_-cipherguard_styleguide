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
 * @since         2.0.0
 */
namespace App\Controller\Roles;

use App\Controller\AppController;

/**
 * RolesIndexController Class
 */
class RolesIndexController extends AppController
{
    /**
     * @var \App\Model\Table\RolesTable
     */
    protected $Roles;

    /**
     * Roles Index action
     *
     * @return void
     */
    public function index()
    {
        $this->assertJson();

        $this->Roles = $this->fetchTable('Roles');
        $roles = $this->Roles->find('all');
        $this->success(__('The operation was successful.'), $roles);
    }
}
