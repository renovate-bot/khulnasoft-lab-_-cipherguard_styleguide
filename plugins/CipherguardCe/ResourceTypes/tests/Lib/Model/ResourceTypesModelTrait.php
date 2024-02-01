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
namespace Cipherguard\ResourceTypes\Test\Lib\Model;

trait ResourceTypesModelTrait
{
    /**
     * Asserts that an object has all the attributes a role should have.
     *
     * @param object $roles
     */
    protected function assertResourceTypeAttributes($roles)
    {
        $attributes = ['id', 'name', 'slug', 'description', 'definition'];
        $this->assertObjectHasAttributes($attributes, $roles);
    }
}
