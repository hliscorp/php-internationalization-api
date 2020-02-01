<?php
namespace Test\Lucinda\Internationalization;

use Lucinda\Internationalization\LocaleDetector;
use Lucinda\UnitTest\Result;

class LocaleDetectorTest
{
    public function getDetectedLocale()
    {
        $results = [];
        
        $detector = new LocaleDetector([], ["Accept-Language"=>"en-US,en;q=0.5"], "header");
        $results[] = new Result($detector->getDetectedLocale()=="en_US", "header-based detection");
        
        $detector = new LocaleDetector(["locale"=>"en_US"], [], "request");
        $results[] = new Result($detector->getDetectedLocale()=="en_US", "request-based detection");
        
        session_start();
        $_SESSION["locale"] = "en_US";
        $detector = new LocaleDetector([], [], "session");
        $results[] = new Result($detector->getDetectedLocale()=="en_US", "session-based detection");
        session_destroy();
        
        return $results;
    }
}
