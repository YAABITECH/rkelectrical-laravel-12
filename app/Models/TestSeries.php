<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestSeries extends Model
{
    use HasFactory;
    protected $fillable = ['head_id', 'parent_id', 'name', 'tagline', 'description', 'url_slug', 'ulink', 'image', 'test_count', 'priority', 'is_paid', 'fees', 'is_index', 'mtit', 'mdes', 'ogimage', 'status', 'featured', 'launch_at', 'expire_at'];

    protected $casts = [
        'is_paid' => 'boolean',
        'is_index'  => 'boolean',
        'featured'  => 'boolean',
        'launch_at' => 'datetime',
        'expire_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::created(function ($series) {
            if ($series->parent_id) {
                $parent = TestSeries::find($series->parent_id);
                $series->update([
                    'head_id' => $parent->head_id ?: $parent->id
                ]);
                $parent->update(['is_parent' => true]);
            }
        });

        static::updated(function ($series) {
            // If parent_id changed, update old & new parents
            if ($series->wasChanged('parent_id')) {

                // OLD parent
                $oldParentId = $series->getOriginal('parent_id');
                if ($oldParentId) {
                    $oldParent = TestSeries::find($oldParentId);
                    if ($oldParent && $oldParent->getChildren()->count() === 0) {
                        $oldParent->update(['is_parent' => false]);
                    }
                }

                // NEW parent
                if ($series->parent_id) {
                    TestSeries::where('id', $series->parent_id)
                        ->update(['is_parent' => true]);
                }
            }
        });

        static::deleted(function ($series) {
            if ($series->parent_id) {
                $parent = TestSeries::find($series->parent_id);
                if ($parent && $parent->getChildren()->count() === 0) {
                    $parent->update(['is_parent' => false]);
                }
            }
        });
    }

    public function getHead()
    {
        return $this->belongsTo(TestSeries::class, 'head_id');
    }
    public function getParent()
    {
        return $this->belongsTo(TestSeries::class, 'parent_id');
    }
    public function getChildren()
    {
        return $this->hasMany(TestSeries::class, 'parent_id')
                    ->orderBy('priority');
    }
    public function getAllParents()
    {
        $parents = [];
        $node = $this;
        while ($node->parent_id !== null) {
            $node = $node->getParent;
            $parents[] = $node;
        }
        return array_reverse($parents);
    }
    public function isParent()
    {
        return $this->is_parent;
    }
    public function isChild()
    {
        return !is_null($this->parent_id);
    }
}
