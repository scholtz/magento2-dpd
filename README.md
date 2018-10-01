Installation
------------

```bash
composer require gimmick/magento2-dpd
```

or 

```bash
composer require "gimmick/magento2-dpd":"dev-master"
```


Upgrade
-------

```bash
composer update gimmick/magento2-dpd
```

After upgrade or install
------------------------

```bash
php bin/magento module:enable DPD_Shipping
```
```bash
php bin/magento setup:upgrade
```
```bash
php bin/magento setup:di:compile
```
```bash
php bin/magento setup:static-content:deploy
```

Magento 2 Shipping module by Gimmick
