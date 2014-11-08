<?php
/*************************************************************************************/
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/
namespace Thelia\CurrencyConverter\Exception;

/**
 * Class CurrencyNotFoundException
 * @author Manuel Raynaud <manu@thelia.net>
 */
class CurrencyNotFoundException extends \RuntimeException
{
    protected $currency;

    /**
     * @param string $currency
     */
    public function __construct($currency, $message = null, $code = 0, \Exception $previous = null)
    {
        $this->currency = $currency;

        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string return the currency unknown
     */
    public function getCurrency()
    {
        return $this->currency;
    }
}
