<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Locality extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'level',
        'type',
        'parent_id',
    ];

    public function county(): BelongsTo
    {
        return $this->belongsTo(County::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(static::class, 'parent_id');
    }

    protected function getNameWithCountyAttribute(): string
    {
        return "{$this->name}, {$this->county->name}";
    }

    protected function makeAllSearchableUsing(Builder $query): Builder
    {
        return $query->with([
            'county:id,name',
        ]);
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array<string, mixed>
     */
    public function toSearchableArray(): array
    {
        return [
            'id' => (string) $this->id,
            'name' => $this->name,
            'county' => $this->county->name,
            'county_id' => (string) $this->county_id,
            'parent_id' => (string) $this->parent_id ?: null,
        ];
    }

    public static function getTypesenseModelSettings(): array
    {
        return [
            'collection-schema' => [
                'fields' => [
                    [
                        'name' => 'id',
                        'type' => 'string',
                    ],
                    [
                        'name' => 'name',
                        'type' => 'string',
                    ],
                    [
                        'name' => 'county',
                        'type' => 'string',
                    ],
                    [
                        'name' => 'county_id',
                        'type' => 'string',
                    ],
                    [
                        'name' => 'parent_id',
                        'type' => 'string',
                        'optional' => true,
                    ],
                ],
            ],
            'search-parameters' => [
                'query_by' => 'name',
            ],
        ];
    }
}
