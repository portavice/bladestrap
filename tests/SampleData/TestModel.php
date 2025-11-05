<?php

namespace Portavice\Bladestrap\Tests\SampleData;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $short_name
 * @property-read string $display_name {@see self::displayName()}
 */
class TestModel extends Model
{
    protected $fillable = [
        'id',
        'name',
        'short_name',
    ];

    public function displayName(): Attribute
    {
        return Attribute::get(fn () => sprintf('%s (%s)', $this->name, $this->short_name));
    }

    public static function samples(): Collection
    {
        return Collection::make([
            new self(['id' => 1, 'name' => 'Test model', 'short_name' => 'TM']),
            new self(['id' => 2, 'name' => 'Another test model', 'short_name' => 'ATM']),
            new self(['id' => 4, 'name' => 'Yet another test model', 'short_name' => 'YATM']),
        ]);
    }
}
