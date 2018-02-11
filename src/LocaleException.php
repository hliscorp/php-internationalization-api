<?php
namespace Internationalization;

/**
 * Exception thrown when locale couldn't be properly setup (either because it doesn't exist on server, or MO file could not be located)
 */
class LocaleException extends \Exception {}