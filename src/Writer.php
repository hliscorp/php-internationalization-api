<?php
namespace Lucinda\Internationalization;

require_once("Settings.php");
require_once("LocaleDetector.php");

/**
 * Writes to MO translation files GETTEXT utility will read from later on. 
 * 
 * Requires: 
 * - gettext extension (if WIN, download it from http://gnuwin32.sourceforge.net/packages/gettext.htm)
 * - if UNIX, "locale" folder @ application root to be owned by web server (chown -R www-data:www-data locale/)
 */
class Writer
{
    private $settings;
    
    private $file;    
    private $translations = array();
    private $dateCreated;
    
    /**
     * Sets up writer based on user-defined internationalization settings then reads existing translations from matching PO file (if found)
     *
     * @param Settings $settings Holds user-defined internationalization settings.
     */
    public function __construct(Settings $settings) {
        $this->settings = $settings;
        $this->readFile();
    }
    
    /**
     * Locates PO translation file based on received settings. If found, saves existing translations and creation date. Otherwise, creates folder
     * to future PO translation file based on received settings.
     */
    private function readFile() {
        $detector = new LocaleDetector($this->settings);
        $this->file = $this->settings->getFolder().DIRECTORY_SEPARATOR.$detector->getLocale().DIRECTORY_SEPARATOR."LC_MESSAGES".DIRECTORY_SEPARATOR.$this->settings->getDomain().".po";
        if(file_exists($this->file)) {
            $content = file_get_contents($this->file);
            
            // get translations
            preg_match_all('/msgid[ ]+"([^\"]+)"[ ]*\nmsgstr[ ]+"([^\"]+)"/', $content, $matches);
            foreach($matches[1] as $i=>$key) {
                $this->translations[$key] = $matches[2][$i];
            }
            
            // get date created
            $matches = array();
            preg_match('/POT-Creation-Date: ([0-9\-\ \:\+]+)/', $content, $matches);
            $this->dateCreated = $matches[1];
        } else {
            $folder = dirname($this->file);
            if(!file_exists($folder)) {
                mkdir(dirname($this->file), 0755, true);
            }
        }
    }
    
    /**
     * Adds translation or modifies existing one
     * 
     * @param string $key Message id to be used when gettext is called. Ex: "hello".
     * @param string $value Value to be received when gettext is called by above message id. Ex: "Greetings from Planet Earth!". 
     */
    public function addTranslation($key, $value) {
        $this->translations[$key] = $value;
    }
    
    /**
     * Saves PO file in detected location then converts it to MO file, the only one GETTEXT utility will read from.
     */
    public function saveFile() {
        // save PO file
        $content = '
msgid ""
msgstr ""
"POT-Creation-Date: '.($this->dateCreated?$this->dateCreated:date("Y-m-d H:iO")).'\n"
"PO-Revision-Date: '.date("Y-m-d H:iO").'\n"
"MIME-Version: 1.0\n"
"Content-Type: text/plain; charset='.($this->settings->getCharset()?$this->settings->getCharset():"UTF-8").'\n"
"Content-Transfer-Encoding: 8bit\n"

';
        foreach($this->translations as $k=>$v) {
            $content .= "\nmsgid \"".$k."\"\nmsgstr \"".$v."\"\n";
        }
        file_put_contents($this->file, $content);
        
        // convert to MO file
        exec("msgfmt -o ".str_replace(".po", ".mo", $this->file)." ".$this->file);
    }
}
