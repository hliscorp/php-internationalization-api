<?php
namespace Test\Lucinda\Internationalization;
    
use Lucinda\Internationalization\Writer;
use Lucinda\Internationalization\Settings;
use Lucinda\UnitTest\Result;

class WriterTest
{
    private $settings;
    
    public function __construct()
    {
        $settings = new Settings(simplexml_load_string('<internationalization method="request" locale="en_US" folder="tests/locale"/>'));
        $settings->setPreferredLocale($settings->getDefaultLocale());
        $this->settings = $settings;
    }
    
    public function setTranslation()
    {
        $writer = new Writer($this->settings);
        $writer->setTranslation("test", "me");
        $writer->save();
        
        $contents = json_decode(file_get_contents(__DIR__."/locale/en_US/messages.json"), true);
        return new Result(isset($contents["test"]) && $contents["test"]=="me");
    }
        

    public function unsetTranslation()
    {
        $writer = new Writer($this->settings);
        $writer->unsetTranslation("test");
        $writer->save();
        
        $contents = json_decode(file_get_contents(__DIR__."/locale/en_US/messages.json"), true);
        return new Result(!isset($contents["test"]));
    }
        

    public function save()
    {
        return $this->setTranslation();
    }
        

}
