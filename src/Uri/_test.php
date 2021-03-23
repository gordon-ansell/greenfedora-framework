<?php
	
/**
 * This file is part of the GordyAnsell GreenFedora PHP framework.
 *
 * (c) Gordon Ansell <contact@gordonansell.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
define("TESTROOT", getenv("TESTROOT"));

require TESTROOT . "/vendor/autoload.php";
require TESTROOT . "/Test/setup.php";

use GreenFedora\Uri\Uri;

divider();
norm("Testing Uri");

// ==========================================================

$uri = new Uri("https://example.com/base/deep/this-is-a-file");

if (!assert("'https://example.com/base/deep/this-is-a-file' == \$uri->getUri()", "Assertion failed getting full Url (from absolute input)")) exit();
good("Uri passes full Url test given absolute.");

if (!assert("'/deep/this-is-a-file' == \$uri->getRelative('https://example.com/base')", "Assertion failed getting relative Url from absolute")) exit();
good("Uri passes relative URL test given absolute.");

// ==========================================================

$uri = new Uri("deep/this-is-a-file");

if (!assert("'deep/this-is-a-file' == \$uri->getUri()", "Assertion failed getting full Url (from relative input)")) exit();
good("Uri passes full Url test given relative.");

if (!assert("'https://example.com/base/deep/this-is-a-file' == \$uri->getAbsolute('https://example.com/base')", "Assertion failed getting absolute Url from relative")) exit();
good("Uri passes absolute URL test given relative.");

// ==========================================================

$uri = new Uri("/deep/this-is-a-file", true);

if (!assert("'https://example.com/base/deep/this-is-a-file' == \$uri->getAbsolute('https://example.com/base')", "Assertion failed getting hard-coded relative Url from absolute")) exit();
good("Uri passes hard-coded relative URI test.");

// ==========================================================

vgood("Uri passed all tests.");
divider();
