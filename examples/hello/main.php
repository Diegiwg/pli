<?php

require_once("../../pli.php");

// main

$cmd = parse_command_line(implode(" ", $argv));
print($cmd->stringify());
