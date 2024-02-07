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
 * Default app context for authentication recover context.
 * @param {Object} appContext The override
 * @return {Object}
 */
export function defaultAuthenticationRecoverAppContext(appContext) {
  const port = new MockPort();
  port.addRequestListener("cipherguard.recover.start", jest.fn(() => ({locale: "fr-FR"})));
  port.addRequestListener("cipherguard.recover.first-install", jest.fn(() => Promise.resolve(false)));
  port.addRequestListener("cipherguard.recover.import-key", jest.fn(() => Promise.resolve()));
  port.addRequestListener("cipherguard.recover.verify-passphrase", jest.fn(() => Promise.resolve()));
  port.addRequestListener("cipherguard.recover.set-security-token", jest.fn(() => Promise.resolve()));
  port.addRequestListener("cipherguard.recover.complete", jest.fn(() => Promise.resolve()));
  port.addRequestListener("cipherguard.recover.sign-in", jest.fn(() => Promise.resolve()));
  port.addRequestListener("cipherguard.recover.generate-account-recovery-request-key", jest.fn(() => Promise.resolve()));
  port.addRequestListener("cipherguard.recover.request-account-recovery", jest.fn(() => Promise.resolve()));
  port.addRequestListener("cipherguard.recover.request-help-credentials-lost", jest.fn(() => Promise.resolve()));
  port.addRequestListener("cipherguard.recover.has-user-enabled-account-recovery", jest.fn(() => Promise.resolve(true)));
  port.addRequestListener("cipherguard.recover.lost-passphrase-case", jest.fn(() => Promise.resolve(false)));
  port._port = {
    onDisconnect: {
      addListener: jest.fn()
    }
  };

  const defaultAuthenticationRecover = {
    port: port,
    onRefreshLocaleRequested: jest.fn(),
    initLocale: jest.fn(),
  };
  return Object.assign(defaultAppContext(defaultAuthenticationRecover), appContext || {});
}

/**
 * Default props.
 * @returns {object}
 */
export function defaultProps(props) {
  const defaultProps = {
    context: defaultAuthenticationRecoverAppContext(props?.context),
  };
  return Object.assign(defaultProps, props || {});
}

/**
 * Decorate - account recovery enabled
 * @param {Object} props The props to decorate
 * @returns {object}
 */
export function withAccountRecoveryEnabled(props) {
  props.context.port.addRequestListener("cipherguard.recover.has-user-enabled-account-recovery", () => Promise.resolve(true));
  return props;
}
