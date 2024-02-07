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
 * @since         3.6.0
 */

import {defaultAppContext} from "../ExtAppContext.test.data";
import MockPort from "../../test/mock/MockPort";

/**
 * Default app context for authentication setup context.
 * @param {Object} appContext The override
 * @return {Object}
 */
export function defaultAuthenticationSetupAppContext(appContext) {
  const port = new MockPort();
  port.addRequestListener("cipherguard.setup.start", jest.fn(() => ({locale: "fr-FR"})));
  port.addRequestListener("cipherguard.setup.is-first-install", jest.fn(() => Promise.resolve(false)));
  port.addRequestListener("cipherguard.setup.generate-key", jest.fn(() => Promise.resolve()));
  port.addRequestListener("cipherguard.setup.download-recovery-kit", jest.fn(() => Promise.resolve()));
  port.addRequestListener("cipherguard.setup.import-key", jest.fn(() => Promise.resolve()));
  port.addRequestListener("cipherguard.setup.verify-passphrase", jest.fn(() => Promise.resolve()));
  port.addRequestListener("cipherguard.setup.set-account-recovery-user-setting", jest.fn(() => Promise.resolve()));
  port.addRequestListener("cipherguard.setup.set-security-token", jest.fn(() => Promise.resolve()));
  port.addRequestListener("cipherguard.setup.complete", jest.fn(() => Promise.resolve()));
  port.addRequestListener("cipherguard.setup.sign-in", jest.fn(() => Promise.resolve()));
  port._port = {
    onDisconnect: {
      addListener: jest.fn()
    }
  };

  const defaultAuthenticationSetupAppContext = {
    port: port,
    onRefreshLocaleRequested: jest.fn(),
  };
  return Object.assign(defaultAppContext(defaultAuthenticationSetupAppContext), appContext || {});
}

/**
 * Default props.
 * @returns {object}
 */
export function defaultProps(props) {
  const defaultProps = {
    context: defaultAuthenticationSetupAppContext(props?.context),
  };
  return Object.assign(defaultProps, props || {});
}

/**
 * Decorate - account recovery enabled
 * @param {Object} props The props to decorate
 * @returns {object}
 */
export function withAccountRecoveryEnabled(props) {
  const accountRecoveryOrganizationPolicy = {
    policy: "opt-in"
  };
  props.context.port.addRequestListener("cipherguard.setup.get-account-recovery-organization-policy", () => Promise.resolve(accountRecoveryOrganizationPolicy));
  return props;
}
