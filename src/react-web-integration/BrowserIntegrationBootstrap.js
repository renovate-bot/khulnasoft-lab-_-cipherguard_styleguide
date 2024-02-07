/**
 * Cipherguard ~ Open source password manager for teams
 * Copyright (c) 2021 KhulnaSoft Ltd (https://www.cipherguard.khulnasoft.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) 2021 KhulnaSoft Ltd (https://www.cipherguard.khulnasoft.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.cipherguard.khulnasoft.com Cipherguard(tm)
 * @since         3.3.0
 */

import {QuickAccessEvent} from "./Events/Quickaccess/QuickAccessEvent";
import {AuthLogin} from "./AuthLogin/AuthLogin";
import InFormManager from "./lib/InForm/InFormManager";
import SiteSettings from "../shared/lib/Settings/SiteSettings";

/**
 * Bootstrap the browser integration with browsed pages.
 */
async function init() {
  AuthLogin.legacyAuthLogin();

  QuickAccessEvent.fillForm();

  const siteSettings = await getSiteSettings();
  if (siteSettings?.canIUse('inFormIntegration')) {
    InFormManager.initialize();
  }
}

/**
 * Get the site settings.
 * @returns {Promise<SiteSettings>}
 */
async function getSiteSettings() {
  try {
    const siteSettingsDto = await port.request('cipherguard.organization-settings.get', false);
    return new SiteSettings(siteSettingsDto);
  } catch (error) {
    console.error(error);
  }
}

export const BrowserIntegrationBootstrap = {init};
