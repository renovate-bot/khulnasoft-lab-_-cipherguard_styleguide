/**
 * Cipherguard ~ Open source password manager for teams
 * Copyright (c) 2020 KhulnaSoft Ltd (https://www.cipherguard.khulnasoft.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) 2020 KhulnaSoft Ltd (https://www.cipherguard.khulnasoft.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.cipherguard.khulnasoft.com Cipherguard(tm)
 * @since         2.11.0
 */

import {MemoryRouter, Route} from "react-router-dom";
import React from "react";
import AppContext from "../../../../shared/context/AppContext/AppContext";
import PropTypes from "prop-types";
import {defaultAppContext, defaultProps, mockGpgKey} from "./EditUserGroup.test.data";
import EditUserGroup from "./EditUserGroup";

export default {
  title: 'Components/UserGroup/EditUserGroup',
  component: EditUserGroup
};

const Template = ({context, ...args}) =>
  <AppContext.Provider value={context}>
    <MemoryRouter initialEntries={['/']}>
      <Route component={routerProps => <EditUserGroup {...args} {...routerProps}/>}></Route>
    </MemoryRouter>
  </AppContext.Provider>;

Template.propTypes = {
  context: PropTypes.object,
};

const initialContext = defaultAppContext();
initialContext.port.addRequestListener('cipherguard.keyring.get-public-key-info-by-user', async() => mockGpgKey);
export const Initial = Template.bind({});
Initial.args = {
  context: initialContext,
  ...defaultProps(),
};

export const Loading = Template.bind({});
Loading.args = {
  context: defaultAppContext(),
  ...defaultProps(),
};
