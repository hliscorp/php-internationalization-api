<?php
namespace Lucinda\Internationalization;

/**
 * Detects locale based on contents of internationalization tag:
 *
 * <internationalization locale="en_US" method="session"/>
 */
class LocaleDetector
{
    const PARAMETER_NAME = "locale";
    private ?string $detectedLocale = null;

    /**
     * Determines method to detect locale then performs locale detection by matching XML with client request/headers
     *
     * @param string[string] $requestParameters
     * @param string[string] $requestHeaders
     * @param LocaleDetectionMethod $detectionMethod
     * @throws ConfigurationException
     */
    public function __construct(array $requestParameters, array $requestHeaders, LocaleDetectionMethod $detectionMethod)
    {
        $this->setDetectedLocale($requestParameters, $requestHeaders, $detectionMethod);
    }

    /**
     * Sets detected locale based on detection method, client request as well as <session> XML tag (if detection method is session)
     *
     * @param string[string] $requestParameters
     * @param string[string] $requestHeaders
     * @param LocaleDetectionMethod $detectionMethod
     * @throws ConfigurationException
     */
    private function setDetectedLocale(array $requestParameters, array $requestHeaders, LocaleDetectionMethod $detectionMethod): void
    {
        switch ($detectionMethod) {
            case LocaleDetectionMethod::HEADER:
                if (isset($requestHeaders["Accept-Language"])) {
                    $matches = [];
                    preg_match_all("/(([a-z]{2})\-([A-Z]{2}))/", $requestHeaders["Accept-Language"], $matches);
                    if (!empty($matches[1])) {
                        $this->detectedLocale = $matches[2][0]."_".$matches[3][0];
                    }
                }
                break;
            case LocaleDetectionMethod::REQUEST:
                if (isset($requestParameters[self::PARAMETER_NAME])) {
                    $this->detectedLocale = $requestParameters[self::PARAMETER_NAME];
                }
                break;
            case LocaleDetectionMethod::SESSION:
                if (session_id() == "") {
                    throw new ConfigurationException("Session must be already started!");
                }
                if (isset($_SESSION[self::PARAMETER_NAME])) {
                    $this->detectedLocale = $_SESSION[self::PARAMETER_NAME];
                }
                if (isset($requestParameters[self::PARAMETER_NAME])) {
                    $this->detectedLocale = $requestParameters[self::PARAMETER_NAME];
                }
                break;
        }
    }
    
    /**
     * Gets detected locale
     *
     * @return string|NULL
     */
    public function getDetectedLocale(): ?string
    {
        return $this->detectedLocale;
    }
}
