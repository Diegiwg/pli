<?php


class Command
{
    public string $prog;
    public string $name;
    private array $args = [];
    private array $flags = [];

    public function __construct(string $prog, string $name)
    {
        $this->prog = $prog;
        $this->name = $name;
    }

    public function add_arg(string $arg)
    {
        $this->args[] = $arg;
    }

    public function add_flag(string $flag)
    {
        $this->flags[] = $flag;
    }

    public function stringify(): string
    {
        return "
PROG: {$this->prog}
COMMAND: {$this->name} 
ARGS:
\t" . join("\n\t", $this->args)
            . "
FLAGS:
\t" . join("\n\t", $this->flags)
            . "\n";
    }

    /**
     * Return the context of the command
     * 
     * @return array{args: array<string>, flags: array<string>}
     */
    public function ctx()
    {
        return [
            'args' => $this->args,
            'flags' => $this->flags
        ];
    }
}

function parse_args(string $input, Command &$cmd)
{
    $input = trim($input);
    if ($input === "") {
        return;
    }

    $parts = explode(" ", $input);
    foreach ($parts as $part) {
        if ($part[0] === "-") {
            $cmd->add_flag($part);
        } else {
            $cmd->add_arg($part);
        }
    }
}

function parse_command_line(string $line): Command|Error
{
    $line = trim($line);

    [$prog, $command, $args] = explode(" ", $line, 3);
    if ($command === "") {
        return new Error("No command provided");
    }

    $cmd = new Command($prog, $command);

    parse_args($args, $cmd);

    return $cmd;
}
