/**
 * Network error
 *
 * @copyright (c) 2019 KhulnaSoft Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */

class CipherguardServiceUnavailableError extends Error {
  constructor(message) {
    message = message || "The service is unavailable";
    super(message);
    this.name = 'CipherguardServiceUnavailableError';
  }
}

export default CipherguardServiceUnavailableError;
