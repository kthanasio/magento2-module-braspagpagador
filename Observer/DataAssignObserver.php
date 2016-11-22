<?php

namespace Webjump\BraspagPagador\Observer;

use Magento\Framework\Event\Observer;
use Magento\Payment\Observer\AbstractDataAssignObserver;
use Magento\Quote\Api\Data\PaymentInterface;
use Magento\Framework\DataObject;
use Webjump\BraspagPagador\Api\CardTokenRepositoryInterface;

/**
 * Credit Card Data Assign
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class DataAssignObserver extends AbstractDataAssignObserver
{
    protected $cardTokenRepository;

    public function __construct(
        CardTokenRepositoryInterface $cardTokenRepository
    ) {
        $this->setCardTokenRepository($cardTokenRepository);
    }

    public function execute(Observer $observer)
    {
        $method = $this->readMethodArgument($observer);
        $info = $method->getInfoInstance();
        $data = $this->readDataArgument($observer);

        $additionalData = $data->getData(PaymentInterface::KEY_ADDITIONAL_DATA);
        if (!is_object($additionalData)) {
            $additionalData = new DataObject($additionalData ?: []);
        }

        $info->addData([
            'cc_type' => $additionalData->getCcType(),
            'cc_owner' => $additionalData->getCcOwner(),
            'cc_number' => $additionalData->getCcNumber(),
            'cc_cid' => $additionalData->getCcCid(),
            'cc_exp_month' => $additionalData->getCcExpMonth(),
            'cc_exp_year' => $additionalData->getCcExpYear()
        ]);

        if ($additionalData->getCcInstallments()) {
            $info->setAdditionalInformation('cc_installments', (int) $additionalData->getCcInstallments());
        }

        if ($additionalData->getCcSavecard()) {
            $info->setAdditionalInformation('cc_savecard', (boolean) $additionalData->getCcSavecard());
        }

        if ($additionalData->getCcToken()) {
            $cardToken = $this->getCardTokenRepository()->get($additionalData->getCcToken());
            $info->setCcType($cardToken->getProvider() . '-' . $cardToken->getBrand());
            $info->setAdditionalInformation('cc_token', $additionalData->getCcToken());
        }

        if ($additionalData->getCcSoptpaymenttoken()) {
            $info->setAdditionalInformation('cc_soptpaymenttoken', $additionalData->getCcSoptpaymenttoken());
        }

        return $this;
    }

    protected function getCardTokenRepository()
    {
        return $this->cardTokenRepository;
    }

    protected function setCardTokenRepository($cardTokenRepository)
    {
        $this->cardTokenRepository = $cardTokenRepository;

        return $this;
    }
}
