# SimpleFlow #

PHP Finite state machine efficient implementation inspired upon some BPMN
standard concepts and semantics.

The primary goal of this API is to give a simple and fast implementation of
finite state machine, and eventually of more complex business processes in
order to implement such things as business objects workflow in various
software by providing strict invalid objects state change error control during
runtime in order to avoid pragmatic errors and saved data inconsitency.

## Capabilities ##

### Elements ###

```Element``` is the top level interface of objects that can be named and
manipulated. Not every object implements this interface.

### Process and Event API ###

Current API is unstable and changing, but right now it is able to:

 * Define ```Process``` objects, a process is a finite state maching with states
   refered as ```Activity``` and possible transitions between states implemented
   as a basic sparse matrix in the array based implementation.

 * Define ```ProcessInstance``` objects, a process instance is a stateful
   business object that has a current state in a given process.

 * Default process array based implementation can be serialized.

 * Define listeners upon process transitions: listeners are a runtime matter and
   don't get serialized with the ```Process``` instance. Listeners are instances
   of ```callable``` which makes the system quite flexible.

 * Run ```Transition``` on ```ProcessInstance``` objects using an listener and
   event based design. Listener ```callable``` objects that can accept an
   ```Event``` as first parameter: ```Event``` instance allows listeners to
   drill-down to the ```ProcessInstance``` on which it is being run upon.

### Additional notes ###

Listeners and ```ProcessInstance``` objects are runtime and business matter:
this API does not handle their serialization. It provides a default process
instance implementation for testing only.

```Transition``` instances are not persisting objects in that regard: their only
goal is to aggregate runtime listeners. The possible transitions are defined by
the ```Process``` interface as discrete objects the developer cannot manipulate
throught the given interfaces. They still can be named when pragmatically
defined in the ```WritableProcess``` interface.

## Installation ##

This library requires PHP 5.3 or higher. This code is PSR-0 code and in order to
use it, you have to provide your own PSR-0 autoloader.

Here is a two step installation:

 * Download it somewhere

 * Register it to your PSR-0 autoloader, if you don't have such thing, you can
   use this non-efficient code:

``` php
spl_autoload_register(function ($classname) {
    $parts = explode('\\', $classname);
    if ('SimpleFlow' === $parts[0]) {
        $filename = sprintf('%s/lib/%s.php', 'YOUR LIB DIR PATH', implode('/', $parts));
        if (file_exists($filename)) {
            require_once $filename;
            return true;
        }
    }
    return false;
});
```

You're good to go. Start by reading the ```sample.php``` file to see a simple
API usage.

## Disclaimer ##

The goal may be ambitious, this is not guaranted it will reach it, this is at
this very moment purely educational code and it does not ambition to be used by
concrete or business software. 

This is not supposed to become complete anytime, except if people are interested
to do so. BPMN complete specification implementation is not a goal, only
semantics and object naming are actually in use in this code.

## External links ##

 * http://www.bpmn.org/

 * http://en.wikipedia.org/wiki/Business_Process_Model_and_Notation

 * http://en.wikipedia.org/wiki/Business_process_management

 * http://en.wikipedia.org/wiki/Finite-state_machine
