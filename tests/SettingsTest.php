<?php
namespace Test\Lucinda\Internationalization;

use Lucinda\Internationalization\LocaleDetectionMethod;
use Lucinda\Internationalization\Settings;
use Lucinda\UnitTest\Result;

class SettingsTest
{
    private $object;
    
    public function __construct()
    {
        $this->object = new Settings(\simplexml_load_string('
        <internationalization method="header" locale="en_US" domain="messages" folder="locales" extension="json"/>
        '));
    }

    public function getLocalizationMethod()
    {
        return new Result($this->object->getLocalizationMethod()==LocaleDetectionMethod::HEADER);
    }
        

    public function getDefaultLocale()
    {
        return new Result($this->object->getDefaultLocale()=="en_US");
    }
        

    public function getDomain()
    {
        return new Result($this->object->getDomain()=="messages");
    }
        

    public function getFolder()
    {
        return new Result($this->object->getFolder()=="locales");
    }
        

    public function getExtension()
    {
        return new Result($this->object->getExtension()=="json");
    }
        

    public function setPreferredLocale()
    {
        $this->object->setPreferredLocale("fr_FR");
        return new Result(true);
    }
        

    public function getPreferredLocale()
    {
        return new Result($this->object->getPreferredLocale()=="fr_FR");
    }
}
