<?php
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
 * @since         3.0.0
 */
// @codingStandardsIgnoreStart
use Migrations\AbstractMigration;

class V300AddResourceTypeIdField extends AbstractMigration
{
    /**
     * Up
     *
     * @return void
     */
    public function up()
    {
        $this->table('resources')
            ->addColumn('resource_type_id', 'char', [
                'default' => null,
                'limit' => 36,
                'null' => true,
            ])
            ->addIndex(['resource_type_id'])
            ->save();
    }
}
// @codingStandardsIgnoreEnd
