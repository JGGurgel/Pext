<?php

namespace Jggurgel\Pext\Lib;

class EventDispatcher
{

    protected $events = [];

    public function __construct(
        private Database $database
    ) {
    }
    public function dispatch($event)
    {
        $this->events[] = $event;
        $this->database->insert(
            'event',
            [
                'type' => $event['type'],
                'data' => json_encode($event['data'])
            ]
        );
    }
    public function events()
    {
        return $this->events;
    }
}