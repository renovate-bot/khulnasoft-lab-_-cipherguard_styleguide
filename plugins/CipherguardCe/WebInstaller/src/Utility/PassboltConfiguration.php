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
 * @since         2.5.0
 */
namespace Cipherguard\WebInstaller\Utility;

use Cake\View\ViewVarsTrait;

class CipherguardConfiguration
{
    use ViewVarsTrait;

    /**
     * Render the cipherguard configuration file.
     *
     * @param array $settings The webinstaller settings.
     * @return string
     */
    public function render(array $settings): string
    {
        $this->viewBuilder();
        $settings = $this->sanitize($settings);
        $this->set(['config' => $settings]);
        $configView = $this->createView()
            ->setPlugin('Cipherguard/WebInstaller')
            ->setTemplate('Config/cipherguard')
            ->setLayout('ajax');
        $contents = $configView->render();

        return "<?php\n$contents";
    }

    /**
     * Sanitize all entries of a settings array.
     * Sanitize = we escape the characters ' and \
     *
     * @param mixed $settings An array of settings or a scalar to sanitize.
     * @return mixed
     */
    protected function sanitize($settings)
    {
        if (is_scalar($settings) || is_null($settings)) {
            if (is_string($settings)) {
                return addslashes($settings);
            }

            return $settings;
        }

        foreach ($settings as $key => $entry) {
            $settings[$key] = $this->sanitize($entry);
        }

        return $settings;
    }
}
