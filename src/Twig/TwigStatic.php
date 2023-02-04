<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;

class TwigStatic extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new \Twig\TwigFunction('static', [$this, 'getStaticProperty']),
        ];
    }

    public function getStaticProperty($static, $entity = null): mixed
    {
        if ($entity != null && !is_string($entity)) {
            $entity = \get_class($entity);
        }
        if (property_exists($entity, $static)) {
            return $entity::$$static;
        }

        return null;
    }
}