<?php
/**
 * This file is part of the Flurrybox EnhancedPrivacy package.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Flurrybox EnhancedPrivacy
 * to newer versions in the future.
 *
 * @copyright Copyright (c) 2018 Flurrybox, Ltd. (https://flurrybox.com/)
 * @license   GNU General Public License ("GPL") v3.0
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Flurrybox\EnhancedPrivacy\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\Phrase;

/**
 * Account delete schema types.
 */
class Schema implements OptionSourceInterface
{
    const DELETE = 0;
    const ANONYMIZE = 1;
    const DELETE_ANONYMIZE = 2;

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::DELETE,
                'label' => new Phrase('Always delete')
            ],
            [
                'value' => self::ANONYMIZE,
                'label' => new Phrase('Always anonymize')
            ],
            [
                'value' => self::DELETE_ANONYMIZE,
                'label' => new Phrase('Delete if no orders made, anonymize otherwise')
            ]
        ];
    }
}
