<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait HasArchive
{
    public function archive()
    {
        $this->update([
            'is_archived' => true,
            'archived_at' => now(),
            'archived_by' => Auth::id()
        ]);
    }

    public function unarchive()
    {
        $this->update([
            'is_archived' => false,
            'archived_at' => null,
            'archived_by' => null
        ]);
    }

    public function scopeArchived($query)
    {
        return $query->where('is_archived', true);
    }

    public function scopeNotArchived($query)
    {
        return $query->where('is_archived', false);
    }

    public function isArchived()
    {
        return $this->is_archived;
    }

    public function archivedBy()
    {
        return $this->belongsTo(User::class, 'archived_by');
    }
} 