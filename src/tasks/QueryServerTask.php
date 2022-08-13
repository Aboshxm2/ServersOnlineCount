<?php

declare(strict_types=1);


namespace Aboshxm2\ServersOnlineCount\tasks;

use Aboshxm2\ServersOnlineCount\server\ServerFactory;
use libpmquery\PMQuery;
use libpmquery\PmQueryException;
use pocketmine\scheduler\AsyncTask;

class QueryServerTask extends AsyncTask
{
    public function __construct(
        public string $ip,
        public int $port
    ){}

    /**
     * @inheritDoc
     */
    public function onRun(): void
    {
        try{
            $query = PMQuery::query($this->ip, $this->port);
            $results = [true, (int)$query['Players'], (int)$query['MaxPlayers']];
        }catch(PmQueryException){
            $results = [false];
        }

        $this->setResult($results);
    }

    public function onCompletion(): void
    {
        $server = ServerFactory::getByAddress($this->ip, $this->port);
        if($server !== null) {
            $results = $this->getResult();
            if($results[0]) {
                $server->setIsOnline(true);
                $server->setPlayersCount($results[1]);
                $server->setMaxPlayersCount($results[2]);
            }else {
                $server->setIsOnline(false);
            }

            $server->updateEntities();
        }
    }
}