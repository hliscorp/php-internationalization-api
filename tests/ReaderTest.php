<?php
namespace Test\Lucinda\Internationalization;
    
use Lucinda\Internationalization\Settings;
use Lucinda\Internationalization\Reader;
use Lucinda\UnitTest\Result;

class ReaderTest
{

    public function getTranslation()
    {
        $results = [];
        
        $settings = new Settings(simplexml_load_string('<internationalization method="request" locale="en_US" folder="tests/locale"/>'));
                
        $settings->setPreferredLocale("fr_FR");
        $reader = new Reader($settings);
        $results[] = new Result($reader->getTranslation("hello", $settings->getDomain())=="Bonjour", "custom locale");
        
        
        $settings->setPreferredLocale($settings->getDefaultLocale());
        $reader = new Reader($settings);
        $results[] = new Result($reader->getTranslation("hello", $settings->getDomain())=="Hello", "default locale");
        
        return $results;
    }
        

}
