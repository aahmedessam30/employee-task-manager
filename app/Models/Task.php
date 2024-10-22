<?php

namespace App\Models;

class Task extends Model
{
    public function creator()
    {
        return db()->table('users')->where('id', $this->created_by)->first();
    }

    public function assignee()
    {
        return db()->table('users')->where('id', $this->assigned_to)->first();
    }

    public function scopeCreatedByMe($query)
    {
        return $query->where('created_by', auth()->id());
    }

    public function scopeAssignedToMe($query)
    {
        return $query->where('assigned_to', auth()->id());
    }
}
