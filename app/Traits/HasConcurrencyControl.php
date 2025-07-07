<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

trait HasConcurrencyControl
{
    public function lock()
    {
        if ($this->is_locked && $this->locked_by !== Auth::id()) {
            throw new \Exception('This record is currently being edited by another user.');
        }

        $this->update([
            'is_locked' => true,
            'locked_by' => Auth::id(),
            'locked_at' => now()
        ]);
    }

    public function unlock()
    {
        if ($this->locked_by === Auth::id()) {
            $this->update([
                'is_locked' => false,
                'locked_by' => null,
                'locked_at' => null
            ]);
        }
    }

    public function updateWithVersion(array $attributes = [], array $options = [])
    {
        $this->version++;
        $attributes['version'] = $this->version;
        $attributes['last_modified_at'] = now();
        $attributes['last_modified_by'] = Auth::id();

        return parent::update($attributes, $options);
    }

    public static function findWithVersion($id, $version)
    {
        $model = static::find($id);
        
        if (!$model) {
            throw new ModelNotFoundException();
        }

        if ($model->version !== $version) {
            throw new \Exception('This record has been modified by another user. Please refresh the page.');
        }

        return $model;
    }

    public function isLocked()
    {
        return $this->is_locked;
    }

    public function lockedBy()
    {
        return $this->belongsTo(User::class, 'locked_by');
    }

    public function lastModifiedBy()
    {
        return $this->belongsTo(User::class, 'last_modified_by');
    }
} 