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
namespace GreenFedora\Template;

use GreenFedora\Template\TemplateInterface;
use GreenFedora\DependencyInjection\ContainerInterface;
use GreenFedora\DependencyInjection\ContainerAwareInterface;
use GreenFedora\DependencyInjection\ContainerAwareTrait;
use GreenFedora\Inflector\InflectorInterface;
use GreenFedora\Inflector\InflectorAwareInterface;
use GreenFedora\Inflector\InflectorAwareTrait;

use Smarty;

/**
 * Smarty templates.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class SmartyTemplate extends Smarty implements TemplateInterface, ContainerAwareInterface, InflectorAwareInterface
{
	use ContainerAwareTrait;
	use InflectorAwareTrait;

	/**
	 * Constructor.
	 *
	 * @param 	ContainerInterface	$container		Dependency injection container.
     * @param 	string 				$compileDir		Compile directory.
     * @param 	string|array		$templateDir 	Template directory.
	 *
	 * @return 	void
	 */
	public function __construct(ContainerInterface $container, string $compileDir, $templateDir)
	{
        parent::__construct();
        
        $this->container = $container;
        
	    $this->setCompileDir($compileDir);
	    $this->setTemplateDir($templateDir);
		
		$this->error_reporting = E_ALL;
		
		$this->registerPlugins();
	}	
    /**
	 * Register smarty plugins.
	 *
	 * @return 	void
	 */
	protected function registerPlugins()
	{
	    $this->registerPlugin('modifier', 'slugify', array($this, 'modifier_slugify'));
	    $this->registerPlugin('modifier', 'titlecase', array($this, 'modifier_titlecase'));
	    $this->registerPlugin('modifier', 'sha', array($this, 'modifier_sha'));
	    $this->registerPlugin('modifier', 'strip_all_tags', array($this, 'modifier_strip_all_tags'));
	    $this->registerPlugin('modifier', 'strip_script_tags', array($this, 'modifier_strip_script_tags'));	    

	    $this->registerPlugin('function', 'datexmlnow', array($this, 'function_datexmlnow'));
	} 
	
	/**
	 * Slugify.
	 *
	 * @return 	string
	 */
	public function modifier_slugify($data) : string
	{
		return $this->slugify($data);
	}
		
	/**
	 * Titlecase.
	 *
	 * @return 	string
	 */
	public function modifier_titlecase($data) : string
	{
		return $this->titleCase($data);
	}

	/**
	 * Sha.
	 *
	 * @return 	string
	 */
	public function modifier_sha($data) : string
	{
		return $this->sha($data);
	}

	/**
	 * Strip all tags.
	 *
	 * @return 	string
	 */
	public function modifier_strip_all_tags($data) : string
	{
		return $this->stripAllTags($data);
	}

	/**
	 * Strip script tags.
	 *
	 * @return 	string
	 */
	public function modifier_strip_script_tags($data) : string
	{
		return $this->stripScriptTags($data);
	}

	/**
	 * Get now date in XML format.
	 *
	 * @param 	array	$params		Parameters.
	 * @param	\Smarty	$smarty		Smarty object.
	 * @return 	string
	 */
	public function function_datexmlnow(array $params, $smarty) : string
	{
		$tz = date_default_timezone_get();
		$now = new \DateTime('now', new \DateTimeZone($tz));
		return $now->format("c");
	}
	
	/**
	 * Get the inflector.
	 *
	 * @return 	InflectorInterface
	 */
	public function getInflector() : InflectorInterface
	{
		return $this->getInstance('inflector');
	}	
}
