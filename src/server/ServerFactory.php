<?php

declare(strict_types=1);

namespace Aboshxm2\ServersOnlineCount\server;

class ServerFactory
{
    /**
     * @var Server[]
     */
    private static array $servers = [];

    /**
     * @param string $name
     * @return Server|null
     */
    public static function get(string $name): ?Server {
        return self::$servers[$name] ?? null;
    }

    public static function getByAddress(string $ip, int $port): ?Server {
        foreach (self::$servers as $server) {
            if($server->getIp() === $ip and $server->getPort() === $port)
                return $server;
        }

        return null;
    }

    public static function add(string $name, Server $server)
    {
        if(self::getByAddress($server->getIp(), $server->getPort()) !== null)
            throw new ServerFactoryException("You can't register 2 servers have the same ip and the port");

        if(!isset(self::$servers[$name]))
            self::$servers[$name] = $server;
    }

    /**
     * @param string $name
     * @return void
     */
    public static function remove(string $name): void {
        if(isset(self::$servers[$name]))
            unset(self::$servers[$name]);
    }

    /**
     * @return Server[]
     */
    public static function getAll(): array
    {
        return self::$servers;
    }

    private function __construct(){}
}