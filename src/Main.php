<?php

declare(strict_types=1);

namespace Aboshxm2\ServersOnlineCount;

use Aboshxm2\ServersOnlineCount\commands\ServersCommand;
use Aboshxm2\ServersOnlineCount\server\Server;
use Aboshxm2\ServersOnlineCount\server\ServerFactory;
use Aboshxm2\ServersOnlineCount\tasks\UpdateServersTask;
use brokiem\snpc\entity\BaseNPC;
use brokiem\snpc\entity\CustomHuman;
use brokiem\snpc\event\SNPCCreationEvent;
use pocketmine\event\entity\EntityDespawnEvent;
use pocketmine\event\entity\EntitySpawnEvent;
use pocketmine\event\Listener;
use pocketmine\event\server\QueryRegenerateEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;
use slapper\entities\SlapperHuman;
use slapper\entities\SlapperEntity;
use Webmozart\PathUtil\Path;

class Main extends PluginBase implements Listener
{
    use SingletonTrait;

    public static array $supportedEntities = [BaseNPC::class, CustomHuman::class, SlapperHuman::class, SlapperEntity::class];

    public Config $serversFile;

    protected function onLoad(): void
    {
        self::setInstance($this);
    }

    protected function onEnable(): void
    {
        $this->saveDefaultConfig();

        $this->serversFile = new Config(Path::join($this->getDataFolder(), "servers.yml"), Config::YAML);
        foreach ($this->serversFile->getAll() as $name => $stringAddress) {
            $address = explode(":", $stringAddress);
            ServerFactory::add($name, new Server($name, $address[0], (int)$address[1]));
        }

        $this->getScheduler()->scheduleRepeatingTask(new UpdateServersTask(), 20 * $this->getConfig()->get("update-interval"));

        $this->getServer()->getPluginManager()->registerEvents($this, $this);

        $this->getServer()->getCommandMap()->register("ServersOnlineCount", new ServersCommand("servers"));
    }

    protected function onDisable(): void
    {
        foreach (ServerFactory::getAll() as $server) {
            $server->resetEntitesName();
        }
    }

    public function onQuery(QueryRegenerateEvent $event) {
        $count = count($this->getServer()->getOnlinePlayers());
        foreach (ServerFactory::getAll() as $server) {
            $count += $server->getPlayersCount();
        }

        $event->getQueryInfo()->setPlayerCount($count);
    }

    public function onEntitySpawn(EntitySpawnEvent $event) {
        $entity = $event->getEntity();
        if(in_array($entity::class, self::$supportedEntities)) {
            if(count(explode("|", $entity->getNameTag())) === 3) {
                $serverName = explode("|", $entity->getNameTag())[1];
                if(($server = ServerFactory::get($serverName)) !== null) {
                    $server->entities[$entity->getId()] = $entity;
                }
            }
        }
    }

    public function onSnpcSpawn(SNPCCreationEvent $event) {
        $entity = $event->getEntity();
        if(count(explode("|", $entity->getNameTag())) === 3) {
            $serverName = explode("|", $entity->getNameTag())[1];
            if(($server = ServerFactory::get($serverName)) !== null) {
                $server->entities[$entity->getId()] = $entity;
            }
        }
    }

//    public function onEntityDespawn(EntityDespawnEvent $event) {
//        $entity = $event->getEntity();
//        if(in_array($entity::class, self::$supportedEntities)) {
//            foreach (ServerFactory::getAll() as $server) {
//                if(isset($server->entities[$entity->getId()])) {
//                    unset($server->entities[$entity->getId()]);
//                    break;
//                }
//            }
//        }
//    }
}