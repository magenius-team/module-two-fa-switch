<?php
declare(strict_types=1);

namespace Magenius\TwoFaSwitch\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;

class ConfigDataProvider
{
    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * ConfigDataProvider constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Is 2 fa enabled globally
     *
     * @return bool
     */
    public function isGloballyDisabled(): bool
    {
        return (bool)$this->scopeConfig->getValue('twofactorauth/general/enabled');
    }
}
