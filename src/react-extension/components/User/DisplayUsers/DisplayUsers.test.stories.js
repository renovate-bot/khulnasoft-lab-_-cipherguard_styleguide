/**
 * Cipherguard ~ Open source password manager for teams
 * Copyright (c) KhulnaSoft LtdRL (https://www.cipherguard.khulnasoft.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) KhulnaSoft LtdRL (https://www.cipherguard.khulnasoft.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.cipherguard.khulnasoft.com Cipherguard(tm)
 * @since         3.4.0
 */
import {MemoryRouter, Route} from "react-router-dom";
import React from "react";
import DisplayUsers from "./DisplayUsers";
import {defaultContext, defaultProps, propsWithFirstUserAttentionRequired} from "./DisplayUsers.test.data";


export default {
  title: 'Components/User/DisplayUsers',
  component: DisplayUsers
};

const Template = args =>
  <MemoryRouter initialEntries={['/']}>
    <div className="page">
      <div className="panel">
        <Route component={routerProps => <DisplayUsers {...args} {...routerProps}/>}></Route>
      </div>
    </div>
  </MemoryRouter>;

export const Initial = Template.bind({});
Initial.args = Object.assign(defaultProps(), {context: defaultContext()});

export const AccountRecoveryPending = Template.bind({});
AccountRecoveryPending.args = Object.assign(propsWithFirstUserAttentionRequired(), {context: defaultContext()});
