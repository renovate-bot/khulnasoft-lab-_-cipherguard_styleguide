<?php
declare(strict_types=1);

use Migrations\AbstractMigration;
use Cipherguard\Log\Service\ActionLogs\ActionLogsDeleteService;

class V360RemoveAuthLoginLoginGetActionFromLogs extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $service = new ActionLogsDeleteService();
        $service->delete($service::AUTH_LOGIN_LOGIN_GET);
    }
}
