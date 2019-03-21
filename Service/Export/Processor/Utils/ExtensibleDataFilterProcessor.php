<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Adexos\Gdpr\Service\Export\Processor\Utils;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Class ExtensibleDataFilterProcessor
 */
final class ExtensibleDataFilterProcessor implements DataFilterProcessorInterface
{
    /**
     * @var \Adexos\Gdpr\Service\Export\Processor\Utils\DataFilterProcessor
     */
    private $dataFilterProcessor;

    /**
     * @param \Adexos\Gdpr\Service\Export\Processor\Utils\DataFilterProcessor $dataFilterProcessor
     */
    public function __construct(
        DataFilterProcessor $dataFilterProcessor
    ) {
        $this->dataFilterProcessor = $dataFilterProcessor;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(array $scheme, array $data = []): array
    {
        return $data[ExtensibleDataInterface::EXTENSION_ATTRIBUTES_KEY]
            ? $this->dataFilterProcessor->execute($scheme, $data[ExtensibleDataInterface::EXTENSION_ATTRIBUTES_KEY])
            : [];
    }
}
