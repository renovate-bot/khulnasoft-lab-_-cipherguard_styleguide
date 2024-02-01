<?php
declare(strict_types=1);

/**
 * Cipherguard ~ Open source password manager for teams
 * Copyright (c) Khulnasoft Ltd' (https://www.cipherguard.khulnasoft.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Khulnasoft Ltd' (https://www.cipherguard.khulnasoft.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.cipherguard.khulnasoft.com Cipherguard(tm)
 * @since         4.5.0
 */

namespace App\Notification\DigestTemplate;

use App\Notification\Email\Redactor\Share\ShareEmailRedactor;
use Cipherguard\EmailDigest\Utility\Digest\AbstractDigestTemplate;

/**
 * Register new digest related to Resources share.
 */
class ResourceShareDigestTemplate extends AbstractDigestTemplate
{
    public const RESOURCE_SHARE_MULTIPLE_TEMPLATE = 'LU/resources_share';

    /**
     * @inheritDoc
     */
    public function getDigestSubjectIfRecipientIsTheOperator(): string
    {
        return $this->logErrorIfTheRecipientCannotBeTheOperator();
    }

    /**
     * @inheritDoc
     */
    public function getDigestSubjectIfRecipientIsNotTheOperator(): string
    {
        return __('{0} shared several items with you', '{0}');
    }

    /**
     * @inheritDoc
     */
    public function getSupportedTemplates(): array
    {
        return [
            ShareEmailRedactor::TEMPLATE,
        ];
    }

    /**
     * @inheritDoc
     */
    public function getOperatorVariableKey(): string
    {
        return 'owner';
    }

    /**
     * @inheritDoc
     */
    public function getDigestTemplate(): string
    {
        return static::RESOURCE_SHARE_MULTIPLE_TEMPLATE;
    }
}
