/**
 * Cipherguard ~ Open source password manager for teams
 * Copyright (c) 2020 Cipherguard SA (https://www.cipherguard.khulnasoft.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) 2020 Cipherguard SA (https://www.cipherguard.khulnasoft.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.cipherguard.khulnasoft.com Cipherguard(tm)
 * @since         3.0.0
 */

export default () => {
  return {
    resources: {
      created: Array(5)
    },
    folders: {
      created: Array(5),
      errors: ["some error", "another one"]
    },
    importTag: "Generated-custom-tag-import",
    options: {
      hasFoldersPlugin: true,
      importFolders: true,
      hasTags: true
    }
  }
}
