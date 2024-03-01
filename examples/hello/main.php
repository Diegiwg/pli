<?php

require_once("../../pli.php");

$web = new Pli\Group("web");
$web->add_command(new Pli\Command("config", fn () => print "WEB::CONFIG\n"));
$web->add_command(new Pli\Command("build", fn () => print "WEB::BUILD\n"));
$web->add_command(new Pli\Command("serve", fn () => print "WEB::SERVE\n"));


// MAIN //
$app = new Pli\App();

$app->add_group($web);

$app->add_command(new Pli\Command("other", fn () => print "DEFAULT::OTHER\n"));

$app->run($argc, $argv);
