<?php

namespace SubstitutionPlugin\EventHandler;

interface EventHandlerFactoryInterface
{
    /**
     * @return EventHandlerInterface
     */
    public function getEventHandler();
}
