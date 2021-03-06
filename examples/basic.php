<?php
/**
 * @author      Daniel Kesberg <kesberg@gmail.com>
 * @copyright   (c) 2013, Daniel Kesberg
 */

error_reporting(E_ALL);
ini_set('display_errors', false);

// json pretty printing
include './vendor/GloryFish/pretty-json.php';

require_once '../src/Starbound/LogReader.php';
use Starbound\LogReader as LogReader;

$logreader = new LogReader(array(
    'log.path' => '/home/starbound-server/Steam/SteamApps/common/Starbound/linux64'
));
?>
<!doctype html>
<html lang="en-US">
<head>    
    <meta charset="UTF-8">
    <title>Starbound Server Info</title>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">
    <style type="text/css">
        body {
            padding-top: 70px;
        }
        table > tbody > tr > td.server-status {
            vertical-align: middle;
        }
    </style>
</head>
<body>
<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">Starbound Server Info</a>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading"><span class="glyphicon glyphicon-globe"></span> Server</div>
                <div class="panel-body">
                    <table class="table table-condensed table-bordered">
                        <tbody>
                        <tr>
                            <th>Status</th>
                            <td class="server-status">
                                <span class="label label-<?= ($logreader->getServerStatus()) ? 'success' : 'danger' ; ?>">
                                <?= ($logreader->getServerStatus() == 1) ? 'Online' : 'Offline' ; ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Version</th>
                            <td><?= $logreader->getServer()['version']; ?></td>
                        </tr>
                        <tr>
                            <th>Hostname</th>
                            <td><?= $logreader->getServer()['hostname']; ?></td>
                        </tr>
                        <tr>
                            <th>IP</th>
                            <td><?= $logreader->getServer()['ip']; ?></td>
                        </tr>
                        <tr>
                            <th>Players Online</th>
                            <td><?= $logreader->getPlayerCount(); ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading"><span class="glyphicon glyphicon-user"></span> Players</div>
                <div class="panel-body">
                    <?php if ($logreader->getPlayerCount()): ?>
                        <table class="table table-condensed table-bordered">
                            <thead>
                            <tr>
                                <th>Playername</th>
                                <th>Ip</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($logreader->getPlayers() as $player): ?>
                                <tr>
                                    <td>
                                        <?= $player['name']; ?>
                                    </td>
                                    <td>
                                        <?= $player['ip']; ?>
                                    </td>
                                    <td>
                                        <?= $player['status']; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        No active players
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading"><span class="glyphicon glyphicon-user"></span> Chat</div>
                <div class="panel-body">
                    <?php if (count($logreader->getChatlog()) !== 0): ?>
                        <table class="table table-condensed table-bordered">
                            <thead>
                            <tr>
                                <th>Playername</th>
                                <th>Text</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($logreader->getChatlog(true) as $chatline): ?>
                                <tr>
                                    <td>
                                        <?= $chatline['name']; ?>
                                    </td>
                                    <td>
                                        <?= $chatline['text']; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        No chat messages
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading"><span class="glyphicon glyphicon-cog"></span> JSON</div>
                <div class="panel-body">
                    <?php
                    if (function_exists('_format_json')) {
                        echo _format_json($logreader->json(), true);
                    } else {
                        echo json_encode(json_decode($logreader->json(), true), JSON_PRETTY_PRINT);
                    } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <span class="label label-default">
                Log parse time: <?= $logreader->parsetime; ?> seconds.
            </span>
        </div>
    </div>
</div>
</body>
</html>



