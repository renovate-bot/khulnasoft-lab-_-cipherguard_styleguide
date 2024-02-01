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
 * @since         3.8.0
 */
namespace Cipherguard\SmtpSettings\Service;

use App\Error\Exception\FormValidationException;
use Cake\Event\EventDispatcherTrait;
use Cake\Event\EventManager;
use Cipherguard\SmtpSettings\Event\SmtpTransportSendTestEmailEventListener;
use Cipherguard\SmtpSettings\Form\EmailConfigurationForm;

class SmtpSettingsTestEmailService
{
    use EventDispatcherTrait;

    /**
     * @var array
     */
    private $smtpSettings = [];

    /**
     * @var \Cipherguard\SmtpSettings\Service\SmtpSettingsSendTestMailerService
     */
    private $mailerService;

    /**
     * @param \Cipherguard\SmtpSettings\Service\SmtpSettingsSendTestMailerService $mailerService service.
     */
    public function __construct(SmtpSettingsSendTestMailerService $mailerService)
    {
        $this->mailerService = $mailerService;
    }

    /**
     * @param array $smtpSettings SMTP Settings passed in the payload
     * @return void
     * @throws \App\Error\Exception\FormValidationException if the settings passed do not validate the EmailConfigurationForm
     */
    public function sendTestEmail(array $smtpSettings): void
    {
        $this->smtpSettings = $this->validateAndGetSmtpSettings($smtpSettings);

        // Do not assign the sender as found in the DB settings
        // as we use the one provided in the $smtpSettings
        EventManager::instance()->on(new SmtpTransportSendTestEmailEventListener());

        $this->mailerService->sendEmail($this->smtpSettings);
    }

    /**
     * @return array
     */
    public function getTrace(): array
    {
        $trace = $this->mailerService->getTrace();

        return $this->sanitizeTrace($trace);
    }

    /**
     * Remove sensitive details from the trace.
     *
     * @param array $trace SMTP trace.
     * @return array
     */
    private function sanitizeTrace(array $trace): array
    {
        foreach ($trace as &$entry) {
            if (isset($entry['cmd'])) {
                $entry['cmd'] = $this->removeCredentials($entry['cmd']);
            }
            if (!empty($entry['response'])) {
                foreach ($entry['response'] as &$response) {
                    $response['message'] = $this->removeCredentials($response['message']);
                }
            }
        }

        return $trace;
    }

    /**
     * @param string $str string where to remove the credentials
     * @return array|string|string[]
     */
    protected function removeCredentials(string $str)
    {
        $toReplace = [];
        $replaceMask = '*****';
        $replaceWith = [];

        if (isset($this->smtpSettings['username'])) {
            $usernameEncoded = base64_encode($this->smtpSettings['username']);
            $usernameClear = $this->smtpSettings['username'];
            $toReplace[] = $usernameClear;
            $replaceWith[] = $replaceMask;
            $toReplace[] = $usernameEncoded;
            $replaceWith[] = $replaceMask;
        }
        if (isset($this->smtpSettings['password'])) {
            $passwordEncoded = base64_encode($this->smtpSettings['password']);
            $passwordClear = $this->smtpSettings['password'];
            $toReplace[] = $passwordEncoded;
            $replaceWith[] = $replaceMask;
            $toReplace[] = $passwordClear;
            $replaceWith[] = $replaceMask;
        }
        if (isset($this->smtpSettings['username']) && isset($this->smtpSettings['password'])) {
            $encodedCreds = base64_encode(
                chr(0) . $this->smtpSettings['username'] . chr(0) . $this->smtpSettings['password']
            );
            $toReplace[] = $encodedCreds;
            $replaceWith[] = $replaceMask;
        }

        return str_replace($toReplace, $replaceWith, $str);
    }

    /**
     * @param array $data Data in the payload
     * @return array
     * @throws \App\Error\Exception\FormValidationException if the data passed do not validate the EmailConfigurationForm
     */
    public function validateAndGetSmtpSettings(array $data): array
    {
        $form = new EmailConfigurationForm();

        if (!$form->execute($data, ['validate' => 'sendTestEmail'])) {
            throw new FormValidationException(__('Could not validate the smtp settings.'), $form);
        }

        return (array)$form->getData();
    }
}
