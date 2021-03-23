<?php

define ('RED', "\033[1;31m");
define ('BLUE', "\033[0;33m");
define ('GREEN', "\033[1;32m");
define ('NORMAL', "\033[0m");

assert_options(ASSERT_ACTIVE, 1);
assert_options(ASSERT_WARNING, 0);
assert_options(ASSERT_BAIL, 1);
assert_options(ASSERT_CALLBACK, 'assert_callback');

function assert_callback($script, $line, $message) {
    echo RED . "Condition failed:
        Script: $script
        Line: $line
        Condition: $message" . NORMAL . PHP_EOL;
}

function divider() {
	echo "=====================================================" . PHP_EOL;
}

function norm($msg) {
	echo $msg . PHP_EOL;
}

function good($msg) {
	echo BLUE . $msg . NORMAL . PHP_EOL;
}

function vgood($msg) {
	echo GREEN . $msg . NORMAL . PHP_EOL;
}
