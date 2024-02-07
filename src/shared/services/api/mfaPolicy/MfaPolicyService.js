/**
 * Cipherguard ~ Open source password manager for teams
 * Copyright (c) 2022 KhulnaSoft Ltd (https://www.cipherguard.khulnasoft.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) 2022 KhulnaSoft Ltd (https://www.cipherguard.khulnasoft.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.cipherguard.khulnasoft.com Cipherguard(tm)
 * @since         3.10.0
 */

import {ApiClient} from "../../../lib/apiClient/apiClient";

const MFA_RESOURCE_NAME = "mfa-policies/settings";

/**
 * Model related to the mfa policy service settings
 */
class MfaPolicyService {
  /**
   * Constructor
   *
   * @param {ApiClientOptions} apiClientOptions
   * @public
   */
  constructor(apiClientOptions) {
    apiClientOptions.setResourceName(MFA_RESOURCE_NAME);
    this.apiClient = new ApiClient(apiClientOptions);
  }

  /**
   * Find the MFA policy setting using Cipherguard API
   *
   * @return {Promise<Array<MFADto>>|null>}
   */
  async find() {
    return (await this.apiClient.findAll()).body;
  }

  /**
   * save a the mfa policy settings using Cipherguard API
   * @param  {MfaPolicy} dto
   */
  async save(dto) {
    await this.apiClient.create(dto);
  }
}

export default MfaPolicyService;

