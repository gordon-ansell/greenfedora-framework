#!/bin/bash

tmp="$( cd "$(dirname "$0")" ; pwd -P )"

export TESTROOT=$(dirname $tmp)

php $TESTROOT/src/Uri/_test.php
