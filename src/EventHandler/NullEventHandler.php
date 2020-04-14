<?php

namespace SubstitutionPlugin\EventHandler;

class NullEventHandler implements EventHandlerInterface
{
    public function getSubscribedEvents()
    {
        return array();
    }

    public function activate()
    {
    }

    public function deactivate()
    {
    }

    public function uninstall()
    {
    }
}
