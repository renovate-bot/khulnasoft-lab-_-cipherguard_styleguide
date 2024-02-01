<?php
/**
 * Cipherguard ~ Open source password manager for teams
 * Copyright (c) Khulnasoft Ltd' (https://www.cipherguard.khulnasoft.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Khulnasoft Ltd'RL (https://www.cipherguard.khulnasoft.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.cipherguard.khulnasoft.com Cipherguard(tm)
 * @since         3.0.0
 */
// @codingStandardsIgnoreStart
use Migrations\AbstractMigration;

class V300AddResourceTypesTable extends AbstractMigration
{
    /**
     * Up
     *
     * @return void
     */
    public function up()
    {
        $this->table('resource_types', ['id' => false, 'primary_key' => ['id'], 'collation' => 'utf8mb4_unicode_ci'])
            ->addColumn('id', 'char', [
             'default' => null,
             'limit' => 36,
             'null' => false,
            ])
            ->addColumn('slug', 'char', [
                'default' => null,
                'limit' => 64,
                'null' => false,
            ])
            ->addColumn('name', 'char', [
                'default' => null,
                'limit' => 64,
                'null' => false,
            ])
            ->addColumn('description', 'char', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('definition', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                'slug',
                ['unique' => true]
            )
             ->create();
    }
}
// @codingStandardsIgnoreEnd
