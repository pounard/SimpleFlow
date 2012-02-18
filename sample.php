<?php
/*
 * Testing some core features, not doing any Unit Test in here, just raw sample
 * code.
 */

use BpmnFlow\Activity\DefaultActivity;
use BpmnFlow\Event\EventInstance;

use SimpleFlow\ArrayProcess;
use SimpleFlow\TransitionNotAllowedException;

spl_autoload_register(function ($classname) {
    $parts = explode('\\', $classname);
    if ('BpmnFlow' === $parts[0] || 'SimpleFlow' === $parts[0]) {
        $filename = sprintf('%s/lib/%s.php', __DIR__, implode('/', $parts));
        if (file_exists($filename)) {
            require_once $filename;
            return true;
        }
    }
    return false;
});

$process = new ArrayProcess("sample-process", "Sample process");

$process
    ->addActivity(new DefaultActivity('a'))
    ->addActivity(new DefaultActivity('b'))
    ->addActivity(new DefaultActivity('c'))
    ->addActivity(new DefaultActivity('d'))
    ->setTransition('a', 'b')
    ->setTransition('a', 'c')
    ->setTransition('b', 'd')
    ->setTransition('c', 'd');

$process
    ->getEvent('a', 'b')
    ->addListener(function (EventInstance $eventInstance) {
        $eventInstance->stopPropagation();
        echo "a -> b\n";
    })
    ->addListener(function (EventInstance $eventInstance) {
        echo "a -> b [ERROR]\n";
    });

$process
    ->getEvent('b', 'd')
    ->addListener(function (EventInstance $eventInstance) {
        echo "b -> d\n";
    });

$process
    ->getEvent('a', 'c')
    ->addListener(function (EventInstance $eventInstance) {
        echo "c -> d\n";
    });

$process
    ->getEvent('c', 'd')
    ->addListener(function (EventInstance $eventInstance) {
        echo "c -> d\n";
    });

// print_r($process);die();

try {
    $process->runTransition('a', 'd');
    echo "Could proceed to a -> d [ERRRO]\n";
} catch (TransitionNotAllowedException $e) {
    echo "Cannot proceed to a -> d\n";
}

$process->runTransition('a', 'b');
$process->runTransition('b', 'd');

