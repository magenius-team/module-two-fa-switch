<?php
declare(strict_types=1);

namespace Magenius\TwoFaSwitch\Plugin\TwoFactorAuth\Model;

use Magenius\TwoFaSwitch\Model\ConfigDataProvider;
use Magento\Backend\Model\Auth\Session;
use Magento\TwoFactorAuth\Api\TfaSessionInterface;

class ForceGrantAccessByConfig
{
    /**
     * @var ConfigDataProvider
     */
    private $configDataProvider;

    /**
     * @var Session
     */
    private Session $authSession;

    /**
     * DisableTwoFactorAuthenticationBasedOnConfig constructor.
     *
     * @param Session $authSession
     * @param ConfigDataProvider $configDataProvider
     */
    public function __construct(
        Session $authSession,
        ConfigDataProvider $configDataProvider
    ) {
        $this->configDataProvider = $configDataProvider;
        $this->authSession = $authSession;
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

        if (null !== $user = $this->authSession->getUser()) {
            if (!$user->getData('two_fa_enabled')) {
                return true;
            }
        }

        return $proceed();
    }
}
