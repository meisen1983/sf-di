<?php
/**
 * Created by PhpStorm.
 * User: meisen
 * Date: 2019/8/28
 * Time: 05:44
 */

namespace Dino\Play;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Yaml\Dumper;

require_once __DIR__ . '/vendor/autoload.php';

date_default_timezone_set('Asia/Shanghai');
$stime = microtime(true);

if (!file_exists(__DIR__ . '/cached_container.php')) {
    $container = new ContainerBuilder();
    $container->setParameter('root_dir', __DIR__);

    $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/config'));
    $loader->load('services.yml');

    $container->compile();

    $dumper = new PhpDumper($container);
    file_put_contents(__DIR__ . '/cached_container.php', $dumper->dump());
}

require_once __DIR__ . '/cached_container.php';
$container = new \ProjectServiceContainer();


//$handlerDefinition = new Definition(StreamHandler::class);
//$handlerDefinition->setArguments([__DIR__ . '/dino.log']);
//$container->setDefinition('logger.stream_handler', $handlerDefinition);


//$stdOutLoggerDefinition = new Definition('Monolog\Handler\StreamHandler');
//$stdOutLoggerDefinition->setArguments(['php://stdout']);
//$container->setDefinition('logger.std_out_handler', $stdOutLoggerDefinition);


//$loggerDefinition = new Definition(Logger::class);
//$loggerDefinition->setArguments(
//    [
//        'main',
//        [new Reference("logger.stream_handler")]
//    ]
//);
//$loggerDefinition->addMethodCall('pushHandler', [new Reference('logger.std_out_handler')]);
//$loggerDefinition->addMethodCall('debug', ['Logger just got started']);
//$container->setDefinition('logger', $loggerDefinition);


runApp($container);
$etime = microtime(true);
var_dump(($etime-$stime)*1000);
function runApp(ContainerInterface $container)
{
    $container->get('logger')->info(date('Ymd His'));
}