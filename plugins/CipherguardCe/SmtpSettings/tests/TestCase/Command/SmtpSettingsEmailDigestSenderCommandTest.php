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
 * @since         3.11.0
 */
namespace Cipherguard\SmtpSettings\Test\TestCase\Command;

use App\Mailer\Transport\SmtpTransport;
use App\Test\Lib\Utility\EmailTestTrait;
use App\Utility\Application\FeaturePluginAwareTrait;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Cake\Mailer\Mailer;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Cipherguard\EmailDigest\Test\Factory\EmailQueueFactory;
use Cipherguard\EmailNotificationSettings\Utility\EmailNotificationSettings;
use Cipherguard\SmtpSettings\Test\Lib\SmtpSettingsTestTrait;

/**
 * @uses \Cipherguard\EmailDigest\Command\SenderCommand
 */
class SmtpSettingsEmailDigestSenderCommandTest extends TestCase
{
    use ConsoleIntegrationTestTrait;
    use EmailTestTrait;
    use FeaturePluginAwareTrait;
    use SmtpSettingsTestTrait;
    use TruncateDirtyTables;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->useCommandRunner();
        EmailNotificationSettings::flushCache();
        EventManager::instance()->setEventList(new EventList());
        $this->clearPlugins();
    }

    /**
     * Basic Sender test on DB settings
     */
    public function testSmtpSettingsEmailDigestSenderCommand_Success_Path_On_DB_Settings()
    {
        $this->enableFeaturePlugin('SmtpSettings');
        $senderEmail = 'phpunit@cipherguard.khulnasoft.com';
        $senderName = 'phpunit';
        $data = $this->getSmtpSettingsData();
        $data['sender_email'] = $senderEmail;
        $data['sender_name'] = $senderName;
        $sender = [$senderEmail => $senderName];
        $this->encryptAndPersistSmtpSettings($data);

        $nMails = 2;
        EmailQueueFactory::make($nMails)->persist();
        $mails = EmailQueueFactory::find()->orderAsc('created');

        $this->exec('cipherguard email_digest send');
        $this->assertExitSuccess();

        $this->assertEventFired(SmtpTransport::SMTP_TRANSPORT_INITIALIZE_EVENT);
        $this->assertEventFired(SmtpTransport::SMTP_TRANSPORT_BEFORE_SEND_EVENT);

        $this->assertMailCount($nMails);
        foreach ($mails as $i => $mail) {
            $this->assertMailSentFromAt($i, $sender);
            $this->assertMailSentToAt($i, [$mail->get('email') => $mail->get('email')]);
            $this->assertMailContainsAt($i, 'Sending email to: ' . $mail->get('email'));
        }
    }

    /**
     * Basic Sender test on DB settings
     */
    public function testSmtpSettingsEmailDigestSenderCommand_With_DB_Settings_Plugin_Unload_Should_Rely_On_File()
    {
        $SettingsInDB = $this->getSmtpSettingsData();
        $SettingsInDB['sender_email'] = 'phpunit@cipherguard.khulnasoft.com';
        $SettingsInDB['sender_name'] = 'phpunit';
        $this->encryptAndPersistSmtpSettings($SettingsInDB);

        $sender = Mailer::getConfig('default')['from'];

        $nMails = 2;
        EmailQueueFactory::make($nMails)->persist();
        $mails = EmailQueueFactory::find()->orderAsc('created');

        $this->disableFeaturePlugin('SmtpSettings');
        $this->exec('cipherguard email_digest send');
        $this->assertExitSuccess();

        $this->assertEventFired(SmtpTransport::SMTP_TRANSPORT_INITIALIZE_EVENT);
        $this->assertEventFired(SmtpTransport::SMTP_TRANSPORT_BEFORE_SEND_EVENT);

        $this->assertMailCount($nMails);
        foreach ($mails as $i => $mail) {
            $this->assertMailSentFromAt($i, $sender);
            $this->assertMailSentToAt($i, [$mail->get('email') => $mail->get('email')]);
            $this->assertMailContainsAt($i, 'Sending email to: ' . $mail->get('email'));
        }
        $this->enableFeaturePlugin('SmtpSettings');
    }
}
