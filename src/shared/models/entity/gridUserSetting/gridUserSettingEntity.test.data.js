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
import {defaultColumnSettingData} from "../columnSetting/columnSettingEntity.test.data";
import {defaultSorterData} from "../sorter/sorterEntity.test.data";

export const defaultGridUserSettingData = (data = {}) => {
  const defaultData = {
    "columns_setting": [defaultColumnSettingData(), defaultColumnSettingData({id: "idB"})],
    "sorter": defaultSorterData()
  };

  return Object.assign(defaultData, data);
};
