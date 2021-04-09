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
namespace GreenFedora\Form\Field;

use GreenFedora\Html\Html;
use GreenFedora\Form\Field\FieldInterface;

/**
 * Form field class.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class Field extends Html implements FieldInterface
{
    /**
     * Constructor.
     * @param   string              $tag        HTML tag.
     * @param   array               $params     Parameters.
     * @return  void
     */
    public function __construct(string $tag, array $params = [])
    {
        if (array_key_exists('name', $params) and !array_key_exists('id', $params)) {
            $params['id'] = $params['name'];
        }
        parent::__construct($tag, $params);
    }

    /**
     * Set the name.
     * 
     * @param   string      $name   Name to set.
     * @return  FieldInterface
     */
    public function setName(string $name): FieldInterface
    {
        $this->setParam('name', $name);
        return $this;
    }

    /**
     * Get the name.
     * 
     * @return  string
     */
    public function getName(): string
    {
        return $this->getParam('name');
    }

    /**
     * Render the field.
     * 
     * @param   string  $data   data to use.
     * @return  string          Rendered form HTML.
     */
    public function render(?string $data = null): string
    {
        return parent::render($data) . PHP_EOL;
    }

}