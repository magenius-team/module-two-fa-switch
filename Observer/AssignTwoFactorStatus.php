<?php
declare(strict_types=1);

namespace Magenius\TwoFaSwitch\Observer;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class AssignTwoFactorStatus implements ObserverInterface
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * AssignTwoFactorStatus constructor.
     *
     * @param RequestInterface $request
     */
    public function __construct(
        RequestInterface $request
    ) {
        $this->request = $request;
    }

    /**
     * Add two factor status from request
     *
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $user = $observer->getEvent()->getData('data_object');
        if ($user) {
            $user->addData([
                'two_fa_enabled' => (int)$this->request->getParam('two_fa_enabled', 1)
            ]);
        }
    }
}
