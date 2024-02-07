/**
 * Cipherguard ~ Open source password manager for teams
 * Copyright (c) 2023 KhulnaSoft Ltd (https://www.cipherguard.khulnasoft.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) 2023 KhulnaSoft Ltd (https://www.cipherguard.khulnasoft.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.cipherguard.khulnasoft.com Cipherguard(tm)
 * @since         3.12.0
 */
import {ApiClientOptions} from "../../lib/apiClient/apiClientOptions";

export function defaultAppContext(context) {
  const baseUrl = 'http://localhost:6006';
  const apiClientOptions = new ApiClientOptions()
    .setBaseUrl(baseUrl);

  const defaultAppContext = {
    getApiClientOptions: () => apiClientOptions,
    trustedDomain: `${baseUrl}/subfolder`,
  };
  return Object.assign(defaultAppContext, context || {});
}
