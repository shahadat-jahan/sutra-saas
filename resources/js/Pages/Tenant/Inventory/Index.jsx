import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, usePage } from '@inertiajs/react';
import { Package } from 'lucide-react';

export default function InventoryIndex() {
    const { auth } = usePage().props;
    return (
        <AuthenticatedLayout header="Inventory Management">
            <Head title="Inventory" />
            <div className="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div className="bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-slate-200 dark:border-white/10 p-12 text-center">
                    <Package className="w-16 h-16 mx-auto text-indigo-500 mb-6" />
                    <h2 className="text-2xl font-bold text-slate-900 dark:text-white mb-2">Inventory Management</h2>
                    <p className="text-slate-500 dark:text-slate-400 max-w-lg mx-auto">
                        Manage your products, stock levels, and variants from this module.
                    </p>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
