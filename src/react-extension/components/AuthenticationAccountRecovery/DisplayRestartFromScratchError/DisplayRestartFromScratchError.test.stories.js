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
import {MemoryRouter, Route} from "react-router-dom";
import DisplayRequireInvitationError from "./DisplayRestartFromScratchError";
import DisplayRestartFromScratchError from "./DisplayRestartFromScratchError";

export default {
  title: 'Components/AuthenticationAccountRecovery/DisplayRestartFromScratchError',
  component: DisplayRequireInvitationError
};

const Template = args =>
  <div id="container" className="container page login">
    <div className="content">
      <div className="login-form">
        <MemoryRouter initialEntries={['/']}>
          <Route component={routerProps => <DisplayRestartFromScratchError {...args} {...routerProps}/>}/>
        </MemoryRouter>
      </div>
    </div>
  </div>;


export const Initial = Template.bind({});
Initial.parameters = {
  css: "ext_authentication"
};
