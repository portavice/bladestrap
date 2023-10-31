<?php

namespace Portavice\Bladestrap\Tests\Traits;

trait TestsVariants
{
    protected static function makeVariantAttribute(?string $variant): string
    {
        return isset($variant)
            ? sprintf('variant="%s"', $variant)
            : '';
    }

    protected static function makeDataProvider(string $classPrefix): array
    {
        $variants = [
            'primary',
            'secondary',
            'success',
            'danger',
            'warning',
            'info',
            'light',
            'dark',
        ];

        $data = [];
        foreach ($variants as $variant) {
            $data[] = [
                $classPrefix . $variant,
                $variant,
            ];
        }

        return $data;
    }
}
