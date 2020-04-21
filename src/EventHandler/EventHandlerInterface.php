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

    /**
     * @return void
     */
    public function activate();

    /**
     * @return void
     */
    public function deactivate();

    /**
     * @return void
     */
    public function uninstall();
}
