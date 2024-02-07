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
 * @since         v4.3.0
 */

import React from "react";
import Range from "./Range";
import {defaultProps} from "./Range.test.data";

export default {
  title: 'Foundations/Range',
  component: Range
};

const Template = args =>
  <div>
    <Range {...args} />
  </div>;

export const Default = Template.bind({});
Default.args = defaultProps();
