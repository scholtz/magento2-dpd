<?php

namespace DPD\Shipping\Block;

class ParcelshopInfo extends \Magento\Framework\View\Element\Template
{
    private $parcelshop;
    private $quote;
    private $countryFactory;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Directory\Model\CountryFactory $countryFactory
    ) {
        parent::__construct($context);
        $this->countryFactory = $countryFactory;
    }

    public function setParcelshop($parcelshop)
    {
        $this->parcelshop = $parcelshop;
    }

    public function getParcelshop()
    {
        return $this->parcelshop;
    }

    public function setQuote($quote)
    {
        $this->quote = $quote;
    }

    public function getQuote()
    {
        return $this->quote;
    }

    public function getCountry($countryCode)
    {
        $country = $this->countryFactory->create()->loadByCode($countryCode);
        return $country->getName();
    }

    /**
     * Render the html for openinghours. (to keep template files clean from to much functional php)
     *
     * @param $dpdExtraInfo
     * @return string
     */
    public function getOpeningHoursHtml($dpdExtraInfo)
    {
        $html = "";
        if (is_array(json_decode($dpdExtraInfo))) {
                $html .= '<table>
							<tbody>';
            foreach (json_decode($dpdExtraInfo) as $openinghours) {
                $html .= '<tr><td style="padding-right:10px; padding-top:3px; padding-bottom:3px;"><strong>' . __($openinghours->weekday) . '</strong></td><td style="padding-right:10px; padding-top:3px; padding-bottom:3px;">' . $openinghours->openMorning . ' - ' . $openinghours->closeMorning . '
								</td><td style="padding-right:10px; padding-top:3px; padding-bottom:3px;">' . $openinghours->openAfternoon . ' - ' . $openinghours->closeAfternoon . '</td></tr>';
            }
                            $html .= '</tbody>
							</table>';
        } else {
            $html .= $dpdExtraInfo;
        }
        return $html;
    }
}
