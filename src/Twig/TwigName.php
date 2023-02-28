<?php
namespace App\Twig;

use App\Entity\Person;
use Twig\Extension\AbstractExtension;

class TwigName extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new \Twig\TwigFilter('initialism', [$this, 'getInitialism'], ['is_safe' => ['html']]),
        ];
    }

    public function getInitialism($name): ?string
    {
        if (is_string($name)) {
            return $this->initialize($name);
        } else if (!($name instanceof Person)) {
            throw new \UnexpectedValueException(sprintf('Expected argument of type string, \Traversable or \App\Entity\Person, got "%s"', get_debug_type($name)));
        }

        if ($name->getFirstName()) {
            $result = $this->initialize($name->getFirstName()) . ' ' . $name->getLastName();
        } else {
            $result = (string) $name;
        }

        return $result;
    }

    private function initialize(string $name): ?string
    {
        if (!$name) {
            return null;
        }

        $initials = preg_replace('`(?<=^|\W)(?<initial>[AEIOUY]|[^AEIOUY][^aeiouy\']*)[^ \'-]+`', '$1.', $name);
        return '<abbr title="' . $name . '">' . ($initials ?? $name) . '</abbr>';
    }
}