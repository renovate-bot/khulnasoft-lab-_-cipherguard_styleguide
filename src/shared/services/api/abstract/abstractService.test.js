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
 * @since         3.0.0
 */
import {ApiClientOptions} from "../../../lib/apiClient/apiClientOptions";
import AbstractService from "./abstractService";

describe("Abstract service", () => {
  it("constructor works", () => {
    const options = (new ApiClientOptions()).setBaseUrl('https://test.cipherguard.test/');
    const service = new AbstractService(options, 'test');

    // Basics
    let t = () => { service.assertValidId('test'); };
    expect(t).toThrow(TypeError);
    t = () => { service.assertNonEmptyData(null); };
    expect(t).toThrow(TypeError);
  });

  it("constructor works", () => {
    const options = (new ApiClientOptions()).setBaseUrl('https://test.cipherguard.test/');
    const service = new AbstractService(options, 'test');

    const formated = service.formatContainOptions(
      {"user": true, "user.profile": false},
      ['user', 'user.profile', 'user.profile.avatar', 'gpgkey'],
    );
    expect(formated).toEqual({"contain[user]": "1", "contain[user.profile]": "0"});
  });
});
