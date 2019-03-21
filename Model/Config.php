<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Adexos\Gdpr\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Config
 */
final class Config
{
    /**#@+
     * Scope Config: Data Settings Paths
     */
    public const CONFIG_PATH_GENERAL_ENABLED = 'gdpr/general/enabled';
    public const CONFIG_PATH_GENERAL_INFORMATION_PAGE = 'gdpr/general/page_id';
    public const CONFIG_PATH_GENERAL_INFORMATION_BLOCK = 'gdpr/general/block_id';
    public const CONFIG_PATH_ERASURE_ENABLED = 'gdpr/erasure/enabled';
    public const CONFIG_PATH_ERASURE_STRATEGY = 'gdpr/erasure/strategy';
    public const CONFIG_PATH_ERASURE_TIME_LAPSE = 'gdpr/erasure/time_lapse';
    public const CONFIG_PATH_ERASURE_INFORMATION_BLOCK = 'gdpr/erasure/block_id';
    public const CONFIG_PATH_ERASURE_REMOVE_CUSTOMER = 'gdpr/erasure/remove_customer';
    public const CONFIG_PATH_ERASURE_STRATEGY_COMPONENTS = 'gdpr/erasure/components';
    public const CONFIG_PATH_ANONYMIZE_INFORMATION_BLOCK = 'gdpr/anonymize/block_id';
    public const CONFIG_PATH_EXPORT_ENABLED = 'gdpr/export/enabled';
    public const CONFIG_PATH_EXPORT_INFORMATION_BLOCK = 'gdpr/export/block_id';
    public const CONFIG_PATH_EXPORT_RENDERER = 'gdpr/export/renderer';
    public const CONFIG_PATH_EXPORT_CUSTOMER_ATTRIBUTES = 'gdpr/export/customer_attributes';
    public const CONFIG_PATH_EXPORT_CUSTOMER_ADDRESS_ATTRIBUTES = 'gdpr/export/customer_address_attributes';
    public const CONFIG_PATH_COOKIE_DISCLOSURE_ENABLED = 'gdpr/cookie/enabled';
    public const CONFIG_PATH_COOKIE_INFORMATION_BLOCK = 'gdpr/cookie/block_id';
    /**#@-*/

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Check if the current module is enabled
     *
     * @return bool
     */
    public function isModuleEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(self::CONFIG_PATH_GENERAL_ENABLED, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Retrieve the privacy information page ID
     *
     * @return string
     */
    public function getPrivacyInformationPageId(): string
    {
        return $this->getValueString(self::CONFIG_PATH_GENERAL_INFORMATION_PAGE, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Retrieve the privacy information block ID
     *
     * @return string
     */
    public function getPrivacyInformationBlockId(): string
    {
        return $this->getValueString(self::CONFIG_PATH_GENERAL_INFORMATION_BLOCK, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Check if the erasure is enabled
     *
     * @return bool
     */
    public function isErasureEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(self::CONFIG_PATH_ERASURE_ENABLED, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Retrieve the default strategy to apply
     *
     * @return string
     */
    public function getDefaultStrategy(): string
    {
        return $this->scopeConfig->getValue(self::CONFIG_PATH_ERASURE_STRATEGY, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Check if the customer can be removed if he has no orders
     *
     * @return bool
     */
    public function isCustomerRemovedNoOrders(): bool
    {
        return $this->scopeConfig->isSetFlag(self::CONFIG_PATH_ERASURE_REMOVE_CUSTOMER, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Retrieve the components configured for the deletion strategy
     *
     * @return array
     */
    public function getErasureStrategyComponents(): array
    {
        return $this->getValueArray(self::CONFIG_PATH_ERASURE_STRATEGY_COMPONENTS, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Retrieve the erasure time lapse before execution
     *
     * @return int
     */
    public function getErasureTimeLapse(): int
    {
        return (int) $this->scopeConfig->getValue(self::CONFIG_PATH_ERASURE_TIME_LAPSE, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Retrieve the erasure information block ID
     *
     * @return string
     */
    public function getErasureInformationBlockId(): string
    {
        return $this->getValueString(self::CONFIG_PATH_ERASURE_INFORMATION_BLOCK, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Retrieve the anonymize information block ID
     *
     * @return string
     */
    public function getAnonymizeInformationBlockId(): string
    {
        return $this->getValueString(self::CONFIG_PATH_ANONYMIZE_INFORMATION_BLOCK, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Check if the export is enabled
     *
     * @return bool
     */
    public function isExportEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(self::CONFIG_PATH_EXPORT_ENABLED, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Retrieve the export information block ID
     *
     * @return string
     */
    public function getExportInformationBlockId(): string
    {
        return $this->getValueString(self::CONFIG_PATH_EXPORT_INFORMATION_BLOCK, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Retrieve the export renderer code
     *
     * @return string
     */
    public function getExportRendererCode(): string
    {
        return $this->getValueString(self::CONFIG_PATH_EXPORT_RENDERER, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Retrieve the export customer attributes codes
     *
     * @return array
     */
    public function getExportCustomerAttributes(): array
    {
        return $this->getValueArray(self::CONFIG_PATH_EXPORT_CUSTOMER_ATTRIBUTES, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Retrieve the export customer address attributes codes
     *
     * @return array
     */
    public function getExportCustomerAddressAttributes(): array
    {
        return $this->getValueArray(self::CONFIG_PATH_EXPORT_CUSTOMER_ADDRESS_ATTRIBUTES, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Check if the cookie disclosure is enabled
     *
     * @return bool
     */
    public function isCookieDisclosureEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(self::CONFIG_PATH_COOKIE_DISCLOSURE_ENABLED, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Retrieve the cookie disclosure information block ID
     *
     * @return string
     */
    public function getCookieDisclosureInformationBlockId(): string
    {
        return $this->getValueString(self::CONFIG_PATH_COOKIE_INFORMATION_BLOCK, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Retrieve the scope config value as a string
     *
     * @param string $path
     * @param string $scopeType
     * @param string $scopeCode [optional]
     * @return string
     */
    private function getValueString(
        string $path,
        string $scopeType = ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
        string $scopeCode = ''
    ): string
    {
        return (string) $this->scopeConfig->getValue($path, $scopeType, $scopeCode ?: null);
    }

    /**
     * Retrieve the scope config value as an array
     *
     * @param string $path
     * @param string $scopeType
     * @param string $scopeCode [optional]
     * @return array
     */
    private function getValueArray(
        string $path,
        string $scopeType = ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
        string $scopeCode = ''
    ): array
    {
        $value = $this->scopeConfig->getValue($path, $scopeType, $scopeCode ?: null);

        return $value ? \explode(',',  $value) : [];
    }
}
