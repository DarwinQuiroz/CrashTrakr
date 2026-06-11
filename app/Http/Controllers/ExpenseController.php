<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use App\Http\Requests\ExpenseRequest;
use App\Models\Budget;

class ExpenseController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(ExpenseRequest $request, Budget $budget)
    {
        $data = $request->validated();

        $budget->expenses()->create($data);

        return redirect()->route('budgets.show', $budget)
            ->with('success', 'Gasto registrado correctamente.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        //
    }
}
