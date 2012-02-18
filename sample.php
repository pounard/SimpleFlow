<?php
/*
 * Testing some core features, not doing any Unit Test in here, just raw sample
 * code.
 */

use SimpleFlow\Activity\DefaultActivity;
use SimpleFlow\Event\Event;
use SimpleFlow\Process\ArrayProcess;
use SimpleFlow\Process\LazyArrayProcess;
use SimpleFlow\Process\TransitionNotAllowedException;

spl_autoload_register(function ($classname) {
    $parts = explode('\\', $classname);
    if ('SimpleFlow' === $parts[0]) {
        $filename = sprintf('%s/lib/%s.php', __DIR__, implode('/', $parts));
        if (file_exists($filename)) {
            require_once $filename;
            return true;
        }
    }
    return false;
});

echo "ArrayProcess base implementation usage:\n";

$process = new ArrayProcess("sample-process", "Sample process");

$process
    ->addActivity('a')
    ->addActivity('b')
    ->addActivity('c')
    ->addActivity('d')
    ->setTransition('a', 'b')
    ->setTransition('a', 'c')
    ->setTransition('b', 'd')
    ->setTransition('c', 'd')
    ->addListener('a', 'b', function (Event $eventInstance) {
        $eventInstance->stopPropagation();
        echo "a -> b\n";
    })
    ->addListener('a', 'b', function (Event $eventInstance) {
        echo "a -> b [ERROR]\n";
    })
    ->addListener('b', 'd', function (Event $eventInstance) {
        echo "b -> d\n";
    })
    ->addListener('a', 'c', function (Event $eventInstance) {
        echo "c -> d\n";
    })
    ->addListener('c', 'd', function (Event $eventInstance) {
        echo "c -> d\n";
    });

try {
    $process->runTransition('a', 'd');
    echo "Could proceed to a -> d [ERRRO]\n";
} catch (TransitionNotAllowedException $e) {
    echo "Cannot proceed to a -> d\n";
}

$process->runTransition('a', 'b');
$process->runTransition('b', 'd');

echo "LazyArrayProcess base implementation usage:\n";

$process = new LazyArrayProcess(array(
  'key' => 'sample-process',
  'name' => "Sample process",
  'activities' => array(
    'a' => "a",
    'b' => "b",
    'c' => "c",
    'd' => "d",
  ),
  'transitions' => array(
    'a' => array('b', 'c'),
    'b' => array('d'),
    'c' => array('d'),
  ),
));

$process
    ->addListener('a', 'b', function (Event $eventInstance) {
        $eventInstance->stopPropagation();
        echo "a -> b\n";
    })
    ->addListener('a', 'b', function (Event $eventInstance) {
        echo "a -> b [ERROR]\n";
    })
    ->addListener('b', 'd', function (Event $eventInstance) {
        echo "b -> d\n";
    })
    ->addListener('a', 'c', function (Event $eventInstance) {
        echo "c -> d\n";
    })
    ->addListener('c', 'd', function (Event $eventInstance) {
        echo "c -> d\n";
    });

try {
    $process->runTransition('a', 'd');
    echo "Could proceed to a -> d [ERRRO]\n";
} catch (TransitionNotAllowedException $e) {
    echo "Cannot proceed to a -> d\n";
}

$process->runTransition('a', 'b');
$process->runTransition('b', 'd');
