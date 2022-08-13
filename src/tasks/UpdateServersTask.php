<?php

declare(strict_types=1);

namespace Aboshxm2\ServersOnlineCount\tasks;

use Aboshxm2\ServersOnlineCount\server\ServerFactory;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class UpdateServersTask extends Task
{

    /**
     * @inheritDoc
     */
    public function onRun(): void
    {
        foreach (ServerFactory::getAll() as $server) {
            Server::getInstance()->getAsyncPool()->submitTask(new QueryServerTask($server->getIp(), $server->getPort()));
        }
    }
}