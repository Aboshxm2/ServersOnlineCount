<?php

declare(strict_types=1);

namespace Aboshxm2\ServersOnlineCount\commands;

use Aboshxm2\ServersOnlineCount\Main;
use Aboshxm2\ServersOnlineCount\server\Server;
use Aboshxm2\ServersOnlineCount\server\ServerFactory;
use Aboshxm2\ServersOnlineCount\server\ServerFactoryException;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;

class ServersCommand extends Command
{
    public function __construct(string $name, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
        $this->setPermission("ServersOnlineCount.commands.servers");
        $this->setPermissionMessage("§cYou don't have permission to use this command!");
    }

    /**
     * @inheritDoc
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if(!$this->testPermission($sender)) return;

        if(!isset($args[0])) {
            usage:
            $sender->sendMessage("/servers add|remove|list [args]");
            return;
        }

        switch ($args[0]) {
            case "add":
                if(!isset($args[3])) {
                    $sender->sendMessage("/servers add <name> <ip> <port>");
                    return;
                }

                try {
                    ServerFactory::add($args[1], new Server($args[1], $args[2], (int)$args[3]));
                    Main::getInstance()->serversFile->set($args[1], "{$args[2]}:{$args[3]}");
                    Main::getInstance()->serversFile->save();
                    $sender->sendMessage("done");// TODO better messages
                }catch (ServerFactoryException $e) {
                    $sender->sendMessage($e);
                }
                break;
            case "remove":
                if(!isset($args[1])) {
                    $sender->sendMessage("/servers remove <name>");
                    return;
                }

                ServerFactory::remove($args[1]);
                Main::getInstance()->serversFile->save();
                Main::getInstance()->serversFile->remove($args[1]);
                $sender->sendMessage("done");
                break;
            case "list":
                $sender->sendMessage(join("\n", array_map(fn(Server $server, string $name) => $name . " => " . $server->getIp().":".$server->getPort(),  ServerFactory::getAll(), array_keys( ServerFactory::getAll()))));
                break;
            default:
                goto usage;
        }
    }
}