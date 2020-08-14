<?php
declare(strict_types=1);

namespace Magenius\TwoFaSwitch\Plugin\User\Controller;

use Exception;
use Magento\Backend\Controller\Adminhtml\System\Account\Save;
use Magento\Backend\Model\Auth;
use Magento\User\Model\ResourceModel\User as UserResource;
use Magento\User\Model\User;
use Psr\Log\LoggerInterface;

class AdminhtmlSystemAccountSaveAfter
{
    /**
     * @var Auth
     */
    private $auth;

    /**
     * @var UserResource
     */
    private $userResource;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * AdminhtmlSystemAccountSaveAfter constructor.
     *
     * @param Auth $auth
     * @param UserResource $userResource
     * @param LoggerInterface $logger
     */
    public function __construct(
        Auth $auth,
        UserResource $userResource,
        LoggerInterface $logger
    ) {
        $this->auth = $auth;
        $this->userResource = $userResource;
        $this->logger = $logger;
    }

    /**
     * @param Save $subject
     * @param $result
     * @return mixed
     */
    public function afterExecute(
        Save $subject,
        $result
    ) {
        if ($this->auth->isLoggedIn()) {
            /** @var User $user */
            $user = $this->auth->getUser();
            $twoFaStatus = (int)$subject->getRequest()->getParam('two_fa_enabled', 1);
            if ((int)$user->getData('two_fa_enabled') !== $twoFaStatus) {
                $user->addData([
                    'two_fa_enabled' => $twoFaStatus
                ]);
                try {
                    $this->userResource->save($user);
                } catch (Exception $e) {
                    $this->logger->critical($e->getMessage());
                }
            }
        }
        return $result;
    }
}
