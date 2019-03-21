<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Adexos\Gdpr\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\Phrase;
use Adexos\Gdpr\Model\Config\ErasureComponentStrategy;

/**
 * Erasure Components Processors Data Source
 */
class ErasureComponents implements OptionSourceInterface
{
    /**
     * @var \Adexos\Gdpr\Model\Config\ErasureComponentStrategy
     */
    private $componentStrategy;

    /**
     * @var array
     */
    private $options;

    /**
     * @param \Adexos\Gdpr\Model\Config\ErasureComponentStrategy $componentStrategy
     */
    public function __construct(
        ErasureComponentStrategy $componentStrategy
    ) {
        $this->componentStrategy = $componentStrategy;
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray(): array
    {
        if (!$this->options) {
            $componentNames = \array_intersect(
                $this->componentStrategy->getAnonymizeComponentsNames(),
                $this->componentStrategy->getDeleteComponentsNames()
            );

            foreach ($componentNames as $componentName) {
                $this->options[] = ['label' => new Phrase($componentName), 'value' => $componentName];
            }
        }

        return $this->options;
    }
}
