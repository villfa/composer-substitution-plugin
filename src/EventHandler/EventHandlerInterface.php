<?php

namespace SubstitutionPlugin\EventHandler;

interface EventHandlerInterface
{
    /**
     * @see \Composer\EventDispatcher\EventSubscriberInterface::getSubscribedEvents()
     *
     * @return array
     */
    public function getSubscribedEvents();

    public function activate();

    public function deactivate();

    public function uninstall();
}
