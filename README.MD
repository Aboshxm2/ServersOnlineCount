# ServersOnlineCount
Count the number of players on different servers and put it in the server motd.
# Npc NameTag
ServersOnlineCount support [SimpleNpc](https://github.com/brokiem/SimpleNPC) and [Slapper](https://github.com/vecnavium-pm-pl/Slapper/tree/stable), All you have to do is create the npc and name the npc like this  `|serverName|`.
## Commands
| Command | Description | Permission |
| ------ | ------ | ------  |
| /servers add | Add server to the servers list start querying it | ServersOnlineCount.commands.servers |
| /servers remove | remove server from the servers list | ServersOnlineCount.commands.servers |
| /servers list | List all servers in the servers | ServersOnlineCount.commands.servers |
## Permissions
| Permission | Description | Default |
| ------ | ------ | ------ |
| ServersOnlineCount.commands.servers  | Access to servers command | op

## API
### Add Server to the servers list:
```php
use Aboshxm2\ServersOnlineCount\server\ServerFactory;
use Aboshxm2\ServersOnlineCount\server\Server;

ServerFactory::add($serverName, new Server($serverName, $ip, $port));
```
#### Get Server from the servers list
```php
use Aboshxm2\ServersOnlineCount\server\ServerFactory;

$server = ServerFactory::get($name);
// or
$server = ServerFactory::getByAddress($ip, $port);
```