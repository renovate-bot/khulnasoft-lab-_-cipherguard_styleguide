/**
 * Application error
 *
 * @copyright (c) 2019 KhulnaSoft Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */

/**
 * The cipherguard subscription error to handle key expired, invalid or no key found
 */
class CipherguardSubscriptionError extends Error {
  constructor(message, subscription = {}) {
    super(message);
    this.name = 'CipherguardSubscriptionError';
    this.subscription = subscription;
  }
}

export default CipherguardSubscriptionError;
