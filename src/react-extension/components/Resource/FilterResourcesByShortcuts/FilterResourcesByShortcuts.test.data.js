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
 * @since         2.13.0
 */

import {defaultUserAppContext} from "../../../contexts/ExtAppContext.test.data";
import {defaultPasswordExpirySettingsContext} from "../../../contexts/PasswordExpirySettingsContext.test.data";
import {defaultResourceWorkspaceContext} from "../../../contexts/ResourceWorkspaceContext.test.data";

/**
 * Default props
 * @returns {{resource: {id: string, name: string}}}
 */
export function defaultProps(data) {
  return {
    context: defaultUserAppContext(),
    resourceWorkspaceContext: defaultResourceWorkspaceContext({
      ...data?.resourceWorkspaceContext
    }),
    passwordExpiryContext: defaultPasswordExpirySettingsContext(),
    history: {
      push: jest.fn(),
    }
  };
}
