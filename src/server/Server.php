<?php

declare(strict_types=1);

namespace Aboshxm2\ServersOnlineCount\server;

use Aboshxm2\ServersOnlineCount\Main;
use pocketmine\entity\Entity;

class Server
{
    /**
     * @var Entity[]
     */
    public array $entities = [];

    public function __construct(
        private string $name,
        private string $ip,
        private int $port,
        private bool $isOnline = false,
        private int $playersCount = 0,
        private int $maxPlayersCount = 0
    ){}

    /**
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @return bool
     */
    public function isOnline(): bool
    {
        return $this->isOnline;
    }

    /**
     * @param bool $isOnline
     */
    public function setIsOnline(bool $isOnline): void
    {
        $this->isOnline = $isOnline;
    }

    /**
     * @return int
     */
    public function getPlayersCount(): int
    {
        return $this->playersCount;
    }

    /**
     * @param int $playersCount
     */
    public function setPlayersCount(int $playersCount): void
    {
        $this->playersCount = $playersCount;
    }

    /**
     * @return int
     */
    public function getMaxPlayersCount(): int
    {
        return $this->maxPlayersCount;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param int $maxPlayersCount
     */
    public function setMaxPlayersCount(int $maxPlayersCount): void
    {
        $this->maxPlayersCount = $maxPlayersCount;
    }

    public function updateEntities() {
        if($this->isOnline()) {
            $nameTag = str_replace(["{count}", "{max}", "{name}"], [$this->getPlayersCount(), $this->getMaxPlayersCount(), $this->getName()], Main::getInstance()->getConfig()->get("online-server-message"));
        }else {
            $nameTag = str_replace("{name}", $this->getName(), Main::getInstance()->getConfig()->get("offline-server-message"));
        }
        foreach ($this->entities as $entity) {
            $entity->setNameTag($nameTag);
        }
    }

    public function resetEntitesName() {
        foreach ($this->entities as $entity) {
            $entity->setNameTag("|{$this->getName()}|");
        }
    }
}