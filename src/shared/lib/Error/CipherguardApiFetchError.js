/**
 * Application error
 *
 * @copyright (c) 2019 KhulnaSoft Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */

class CipherguardApiFetchError extends Error {
  constructor(message, data) {
    super(message);
    this.name = 'CipherguardApiFetchError';
    this.data = data || {};
  }
}

export default CipherguardApiFetchError;
