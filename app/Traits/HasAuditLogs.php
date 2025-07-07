<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait HasAuditLogs
{
    protected static function bootHasAuditLogs()
    {
        static::created(function ($model) {
            $model->logAudit('create', null, $model->toArray());
        });

        static::updated(function ($model) {
            $model->logAudit('update', $model->getOriginal(), $model->getChanges());
        });

        static::deleted(function ($model) {
            $model->logAudit('delete', $model->toArray(), null);
        });
    }

    public function logAudit($action, $oldValues = null, $newValues = null)
    {
        if (!Auth::check()) {
            return;
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model_type' => get_class($this),
            'model_id' => $this->id,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent()
        ]);
    }

    public function auditLogs()
    {
        return $this->morphMany(AuditLog::class, 'model');
    }
} 