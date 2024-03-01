<?php

namespace Pli;

function dump(App &$app)
{
    print_r($app);
}

function default_help(App &$app)
{
    print "USAGE: {$app->prog_path} <GROUP> <COMMAND> [OPTIONS] [ARGS]...\n";
    print "\n";
    print "  Welcome to {$app->prog_name}! This tool helps you manage your tasks effortlessly.\n";
    print "\n";

    print "Commands:\n";
    foreach ($app->groups as $group) {
        if ($group->name_id !== 'default') {
            print "  {$group->name_id}\n";
        }

        foreach ($group->commands as $command) {
            print "    {$command->name_id}  {$command->description}\n";
        }
    }
    print "\n";
}

function parse_command_line(App &$app, int $argc, array $argv)
{
    $app->prog_path = $argv[0];
    array_shift($argv);

    if ($argc < 2) {
        return;
    }

    // PARSE GROUP
    if (isset($app->groups[$argv[0]])) {
        $app->group = &$app->groups[$argv[0]]->name_id;
        array_shift($argv);
    }

    // PARSE COMMAND
    if ($app->group && $argc < 3) {
        return;
    }

    if (!$app->group) {
        $app->group = "default";
    }

    if (isset($app->groups[$app->group]->commands[$argv[0]])) {
        $app->command = &$app->groups[$app->group]->commands[$argv[0]]->name_id;
        array_shift($argv);
    }

    // PARSE FLAGS AND ARGS
}

class App
{
    public string $prog_path;
    public string $prog_name;

    /**
     * @var array<string, Group>
     */
    public array $groups = [];

    public ?string $group = null;
    public ?string $command = null;
    /**
     * @var array<string>
     */
    public array $args;
    /**
     * @var array<string, mixed>
     */
    public array $flags;

    // TODO: function for set default command
    // TODO: function for override default help command

    public function __construct(string $name)
    {
        $this->prog_name = $name;
        $this->groups['default'] = new Group('default', "Default Group");
        $this->add_command(new Command('help', 'Help', fn () => default_help($this)));
    }

    public function add_group(Group $group)
    {
        // Check if group already exists
        if (isset($this->groups[$group->name_id])) {
            throw new \Exception('Group already exists');
        }

        // Check if command already exists in default group
        if (isset($this->groups['default']->commands[$group->name_id])) {
            throw new \Exception('Command with this name already exists');
        }

        $this->groups[$group->name_id] = $group;
    }

    public function add_command(Command $command)
    {
        // Check if command already exists
        if (isset($this->groups[$command->name_id])) {
            throw new \Exception('Command already exists');
        }

        // Check if exist a group with this name
        if (isset($this->groups[$command->name_id])) {
            throw new \Exception('Group with this name already exists');
        }

        $l_default = &$this->groups['default'];
        $l_default->add_command($command);
    }

    public function run(int $argc, array $argv)
    {
        parse_command_line($this, $argc, $argv);

        // TODO: Exec the default command, not the help
        if (!$this->command) {
            $this->groups['default']->commands['help']->run();
            return;
        }

        $this->groups[$this->group]->commands[$this->command]->run();
    }
}

class Command
{
    public string $name_id;
    public string $description;

    /**
     * @var callable
     */
    private $callback;

    public function __construct(string $name_id, string $description, callable $callback)
    {
        $this->name_id = $name_id;
        $this->description = $description;
        $this->callback = $callback;
    }

    public function run()
    {
        ($this->callback)();
    }
}

class Group
{
    public string $name_id;
    public string $description;

    /** @var callable */
    public $callback;

    /** @var array<Command> `name_id` => `Command` */
    public array $commands = [];

    /**
     * @param string $name_id
     * @param array<Command> $commands
     */
    public function __construct(string $name_id, string $description, array $commands = [])
    {
        $this->name_id = $name_id;
        $this->description = $description;
        $this->commands = $commands;
    }

    public function add_command(Command $command)
    {
        // Check if command already exists
        if (isset($this->commands[$command->name_id])) {
            throw new \Exception('Command already exists');
        }

        $this->commands[$command->name_id] = $command;
    }

    public function set_default_callback(callable $callback)
    {
        $this->callback = $callback;
    }
}
