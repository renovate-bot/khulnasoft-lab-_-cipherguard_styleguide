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
namespace Cipherguard\WebInstaller\Controller;

use Cake\Controller\Controller;

class GettingStartedController extends Controller
{
    /**
     * Getting started with cipherguard.
     * Is displayed instead of any other page if cipherguard is not configured.
     *
     * @return void
     */
    public function index()
    {
        $this->render('Pages/getting_started');
    }
}
