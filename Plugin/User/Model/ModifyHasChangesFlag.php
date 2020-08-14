<?php
declare(strict_types=1);

namespace Magenius\TwoFaSwitch\Plugin\User\Model;

use Magento\Framework\App\RequestInterface;
use Magento\User\Model\User;

class ModifyHasChangesFlag
{
    /**
     * @var RequestInterface
     */
    private $request;

    public function __construct(
        RequestInterface $request
    ) {
        $this->request = $request;
    }

    /**
     * Force set data changes flag for status field
     *
     * @param User $subject
     * @param $result
     * @return bool
     */
    public function afterHasDataChanges(
        User $subject,
        $result
    ): bool {
        if (!$result) {
            return $subject->getData('two_fa_enabled') !== (int)$this->request->getParam('two_fa_enabled');
        }

        return $result;
    }
}
