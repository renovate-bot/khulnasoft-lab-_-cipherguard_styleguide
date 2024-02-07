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
 * @since         3.8.0
 */

import {ApiClient} from "../../../lib/apiClient/apiClient";

const MFA_RESOURCE_NAME = "mfa";

/**
 * Model related to the MFA service settings
 */
class MFAService {
  /**
   * Constructor
   *
   * @param {ApiClientOptions} apiClientOptions
   * @public
   */
  constructor(apiClientOptions) {
    this.apiClientOptions = apiClientOptions;
  }

  /**
   * Find the MFA setting using Cipherguard API
   *
   * @return {Promise<Array<MFADto>>|null>}
   */
  async findAllSettings() {
    this.initClient();
    return (await this.apiClient.findAll()).body;
  }

  /**
   * Save the MFA settings settings using Cipherguard API
   * @param {MFASettingDto} MFASetting
   * @returns {Promise<MFASettingDto>}
   */
  async save(MFASetting) {
    this.initClient();
    return (await this.apiClient.create(MFASetting)).body;
  }

  /**
   * retrieve settings from the user
   *
   * @returns {Promise<*>} Response body
   * @public
   */
  async getUserSettings() {
    this.initClient("setup/select");
    return (await this.apiClient.findAll()).body;
  }

  /**
   * Initializes the API client with the specified resource name.
   * @param {string} [path='settings'] - The resource name to use for the API client.
   * @returns {void}
   */
  initClient(path = "settings") {
    this.apiClientOptions.setResourceName(`${MFA_RESOURCE_NAME}/${path}`);
    this.apiClient = new ApiClient(this.apiClientOptions);
  }
}

export default MFAService;

