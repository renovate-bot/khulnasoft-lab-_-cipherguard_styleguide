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
namespace App\Controller\Errors;

use App\Controller\AppController;

class ErrorNotFoundController extends AppController
{
    /**
     * Error not found
     * Feature is not supported
     *
     * @return void
     */
    public function notSupported()
    {
        // Use AppController:error instead of exception to avoid logging the error
        $this->error(__('This feature is not supported by your version of cipherguard.'), null, 404);
    }
}
