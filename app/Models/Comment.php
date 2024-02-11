<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'user_id',
        'comment'
    ];

    /**
     * Get the commentator that owns the Comment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function commentator(): BelongsTo
    {
            //foreign key di tabel commnet, primary key di tabel user
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
