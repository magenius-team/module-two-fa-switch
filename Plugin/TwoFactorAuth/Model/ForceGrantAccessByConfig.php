<?php
declare(strict_types=1);

namespace Magenius\TwoFaSwitch\Plugin\TwoFactorAuth\Model;

use Magenius\TwoFaSwitch\Model\ConfigDataProvider;
use Magento\Backend\Model\Auth;
use Magento\TwoFactorAuth\Api\TfaSessionInterface;

class ForceGrantAccessByConfig
{
    /**
     * @var ConfigDataProvider
     */
    private $configDataProvider;

    /**
     * @var Auth
     */
    private $auth;

    /**
     * DisableTwoFactorAuthenticationBasedOnConfig constructor.
     *
     * @param Auth $auth
     * @param ConfigDataProvider $configDataProvider
     */
    public function __construct(
        Auth $auth,
        ConfigDataProvider $configDataProvider
    ) {
        $this->configDataProvider = $configDataProvider;
        $this->auth = $auth;
    }

    /**
     * Force grand access based on config
     *
     * @param TfaSessionInterface $subject
     * @param callable $proceed
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @return bool
     */
    public function aroundIsGranted(
        TfaSessionInterface $subject,
        callable $proceed
    ): bool {
        if (!$this->configDataProvider->isGloballyDisabled()) {
            return true;
        }

        if (null !== $user = $this->auth->getUser()) {
            if (!$user->getData('two_fa_enabled')) {
                return true;
            }
        }

        return $proceed();
    }
}
