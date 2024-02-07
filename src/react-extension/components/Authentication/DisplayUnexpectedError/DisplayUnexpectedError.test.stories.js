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
 * @since         3.0.0
 */

import React from "react";
import {MemoryRouter, Route} from "react-router-dom";
import DisplayUnexpectedError from "./DisplayUnexpectedError";
import {
  defaultProps,
  cipherguardApiFetchErrorProps,
  cipherguardEntityValidationErrorProps
} from "./DisplayUnexpectedError.test.data";

export default {
  title: 'Components/Authentication/DisplayUnexpectedError',
  component: DisplayUnexpectedError
};

const Template = args =>
  <div id="container" className="container page login">
    <div className="content">
      <div className="login-form">
        <MemoryRouter initialEntries={['/']}>
          <Route component={routerProps => <DisplayUnexpectedError {...args} {...routerProps}/>}/>
        </MemoryRouter>
      </div>
    </div>
  </div>;


export const Initial = Template.bind({});
Initial.args = defaultProps();
Initial.parameters = {
  css: "ext_authentication"
};

export const SignInError = Template.bind({});
SignInError.args = defaultProps({
  title: "Sorry, you have not been signed in.",
  message: "Something went wrong, the sign in failed with the following error",
});
SignInError.parameters = {
  css: "ext_authentication"
};

export const ErrorWithData = Template.bind({});
ErrorWithData.args = cipherguardApiFetchErrorProps();
ErrorWithData.parameters = {
  css: "ext_authentication"
};

export const ErrorWithDetails = Template.bind({});
ErrorWithDetails.args = cipherguardEntityValidationErrorProps();
ErrorWithDetails.parameters = {
  css: "ext_authentication"
};
