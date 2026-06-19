import { useExpenseModalStore } from "@/stores/expense-modal-store";
import { useForm } from "@inertiajs/react";
import Ziggy from "@/ziggy";
import { route } from "ziggy-js";
import InputError from "./InputError";
import { DialogTitle } from "@headlessui/react";

export default function ExpenseForm() {
    const budget = useExpenseModalStore((state) => state.budget);
    const expense = useExpenseModalStore((state) => state.expense);
    const categories = useExpenseModalStore((state) => state.categories);
    const closeModal = useExpenseModalStore((state) => state.closeModal);

    const isEditing = !!expense;

    const { data, setData, post, put, errors, reset, processing } = useForm({
        name: expense?.name ?? "",
        amount: expense?.amount ?? "",
        category: expense?.category ?? "",
    });

    if (!budget) return null;

    const submit = (e: React.SubmitEvent<HTMLFormElement>) => {
        e.preventDefault();

        if (isEditing && expense) {
            put(route("expenses.update", [budget.id, expense.id]), {
                onSuccess: () => {
                    reset();
                    closeModal();
                },
                preserveScroll: true,
            });
            return;
        }

        post(route("expenses.store", budget.id), {
            onSuccess: () => {
                reset();
                closeModal();
            },
            preserveScroll: true,
        });
    };

    return (
        <>
            <DialogTitle
                as="h3"
                className="text-4xl font-black mt-10 text-center text-gray-900 dark:text-white"
            >
                {isEditing ? "Editar Gasto" : "Nuevo Gasto"}
            </DialogTitle>

            <div className="p-10 flex justify-center">
                <form
                    className="flex flex-col space-y-3 w-full"
                    onSubmit={submit}
                >
                    <div className="space-y-3">
                        <label
                            htmlFor="name"
                            className="block text-xl font-bold text-gray-900 dark:text-gray-100"
                        >
                            Nombre Gasto
                        </label>
                        <input
                            id="name"
                            type="text"
                            placeholder="Nombre del gasto"
                            className="w-full border border-gray-300 p-3 rounded-lg bg-white text-gray-900 focus:outline-hidden focus:ring-2 focus:ring-purple-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400 dark:focus:ring-purple-500"
                            value={data.name}
                            onChange={(e) => setData("name", e.target.value)}
                        />
                        {errors.name && <InputError>{errors.name}</InputError>}
                    </div>

                    <div className="space-y-3">
                        <label
                            htmlFor="amount"
                            className="block text-xl font-bold text-gray-900 dark:text-gray-100"
                        >
                            Cantidad Gasto
                        </label>
                        <input
                            id="amount"
                            type="number"
                            placeholder="Cantidad"
                            className="w-full border border-gray-300 p-3 rounded-lg bg-white text-gray-900 focus:outline-hidden focus:ring-2 focus:ring-purple-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400 dark:focus:ring-purple-500"
                            value={data.amount}
                            onChange={(e) => setData("amount", e.target.value)}
                        />
                        {errors.amount && (
                            <InputError>{errors.amount}</InputError>
                        )}
                    </div>

                    {budget.type === "general" && (
                        <div className="space-y-3">
                            <label
                                htmlFor="category"
                                className="block text-xl font-bold text-gray-900 dark:text-gray-100"
                            >
                                Categoría Gasto
                            </label>
                            <select
                                name="category"
                                id="category"
                                className="w-full border border-gray-300 p-3 rounded-lg bg-white text-gray-900 focus:outline-hidden focus:ring-2 focus:ring-purple-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-purple-500"
                                value={data.category}
                                onChange={(e) =>
                                    setData("category", e.target.value)
                                }
                            >
                                <option value="" className="dark:bg-gray-700">
                                    Selecciona Categoría
                                </option>
                                {categories.map((category) => (
                                    <option
                                        key={category.value}
                                        value={category.value}
                                        className="dark:bg-gray-700"
                                    >
                                        {category.label}
                                    </option>
                                ))}
                            </select>
                            {errors.category && (
                                <InputError>{errors.category}</InputError>
                            )}
                        </div>
                    )}

                    <button
                        disabled={processing}
                        type="submit"
                        className={`${processing ? "opacity-50 cursor-not-allowed" : "bg-purple-600 hover:bg-purple-800 cursor-pointer"} mt-5 w-full p-3 rounded-lg text-white font-bold  text-xl disabled:opacity-50`}
                    >
                        {processing
                            ? "Guardando..."
                            : isEditing
                              ? "Actualizar Gasto"
                              : "Agregar Gasto"}
                    </button>
                </form>
            </div>
        </>
    );
}
