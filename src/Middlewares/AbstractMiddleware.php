<?php


namespace UltraStar\Middlewares;


use Psr\Container\ContainerInterface;

/**
 * @property array settings
 */
class AbstractMiddleware
{
    private $container;

    /**
     * AbstractMiddleware constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param $name
     *
     * @return mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __get($name)
    {
        return $this->container->get($name);
    }
}
