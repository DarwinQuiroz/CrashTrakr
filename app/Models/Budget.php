<?php

namespace App\Models;

use App\BudgetType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['user_id', 'name', 'amount', 'type'])]
class Budget extends Model
{
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'type' => BudgetType::class
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isGeneral()
    {
        return $this->type === BudgetType::General;
    }

    public function isGoal()
    {
        return $this->type === BudgetType::Goal;
    }
}
