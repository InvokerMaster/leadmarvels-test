<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'start_date',
        'end_date',
        'quota',
        'terms_and_conditions'
    ];

    protected $cast = [
        'start_date' => 'date',
        'end_date' => 'date'
    ];

    public function company() {
        return $this->belongsTo(Company::class);
    }

    public static function boot()
    {
        parent::boot();
        self::saving(function($model) {
            if ($model->end_date < $model->start_date) {
                throw ValidationException::withMessages([
                    'end_date' => 'End Date needs to be after Start Date',
                ]);
            }
            $count = Contract::where('company_id', $model->company_id)
            ->where('id', '<>', $model->id)
            ->where(function ($query) use ($model) {
                return $query->where(function ($query) use ($model) {
                    return $query->where('start_date', '<=', $model->end_date)
                        ->where('end_date', '>=', $model->end_date);
                })->orWhere(function ($query) use ($model) {
                    return $query->where('start_date', '<=', $model->start_date)
                        ->where('end_date', '>=', $model->start_date);
                });
            })->count();
            if ($count > 0) {
                throw ValidationException::withMessages([
                    'start_date' => 'Overlap',
                    'end_date' => 'Overlap',
                ]);
            }
        });
    }
}
