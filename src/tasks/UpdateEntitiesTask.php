<?php

declare(strict_types=1);

namespace Aboshxm2\ServersOnlineCount\tasks;

use Aboshxm2\ServersOnlineCount\Main;
use Aboshxm2\ServersOnlineCount\server\ServerFactory;
use pocketmine\scheduler\Task;

class UpdateEntitiesTask extends Task
{
    public function __construct(public Main $plugin)
    {
    }

    /**
     * @inheritDoc
     */
    public function onRun(): void
    {
        foreach ($this->plugin->entities as $entity) {
            $nameTag = $entity->getNameTag();
            if(count(explode("|", $nameTag)) === 3) {
                if(($server = ServerFactory::get(explode("|", $nameTag)[1])) !== null) {
                    $entity->tag
                }
            }
        }
    }
}