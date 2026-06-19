import { Budget } from '@/types/budget';
import { Expense } from '@/types/expense';
import { Category } from '@/types/category';
import { create } from 'zustand';
import { devtools } from 'zustand/middleware';

type ExpenseModalStore = {
    open: boolean
    budget: Budget | null
    expense: Expense | null
    categories: Category[]
    openCreateModal: () => void
    openEditModal: (expense: Expense) => void
    closeModal: () => void
    setBudget: (budget: Budget) => void
    setCategories: (categories: Category[]) => void
}

export const useExpenseModalStore = create<ExpenseModalStore>()
    (devtools((set) => ({
    open: false,
    budget: null,
    expense: null,
    categories: [],
    openCreateModal: () => {
        set({ open: true })
    },
    openEditModal: (expense: Expense) => {
        set({ open: true, expense })
    },
    closeModal: () => {
        set({ open: false, expense: null })
    },
    setBudget: (budget) => {
        set({ budget })
    },
    setCategories: (categories) => {
        set({ categories })
    },
})));