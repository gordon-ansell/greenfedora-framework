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
namespace GreenFedora\Html;

/**
 * HTML statement interface.
 */
interface HtmlInterface
{
	/**
	 * Build the statement.
	 *
	 * $param 	string|null	$data 	Data.
	 * @return 	string
	 */
	public function build(?string $data = null) : string;
	
	/**
	 * Set the data.
	 *
	 * @param 	mixed 	$data 	Data to set.
	 * @return  HtmlInterface
	 */
	public function setData($data) : self;

    /**
     * Set a parameter.
     * 
     * @param   string  $name   Name of parameter to set.
     * @param   mixed   $val    Value to set.
     * @return  HtmlInterface 
     */
    public function setParam(string $name, $val): self;
	
	/**
	 * Set the self-closing flag.
	 *
	 * @param 	bool 	$sc 	Value.
	 * @return  HtmlInterface
	 */
	public function setSelfClose(bool $sc = true) : self;

}
