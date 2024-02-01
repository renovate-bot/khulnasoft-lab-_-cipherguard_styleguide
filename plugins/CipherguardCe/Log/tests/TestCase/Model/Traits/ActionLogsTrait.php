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
 */
namespace Cipherguard\Log\Test\TestCase\Model\Traits;

trait ActionLogsTrait
{
    public function assertActionLogExists($conditions)
    {
        $actionLog = $this->ActionLogs
            ->find()
            ->where($conditions)
            ->first();

        $this->assertNotEmpty($actionLog, 'No corresponding actionLog could be found');

        return $actionLog;
    }

    public function assertActionLogsCount($count, ?array $conditions = [])
    {
        $actionLogCount = $this->ActionLogs
            ->find()
            ->count();

        $this->assertEquals($actionLogCount, $count);
    }

    public function assertOneActionLog(?array $conditions = [])
    {
        return $this->assertActionLogsCount(1);
    }

    public function assertActionLogIdMatchesResponse($id, $response)
    {
        $this->assertEquals($id, $response->id, 'ActionLogId doesn\'t match response id');
    }

    public function assertActionLogsEmpty()
    {
        $this->assertActionLogsCount(0);
    }
}
