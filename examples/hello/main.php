<?php

require_once("../../pli.php");

// RUN: php main.php calc add 1 2

$line = implode(" ", $argv);
$cmd = parse_command_line($line);

//
if ($cmd->name === "calc") {
    $l_args = $cmd->ctx()['args'];

    if (count($l_args) < 3) {
        print("Usage: calc <add|sub> <a> <b>\n");
        exit(1);
    }

    if ($l_args[0] === "add") {
        print($l_args[1] + $l_args[2] . "\n");
    } else if ($l_args[0] === "sub") {
        print($l_args[1] - $l_args[2] . "\n");
    }
}
