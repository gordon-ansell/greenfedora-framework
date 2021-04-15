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
namespace GreenFedora\Json;

use GreenFedora\Json\JsonInterface;

/**
 * JSON object.
 *
 * Currently this is only here to serve as a base for the deeper schema stuff.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class Json implements JsonInterface
{
    /**
     * Escape JSON.
     * 
     * @param   string      $json   JSON to escape.
     * @return  string
     */
    static public function escape(string $json): string
    {
        return preg_replace("!([\b\t\n\r\f\"\\'])!", "\\\\\\1", $json);
    }

    /**
     * Pretty print.
     * 
     * @param   string|array    $json       JSON to work with. 
     * @param   int             $indent     Indent level.
     * @return  string
     */
    static public function prettyPrint($json, $indent = 0): string
    {    
        $out = '';
    
        foreach ($json as $key => $value) {
            
            $out .= str_repeat("\t", $indent + 1);
            $out .= "\"" . self::escape((string)$key) . "\": ";
    
            if (is_object($value) || is_array($value)) {
                $out .= "\n";
                $out .= self::prettyPrint($value, $indent + 1);

            } else if (is_bool($value)) {
                $out .= $value ? 'true' : 'false';

            } else if (is_null($value)) {
                $out .= 'null';

            } elseif (is_string($value)) {
                $out .= "\"" . self::escape($value) . "\"";

            } else {
                $out .= $value;
            }
    
            $out .= ",\n";
        }
    
        if (!empty($out)) {
            $out = substr($out, 0, -2);
        }
    
        $out = str_repeat("\t", $indent) . "{\n" . $out;
        $out .= "\n" . str_repeat("\t", $indent) . "}";
    
        return $out;
    }
}