<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;

class TwigName extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new \Twig\TwigFunction('initialism', [$this, 'getInitialism'], ['is_safe' => ['html']]),
        ];
    }

    public function getInitialism(?string $name): ?string
    {
        preg_match($name, );
        return $name;
    }
}