<?php

namespace SubstitutionPlugin\Provider;

use Composer\Composer;
use Psr\Log\LoggerInterface;
use SubstitutionPlugin\Config\SubstitutionConfiguration;

class ProviderFactory
{
    /** @var Composer */
    private $composer;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(Composer $composer, LoggerInterface $logger)
    {
        $this->composer = $composer;
        $this->logger = $logger;
    }

    public function getProvider(SubstitutionConfiguration $configuration)
    {
        try {
            $this->logger->debug(
                'Build provider for "{placeholder}" with type {type}',
                array(
                    'placeholder' => $configuration->getPlaceholder(),
                    'type' => $configuration->getType(),
                )
            );
            return $this->buildProvider($configuration);
        } catch (\Exception $e) {
            $this->logger->error(sprintf(
                'Error with configuration extra.substitution.mapping.%s: %s',
                $configuration->getPlaceholder(),
                $e->getMessage()
            ));
            return null;
        }
    }

    /**
     * @param SubstitutionConfiguration $configuration
     * @return ProviderInterface|null
     */
    private function buildProvider(SubstitutionConfiguration $configuration)
    {
        switch ($configuration->getType()) {
            case ProviderType::LITERAL:
                $provider = new LiteralProvider($configuration->getValue());
                break;
            case ProviderType::CALLBACK:
                $provider = new CallbackProvider($configuration->getValue());
                break;
            case ProviderType::ENV:
                $provider = new EnvProvider($configuration->getValue());
                break;
            case ProviderType::INCLUDE_PHP:
                $provider = new IncludeProvider($configuration->getValue());
                break;
            default:
                // not supposed to happen
                $this->logger->critical('Invalid type: ' . $configuration->getType());
                return null;
        }

        if ($provider instanceof AutoloadDependentProviderInterface && $provider->mustAutoload()) {
            $provider = new ProviderProxyAutoloader($this->composer, $this->logger, $provider);
        }

        $provider = new ProviderProxyLogger($this->logger, $configuration, $provider);

        if ($configuration->isCached()) {
            $provider = new ProviderProxyCache($provider);
        }

        return $provider;
    }
}
