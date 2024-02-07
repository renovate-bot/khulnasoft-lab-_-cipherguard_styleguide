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
import SsoProviders from "../../Administration/ManageSsoSettings/SsoProviders.data";
import IdentifyWithSso from "./IdentifyWithSso";

export default {
  title: 'Components/Authentication/IdentifyWithSso',
  component: IdentifyWithSso
};

const Template = args =>
  <div id="container" className="container page login">
    <div className="content">
      <div className="login-form">
        <IdentifyWithSso {...args}/>
      </div>
    </div>
  </div>;

export const Initial = Template.bind({});
Initial.parameters = {
  css: "ext_authentication"
};

Initial.args = {
  context: {
    getApiClientOptions: () => ({
      getBaseUrl: () => self.location.origin,
      setResourceName: () => {},
      getResourceName: () => "sso-recover"
    }),
    trustedDomain: self.location.origin
  },
  ssoProvider: SsoProviders.find(provider => provider.id === "azure")
};
