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

import React from "react";
import {MemoryRouter} from "react-router-dom";
import AccountRecoveryInviteUserSettingPreferenceDialog from "./AccountRecoveryInviteUserSettingPreferenceDialog";
import MockTranslationProvider from "../../../test/mock/components/Internationalisation/MockTranslationProvider";
import MockPort from "../../../test/mock/MockPort";

export default {
  title: 'Components/AccountRecovery/AccountRecoveryInviteUserSettingPreferenceDialog',
  component: AccountRecoveryInviteUserSettingPreferenceDialog
};

const Template = args =>
  <MockTranslationProvider>
    <MemoryRouter initialEntries={['/']}>
      <AccountRecoveryInviteUserSettingPreferenceDialog {...args}/>
    </MemoryRouter>
  </MockTranslationProvider>;

export const Mandatory = Template.bind({});
Mandatory.args = {
  context: {
    port: new MockPort(),
    locale: "en-US",
  },
  policy: "mandatory",
  onClose: () => {}
};

export const OptOut = Template.bind({});
OptOut.args = {
  context: {
    port: new MockPort(),
    locale: "en-US",
  },
  policy: "opt-out",
  onClose: () => {}
};
