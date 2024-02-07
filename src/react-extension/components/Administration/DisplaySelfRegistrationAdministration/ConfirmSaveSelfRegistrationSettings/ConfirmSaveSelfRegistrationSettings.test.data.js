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
 * @since         3.8.3
 */

import {defaultAppContext} from "../../../../contexts/ApiAppContext.test.data";

/**
 * Default props.
 * @returns {object}
 * @param data
 */
export function defaultProps(data = {}) {
  const defaultProps = {
    context: defaultAppContext(data?.context),
    administrationWorkspaceContext: {
      setDisplayAdministrationWorkspaceAction: jest.fn(),
      resetDisplayAdministrationWorkspaceAction: jest.fn(),
    },
    onClose: jest.fn(),
    onSubmit: jest.fn(),
    t: jest.fn()
  };
  return Object.assign(defaultProps, data);
}


/**
 * Has changed policy.
 * @param {Object} props The props to override
 * @returns {object}
 */
export function propsWithMockDomains(props = {}) {
  const _props = {
    adminSelfRegistrationContext: {
      isProcessing: jest.fn(),
      getAllowedDomains: () =>  (mockDomains())
    }
  };
  return defaultProps(Object.assign(_props, props));
}

/**
 * Has changed policy.
 * @param {Object} props The props to override
 * @returns {object}
 */
export function mockDomains() {
  const map = new Map();
  map.set("uuid", "cipherguard.khulnasoft.com");
  map.set("uuid2", "cipherguard.lu");
  return map;
}

