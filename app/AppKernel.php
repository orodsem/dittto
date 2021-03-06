<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new Bmatzner\FontAwesomeBundle\BmatznerFontAwesomeBundle(),
            new AppBundle\AppBundle(),
            new Dittto\UserBundle\DitttoUserBundle(),
            new Dittto\SecurityBundle\DitttoSecurityBundle(),
            new Dittto\RecognitionBundle\DitttoRecognitionBundle(),
            new Dittto\SchoolBundle\DitttoSchoolBundle(),
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();

            if ('dev' === $this->getEnvironment()) {
                $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
                $bundles[] = new Symfony\Bundle\WebServerBundle\WebServerBundle();
            }
        } else {
            if (count($_SERVER['HTTP_HOST']) === 1) {
                $this->environment = explode('.', $_SERVER['HTTP_HOST'])[1];
            }
        }

        return $bundles;
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function getCacheDir()
    {
        return dirname(__DIR__).'/var/cache/'.$this->getEnvironment();
    }

    public function getLogDir()
    {
        return dirname(__DIR__).'/var/logs';
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        if (file_exists($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml')) {
            $loader->load($this->getRootDir() . '/config/config_' . $this->getEnvironment() . '.yml');
        } else {
            $loader->load($this->getRootDir().'/config/config_prod.yml');
        }
    }
}
