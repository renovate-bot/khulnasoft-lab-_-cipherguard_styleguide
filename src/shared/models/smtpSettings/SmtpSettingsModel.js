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

import SmtpSettingsService from "../../services/api/smtpSettings/SmtpSettingsService";

/**
 * Model related to the SMTP settings
 */
class SmtpSettingsModel {
  /**
   * Constructor
   *
   * @param {ApiClientOptions} apiClientOptions
   * @public
   */
  constructor(apiClientOptions) {
    this.smtpSettingsService = new SmtpSettingsService(apiClientOptions);
  }

  /**
   * Find the SMTP settings using Cipherguard API
   * @return {Promise<SmtpSettingsDto|null>}
   */
  findSmtpSettings() {
    return this.smtpSettingsService.find();
  }

  /**
   * Save the SMTP settings using Cipherguard API
   * @param {SmtpSettingsDto} smtpSettings the settings to save
   * @return {Promise<SmtpSettingsDto|null>}
   */
  saveSmtpSettings(smtpSettings) {
    return this.smtpSettingsService.save(smtpSettings);
  }
}

export default SmtpSettingsModel;
