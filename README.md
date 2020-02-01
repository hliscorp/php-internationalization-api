# Internationalization & Localization API

*Documentation below refers to latest API version, available in branch [v3.0.0](https://github.com/aherne/php-internationalization-api/tree/v3.0.0)! For older version in master branch, please check [Lucinda Framework](https://www.lucinda-framework.com/internationalization).*

This API is a very light weight platform that allows presentation logic (views) to be automatically translated based on user locale (see [how are locales detected](#how-are-locales-detected)). In order to achieve this, it expects textual parts of your views to be broken up into fine-grained units (ideally without HTML), each identified by a unique keyword and stored in a topic + locale specific dictionary file (see [how are translations stored](#how-are-translations-stored)). This way your HTML view becomes a web of units expected to be translated on compilation, as in example below:

```html
<html>
	<body>
		<h1>__("title")</h2>
		<p>__("description")</p>
	</body>
</html>
```

Since the logic of view rendering/compilation is a MVC API's concern, instead of performing keyword replacement with translations based on detected locale in response to be rendered, API provides developers a platform able to automatically detect user locale as well as setting/getting translations based on following steps:

- **[configuration](#configuration)**: setting up an XML file where API is configured for locale detection and translations storage
- **[initialization](#initialization)**: creating a [Lucinda\Internationalization\Wrapper](https://github.com/aherne/php-internationalization-api/blob/v3.0.0/src/Wrapper.php) instance based on above XML
- **[getting translations](#getting-translations)**: using [Lucinda\Internationalization\Reader](https://github.com/aherne/php-internationalization-api/blob/v3.0.0/src/Reader.php) instance to get translations based on keyword and locale 
- **[setting translations](#getting-translations)**: using [Lucinda\Internationalization\Writer](https://github.com/aherne/php-internationalization-api/blob/v3.0.0/src/Writer.php) instance to set translations based on keyword and locale 

API is fully PSR-4 compliant, only requiring PHP7.1+ interpreter and SimpleXML extension. To quickly see how it works, check:

- **[installation](#installation)**: describes how to install API on your computer, in light of steps above
- **[unit tests](#unit-tests)**: API has 100% Unit Test coverage, using [UnitTest API](https://github.com/aherne/unit-testing) instead of PHPUnit for greater flexibility
- **[example](https://github.com/aherne/php-internationalization-api/blob/v3.0.0/tests/WrapperTest.php)**: shows a deep example of API functionality based on unit test for [Lucinda\Internationalization\Wrapper](https://github.com/aherne/php-internationalization-api/blob/v3.0.0/src/Wrapper.php)

## How are locales detected

A locale is understood by this API as a combination of a double digit lowercase ISO language code and a double digit uppercase ISO country code (eg: *en_US*) joined by underscore. API is able to detect user locale based on following mechanisms:

- by value of *Accept-Language* request header (eg: $_SERVER["HTTP_ACCEPT_LANGUAGE"]= "fr-FR, fr;q=0.9, en;q=0.8, de;q=0.7, *;q=0.5");
- by value of "locale" querystring parameter (eg: $_GET["locale"] = "fr_FR");
- by value of "locale" session parameter (eg: $_SESSION["locale"] = "fr_FR");

If locale could not be detected, the default (specific to your application) will be used instead. 

## How are translations stored

Translations are expected by API to be stored in JSON files. Each JSON file is found on disk at **folder/locale/domain.extension** path where:

- *folder*: folder in your application root where translations are placed. Default: "locale"
- *locale*: locale/language in which translations will be looked after. Example: "fr_FR"
- *domain*: name of translation file. Default: "messages"
- *extension*: translation file extension. Default: "json"

Structure of that file is a dictionary where key is a short keyword that identifies each unit to be translated while value is translation text that will replace keyword when view is compiled. This means for each domain, JSON file must contain same keywords, only with different values specific to locale/language. If a keyword has no matching translation in JSON file, it will appear literally is when view is compiled (aligning with GETTEXT standards).

Examples:

- locale/en_US/greetings.json: ```json
{"hello":"Hello!", "welcome":"Welcome to my site, %0!"}
```
- locale/ro_RO/greetings.json: ```json
{"hello":"Salut!", "welcome":"Bun venit pe situl meu, %0!"}
```

## Configuration

To configure this API you must have a XML with a **internationalization** tag whose syntax is:

```xml
<internationalization method="..." folder="..." locale="..." domain="..." extension="..."/>
```

Where:

- *method*: (mandatory) identifies how locales are detected  (see [how are locales detected](#how-are-locales-detected)). Can be: header, request, session!
- *folder*: (optional) folder in your application root where translations are placed (see [how are translations stored](#how-are-translations-stored)). If not set, "locale" is assumed!
- *locale*: (mandatory) default locale in which translations will be looked after (see [how are translations stored](#how-are-translations-stored)). Eg: en_US
- *domain*: (optional) name of translation file (see [how are translations stored](#how-are-translations-stored)). If not set, "messages" is assumed!
- *extension*: (optional) translation file extension (see [how are translations stored](#how-are-translations-stored)). If not set, "json" is assumed!

## Initialization

Now that XML is configured, you can initialize API using [Lucinda\Internationalization\Wrapper](https://github.com/aherne/php-internationalization-api/blob/v3.0.0/src/Wrapper.php):

```php
$object = new Lucinda\Internationalization\Wrapper(simplexml_load_file(XML_FILE_NAME), $_GET, getallheaders());
```

This class reads XML and user request, compiles internationalization settings and makes possible to set and get translations based on following public methods:

| Method | Arguments | Returns | Description |
| --- | --- | --- | --- |
| __construct | \SimpleXMLElement $xml, array $requestParameters, array $requestHeaders | void | Compiles internationalization settings based on XML and user requests |
| getReader | void | [Lucinda\Internationalization\Reader](https://github.com/aherne/php-internationalization-api/blob/v3.0.0/src/Reader.php) | Gets instance to use in getting translations |
| getWriter | void | [Lucinda\Internationalization\Writer](https://github.com/aherne/php-internationalization-api/blob/v3.0.0/src/Writer.php) | Gets instance to use in setting translations |


## Getting Translations

Unit translation can be retrieved from [storage](#how-are-translations-stored) based on [detected locale](#how-are-locales-detected) using [Lucinda\Internationalization\Reader](https://github.com/aherne/php-internationalization-api/blob/v3.0.0/src/Reader.php), which defines following public methods:

| Method | Arguments | Returns | Description |
| --- | --- | --- | --- |
| __construct | [Lucinda\Internationalization\Settings](https://github.com/aherne/php-internationalization-api/blob/v3.0.0/src/Settings.php) | void | Injects detected user locale |
| getTranslation | string $key, string $domain=null | string | Gets value of translation based on locale. If none found, value of $key is returned! |

## Setting Translations

Unit translation can be added to / deleted from [storage](#how-are-translations-stored) based on [detected locale](#how-are-locales-detected) using [Lucinda\Internationalization\Writer](https://github.com/aherne/php-internationalization-api/blob/v3.0.0/src/Writer.php), which defines following public methods:

| Method | Arguments | Returns | Description |
| --- | --- | --- | --- |
| __construct | [Lucinda\Internationalization\Settings](https://github.com/aherne/php-internationalization-api/blob/v3.0.0/src/Settings.php) | void | Injects detected user locale |
| setTranslation | string $key, string $value | void | Sets a unit translation for detected locale based on its keyword and value. |
| unsetTranslation | string $key| void | Deletes a unit translation for detected locale based on its keyword |
| save | void | void | Persists changes to JSON translation file. |


## Installation

First choose a folder, associate it to a domain then write this command in its folder using console:

```console
composer require lucinda/internationalization
```

Then create a *configuration.xml* file holding configuration settings (see [configuration](#configuration) above) and a *index.php* file in project root with following code:

```php
require(__DIR__."/vendor/autoload.php");

$request = new Lucinda\Internationalization\Wrapper();
$reader = $request->getReader();
```

Then intervene before response is being rendered to replace unit keywords with translations. For example if your HTML is:


```html
<html>
	<body>
		<h1>__("title")</h2>
		<p>__("description")</p>
	</body>
</html>
```

Then this regex will perform perform detected locale-specific translations replacement:

```php
$response = preg_replace_callback('/__\("([^"]+)"\)/', function($matches) use ($reader) { return $reader->getTranslation($matches[1]); }, $response);
```

## Unit Tests

For tests and examples, check following files/folders in API sources:

- [test.php](https://github.com/aherne/php-internationalization-api/blob/v3.0.0/test.php): runs unit tests in console
- [unit-tests.xml](https://github.com/aherne/php-internationalization-api/blob/v3.0.0/unit-tests.xml): sets up unit tests
- [tests](https://github.com/aherne/php-internationalization-api/tree/v3.0.0/tests): unit tests for classes from [src](https://github.com/aherne/php-internationalization-api/tree/v3.0.0/src) folder

