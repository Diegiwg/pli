<?php

require_once("../../pli.php");

$web = new Pli\Group("web", "Web Server");
$web->add_command(new Pli\Command("config", "Configure the web server", fn () => print "WEB::CONFIG\n"));
$web->add_command(new Pli\Command("build", "Build the web server", fn () => print "WEB::BUILD\n"));
$web->add_command(new Pli\Command("serve", "Serve the web server", fn () => print "WEB::SERVE\n"));


// MAIN //
$app = new Pli\App("Pli Example");

$app->add_group($web);

$app->add_command(new Pli\Command("other", "Other command", fn () => print "DEFAULT::OTHER\n"));

$app->run($argc, $argv);
