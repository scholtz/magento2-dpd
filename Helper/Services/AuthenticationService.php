<?php

namespace DPD\Shipping\Helper\Services;

use Magento\Framework\App\Helper\AbstractHelper;
use DPD\Shipping\Helper\DPDClient;

class AuthenticationService extends AbstractHelper
{
    const LOGIN_SERVICE_URL = 'LoginService.svc?singleWsdl';

    const DPD_USERNAME = 'dpdshipping/account_settings/username';
    const DPD_PASSWORD = 'dpdshipping/account_settings/password';

    const DPD_ACCESSTOKEN = 'dpdshipping/accesstoken';
    const DPD_ACCESSTOKEN_DEPOT = 'dpdshipping/accesstoken_depot';
    const DPD_ACCESSTOKEN_CREATED = 'dpdshipping/accesstoken_created';

    private $directoryList;

    private $dpdClient;

    private $configWriter;

    private $crypt;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        DPDClient $dpdClient,
        \Magento\Framework\App\Config\Storage\WriterInterface $configWriter,
        \Magento\Framework\Encryption\Encryptor $crypt
    ) {
        $this->directoryList = $directoryList;
        $this->dpdClient = $dpdClient;
        $this->configWriter = $configWriter;
        $this->crypt = $crypt;
        parent::__construct($context);
    }

    public function getDelisId()
    {
        return $this->scopeConfig->getValue(self::DPD_USERNAME);
    }

    public function getDepot()
    {
        return $this->scopeConfig->getValue(self::DPD_ACCESSTOKEN_DEPOT);
    }

    public function getAccessToken($forceUpdate = false)
    {
        $accessTokenCreated = $this->scopeConfig->getValue(self::DPD_ACCESSTOKEN_CREATED);

        // If the accesstoken is less than 12 hours old, use the cached one
        if ($accessTokenCreated > time() - 12 * 60 * 60 && !$forceUpdate) {
            return $this->scopeConfig->getValue(self::DPD_ACCESSTOKEN);
        }

        $username = $this->scopeConfig->getValue(self::DPD_USERNAME);
        $password = $this->scopeConfig->getValue(self::DPD_PASSWORD);

        // Bug in magento which breaks the encryption for 2.0 installations so we have to manually decrypt it
        // Possible Magento 2.1 and upwards is to use $this->>getConfigValue()
        $password = $this->crypt->decrypt($password);

        $result = $this->dpdClient->login($username, $password);

        $this->configWriter->save(self::DPD_ACCESSTOKEN, $result['authToken']);
        $this->configWriter->save(self::DPD_ACCESSTOKEN_DEPOT, $result['depot']);
        $this->configWriter->save(self::DPD_ACCESSTOKEN_CREATED, time());

        return $result['authToken'];
    }
}
