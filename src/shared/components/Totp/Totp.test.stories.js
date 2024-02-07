/**
 * Cipherguard ~ Open source password manager for teams
 * Copyright (c) 2023 KhulnaSoft Ltd (https://www.cipherguard.khulnasoft.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) 2023 KhulnaSoft Ltd (https://www.cipherguard.khulnasoft.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.cipherguard.khulnasoft.com Cipherguard(tm)
 * @since         4.3.0
 */
import React from "react";
import {defaultProps} from "./Totp.test.data";
import Totp from "./Totp";

export default {
  title: 'Foundations/Password',
  component: "Totp"
};

const Template = args =>
  <Totp {...args}/>;

export const DefaultTotp = Template.bind({});
DefaultTotp.args = defaultProps();
