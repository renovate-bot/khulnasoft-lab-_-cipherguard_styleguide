/**
 * Cipherguard ~ Open source password manager for teams
 * Copyright (c) KhulnaSoft Ltd (https://www.cipherguard.khulnasoft.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) KhulnaSoft Ltd (https://www.cipherguard.khulnasoft.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.cipherguard.khulnasoft.com Cipherguard(tm)
 * @since         4.1.0
 */
// import i18n from "../sdk/i18n";

class CipherguardServiceUnavailableError extends Error {
  constructor(message) {
    // message = message || i18n.t('The service is unavailable');
    message = message || 'The service is unavailable';
    super(message);
    this.name = 'CipherguardServiceUnavailableError';
  }
}

export default CipherguardServiceUnavailableError;
