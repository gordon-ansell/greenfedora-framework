<?php

/**
 * This file is part of the GordyAnsell GreenFedora PHP framework.
 *
 * (c) Gordon Ansell <contact@gordonansell.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);
namespace GreenFedora\Lang;

use GreenFedora\Lang\LangInterface;
use GreenFedora\FileSystem\FileInfo;
use GreenFedora\FileSystem\File;
use GreenFedora\FileSystem\DirIter;

/**
 * Language object.
 *
 * Translations and such.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class Lang implements LangInterface
{
	/**
	 * Translations.
	 * @var array
	 */
	protected $translations = array(
	);
	
	/**
	 * Current language.
	 * @var string
	 */
	protected $lang = 'en';	
	
	/**
	 * Paths we've loaded.
	 * @var array
	 */
	protected $paths = array();	
		
	/**
	 * Constructor.
	 *
	 * @param 	string		$lang 		Language.
	 *
	 * @return 	void
	 */
	public function __construct(string $lang = 'en')
	{
		$this->lang = $lang;
		$this->load($this->lang);
	}	
	
    /**
     * Load a set of language translations.
     *
     * @param   string|null                 $lang       Language.
     * @param   iterable|null      			$paths      Paths to scan for configs.
	 *
     * @return  void
     */
    public function load(?string $lang = null, ?iterable $paths = null)
    {
        if (null === $lang) {
            $lang = "en";
        }
        $this->lang = $lang;

        if (null === $paths) {
            $paths = array(APP_PATH . DIRECTORY_SEPARATOR . 'configs' . DIRECTORY_SEPARATOR . 'lang');
        }

        if (false !== strpos($this->lang, '_')) {
            $exp = explode('_', $this->lang);
            $main = $exp[0];
            $sub = $exp[1];
        } else {
            $main = $this->lang;
            $sub = '';
        }

        $final = array();
        foreach ($paths as $path) {
            $mainPath = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $main;
            $final[] = $mainPath;
            if ('' != $sub) {
                $final[] = $mainPath . DIRECTORY_SEPARATOR . $sub;
            }
        }

        $this->loadPaths($final);
    }

    /**
     * Load the configs from the relevant paths.
     *
     * @param   array                       $paths          Paths to load from
	 *
     * @return  LangInterface
     */
    public function loadPaths(array $paths) : LangInterface
    {
        $this->paths = array();
        foreach ($paths as $path) {
            $file = new FileInfo($path);
            if ($file->isDir()) {
                $iterator = $file->getDirectoryIterator();
                foreach ($iterator as $entry) {
                    if (!$entry->isDir() and !$entry->isDot()) {
                        $this->loadFile($entry->getPathname());
                    }
                }
            } else if ($file->isFile()) {
                $this->loadFile($file);
            }
            $this->paths[] = $file->getPathname();
        }
        return $this;
    }
    
    /**
     * Load a file.
     *
     * @param   string                      $file           File to load.
     * @return  LangInterface
     */
    public function loadFile(string $file) : LangInterface
    {
        $fileObject = new File($file);
        $this->translations = array_replace_recursive($this->translations, $fileObject->getIncludedContents());
        return $this;
    }
    
    /**
	 * Translate a language string, passing in an array.
	 *
	 * @param 	string		$str 			String to translate.
	 * @param 	array 		$args 			Vars for formatting.
	 *
	 * @return 	string
	 */
	public function translateArray(string $str, array $args = array()) : string
	{
		if (array_key_exists($str, $this->translations)) {
			$xl = $this->translations[$str];
			if (is_array($xl)) {
				$xl = $xl[0];
			}
			return vsprintf($xl, $args);
		}
		
		return vsprintf($str, $args);	
	}  
    
    /**
	 * Translate a language string.
	 *
	 * @param 	string		$str 			String to translate.
	 * @param 	... 		$args 			Vars for formatting.
	 *
	 * @return 	string
	 */
	public function translate(string $str, ...$args) : string
	{
		$args = func_get_args();
		array_shift($args);
		return $this->translateArray($str, $args);
	}  
	
    /**
	 * Translate a language string with relevant pluralisation, passing in an array.
	 *
	 * @param 	string		$str 			String to translate.
	 * @param 	int 		$pluralCount	Count for pluralisation.
	 * @param 	array 		$vars 			Vars for formatting.
	 *
	 * @return 	string
	 */
	public function translatePluralisedArray(string $str, int $pluralCount = 1, array $args = array()) : string
	{
		if (array_key_exists($str, $this->translations)) {
			$xl = $this->translations[$str];
			if (is_array($xl)) {
				if (1 == $pluralCount or 1 == count($xl)) {
					$xl = $xl[1];
				} else {
					$xl = $xl[0];
				}
			}
			return vsprintf($xl, $args);
		}
		
		return vsprintf($str, $args);	
	}   

    /**
	 * Translate a language string with relevant pluralisation.
	 *
	 * @param 	string		$str 			String to translate.
	 * @param 	int 		$pluralCount	Count for pluralisation.
	 * @param 	... 		$vars 			Vars for formatting.
	 *
	 * @return 	string
	 */
	public function translatePluralised(string $str, int $pluralCount = 1, ...$args) : string
	{
		$args = func_get_args();
		array_shift($args);
		array_shift($args);
		return $this->translatePluralisedArray($str, $pluralCount, $args);
	}   
}
