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
 * @since         3.9.0
 */
import React from "react";
import TestSsoSettingsDialog from "./TestSsoSettingsDialog";
import {defaultProps} from "./TestSsoSettingsDialog.test.data";
import AdminSsoContextProvider from "../../../contexts/AdminSsoContext";
import SsoProviders from "../ManageSsoSettings/SsoProviders.data";

export default {
  title: 'Components/Administration/TestSsoSettingsDialog',
  component: TestSsoSettingsDialog
};

const Template = args =>
  <AdminSsoContextProvider {...args}>
    <TestSsoSettingsDialog {...args}/>;
  </AdminSsoContextProvider>;

export const Default = Template.bind({});
Default.args = defaultProps({provider: SsoProviders.at(0)});
