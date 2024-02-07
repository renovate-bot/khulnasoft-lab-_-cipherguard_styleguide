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
 * @since         2.9.0
 */

class CipherguardBadResponseError extends Error {
  constructor(error, response) {
    super('An internal error occurred. The server response could not be parsed. Please contact your administrator.');
    this.name = 'CipherguardBadResponseError';
    this.srcError = error;
    this.srcResponse = response;
  }
}

export default CipherguardBadResponseError;
