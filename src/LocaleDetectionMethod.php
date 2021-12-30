<?php
namespace Lucinda\Internationalization;

/**
 * Enum describing possible locale detection methods
 */
enum LocaleDetectionMethod: string
{
    case HEADER = "header";
    case REQUEST = "request";
    case SESSION = "session";
}