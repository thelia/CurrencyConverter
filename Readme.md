# Currency Converter

**Currency Converter** is a Library which helps you to convert a number from a currency to an another one.
The converter uses *provider* for converting the number. Each provider embed the logic for converting this number.

[![Build Status](https://travis-ci.org/thelia/CurrencyConverter.png?branch=master)](https://travis-ci.org/thelia/CurrencyConverter) [![License](https://poser.pugx.org/thelia/currency-converter/license.png)](https://packagist.org/packages/thelia/currency-converter) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/thelia/CurrencyConverter/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/thelia/CurrencyConverter/?branch=master)


## Installation

**Currency Converter** is still in development and doesn't have stable version for now.

Install **Currency Converter** through [Composer](http://getcomposer.org)

Create a composer.json file in your project with this content : 

```
{
    "require": {
        "thelia/currency-converter": "~1.0"
    } 

}
```

## Usage

First, instantiate a ```Provider``` of your choice

```
$provider = new \Thelia\CurrencyConverter\Provider\ECBProvider();
```

Then inject it in the ```CurrencyConverter```

```
$currencyConverty = new \Thelia\CurrencyConverter\CurrencyConverter($provider);
```

Your ```CurrencyConverter``` is now ready to be used. 
This library works with [ISO Code 4217](http://fr.wikipedia.org/wiki/ISO_4217) currency.
 
Example : 

```
$baseValue = new \Thelia\Math\Number('1');

$convertedValue = $currencyConverter
    ->from('EUR')
    ->to('USD')
    ->convert($baseValue);
    
echo $baseValue->getNumber(); //1.24
```
    
## Providers

A provider implements a simple interface and contains all the logic for converting a Number
from a Currency to an other one. Most of time a provider will use a webservice for getting
the exchange rate between two currencies.

List of available providers : 

| Provider | Description |
|---------:|-------------|
| [European Central Bank](http://www.ecb.europa.eu/stats/exchange/eurofxref/html/index.en.html) | All currencies quoted against the euro (base currency) |

