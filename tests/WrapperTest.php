<?php
namespace Test\Lucinda\Internationalization;

use Lucinda\Internationalization\Wrapper;
use Lucinda\UnitTest\Result;

class WrapperTest
{
    private $object;
    
    public function __construct()
    {
        $this->object = new Wrapper(simplexml_load_string('
<xml>
    <internationalization method="request" locale="en_US" folder="tests/locale"/>
</xml>
        '), ["locale"=>"fr_FR"], []);
    }

    public function getReader()
    {
        return new Result($this->object->getReader()->getTranslation("hello")=="Bonjour");
    }
        

    public function getWriter()
    {
        $writer = $this->object->getWriter();
        $writer->setTranslation("test", "me");
        $writer->save();
        return new Result($this->object->getReader()->getTranslation("test")=="me");
    }
}
