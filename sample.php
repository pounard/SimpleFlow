<?php
/*
 * Testing some core features, not doing any Unit Test in here, just raw sample
 * code.
 */

use SimpleFlow\Activity\DefaultActivity;
use SimpleFlow\Event\EventInstance;
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
    ->addActivity(new DefaultActivity('a'))
    ->addActivity(new DefaultActivity('b'))
    ->addActivity(new DefaultActivity('c'))
    ->addActivity(new DefaultActivity('d'))
    ->setTransition('a', 'b')
    ->setTransition('a', 'c')
    ->setTransition('b', 'd')
    ->setTransition('c', 'd')
    ->addListener('a', 'b', function (EventInstance $eventInstance) {
        $eventInstance->stopPropagation();
        echo "a -> b\n";
    })
    ->addListener('a', 'b', function (EventInstance $eventInstance) {
        echo "a -> b [ERROR]\n";
    })
    ->addListener('b', 'd', function (EventInstance $eventInstance) {
        echo "b -> d\n";
    })
    ->addListener('a', 'c', function (EventInstance $eventInstance) {
        echo "c -> d\n";
    })
    ->addListener('c', 'd', function (EventInstance $eventInstance) {
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
    'a' => "A",
    'b' => "B",
    'c' => "C",
    'd' => "D",
  ),
  'transitions' => array(
    'a' => array('b', 'c'),
    'b' => array('d'),
    'c' => array('d'),
  ),
));

$process
    ->addListener('a', 'b', function (EventInstance $eventInstance) {
        $eventInstance->stopPropagation();
        echo "a -> b\n";
    })
    ->addListener('a', 'b', function (EventInstance $eventInstance) {
        echo "a -> b [ERROR]\n";
    })
    ->addListener('b', 'd', function (EventInstance $eventInstance) {
        echo "b -> d\n";
    })
    ->addListener('a', 'c', function (EventInstance $eventInstance) {
        echo "c -> d\n";
    })
    ->addListener('c', 'd', function (EventInstance $eventInstance) {
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
