#!/usr/bin/env php
<?php

/*
 * Testing some core features, not doing any Unit Test in here, just raw sample
 * code.
 */

use SimpleFlow\Activity\DefaultActivity;
use SimpleFlow\Event\Event;
use SimpleFlow\Process\DefaultProcessInstance;
use SimpleFlow\Process\WritableArrayProcess;
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

$process = new WritableArrayProcess("sample-process", "Sample process");

$process
    ->addActivity('a')
    ->addActivity('b')
    ->addActivity('c')
    ->addActivity('d')
    ->setTransition('a', 'b')
    ->setTransition('a', 'c')
    ->setTransition('b', 'd')
    ->setTransition('c', 'd');

// Serializing and deserializing for fun.
$data = serialize($process);
$process = unserialize($data);

$instance = new DefaultProcessInstance($process, 'a');
$instance
    ->getProcess()
    ->addListener('a', 'b', function (Event $e) {
        $e->stopPropagation();
        echo "a -> b\n";
    })
    ->addListener('a', 'b', function (Event $e) {
        echo "a -> b [ERROR]\n";
    })
    ->addListener('b', 'd', function (Event $e) {
        echo "b -> d\n";
    })
    ->addListener('a', 'c', function (Event $e) {
        echo "c -> d\n";
    })
    ->addListener('c', 'd', function (Event $e) {
        echo "c -> d\n";
    });

try {
    $instance->transitionTo('d');
    echo "Could proceed to a -> d [ERROR]\n";
} catch (TransitionNotAllowedException $e) {
    echo "Cannot proceed to a -> d\n";
}

$instance->transitionTo('b');
$instance->transitionTo('d');
