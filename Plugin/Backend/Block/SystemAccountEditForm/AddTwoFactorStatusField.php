<?php
declare(strict_types=1);

namespace Magenius\TwoFaSwitch\Plugin\Backend\Block\SystemAccountEditForm;

use Magento\Backend\Block\System\Account\Edit\Form as EditForm;
use Magento\Backend\Model\Auth\Session;
use Magento\Config\Model\Config\Source\Enabledisable;
use Magento\Framework\Data\Form;

class AddTwoFactorStatusField
{
    /**
     * @var Session
     */
    private $authSession;

    /**
     * @var Enabledisable
     */
    private $optionValues;

    /**
     * AddTwoFactorStatusField constructor.
     * @param Session $authSession
     * @param Enabledisable $optionValues
     */
    public function __construct(
        Session $authSession,
        Enabledisable $optionValues
    ) {
        $this->authSession = $authSession;
        $this->optionValues = $optionValues;
    }

    /**
     * @param EditForm $subject
     * @param Form $form
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeSetForm(
        EditForm $subject,
        Form $form
    ): void {
        $fieldset = $form->getElement('base_fieldset');
        if ($fieldset) {
            $fieldset->addField(
                'two_fa_enabled',
                'select',
                [
                    'name' => 'two_fa_enabled',
                    'label' => __('Two Factor Authentication Status'),
                    'title' => __('Two Factor Authentication Status'),
                    'values' => $this->optionValues->toOptionArray(),
                    'class' => 'select'
                ]
            );
            $form->addValues([
                'two_fa_enabled' => $this->authSession->getUser()->getData('two_fa_enabled')
            ]);
        }
    }
}
