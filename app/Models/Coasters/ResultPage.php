<?php

namespace ChaseH\Models\Coasters;

use Illuminate\Database\Eloquent\Model;

class ResultPage extends Model
{
    protected $fillable = [
        'name',
        'url',
        'public',
        'default',
        'group',
        'description',
        'run_at',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'run_at',
    ];

    public function setDefault() {
        $this->update([
            'default' => true,
        ]);

        self::where('id', '!=', $this->id)->update([
            'default' => false,
        ]);
    }
}
