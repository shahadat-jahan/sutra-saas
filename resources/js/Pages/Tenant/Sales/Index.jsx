import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, usePage } from '@inertiajs/react';
import { Receipt } from 'lucide-react';

export default function SalesIndex() {
    const { auth } = usePage().props;
    return (
        <AuthenticatedLayout header="Sales History">
            <Head title="Sales" />
            <div className="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div className="bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-slate-200 dark:border-white/10 p-12 text-center">
                    <Receipt className="w-16 h-16 mx-auto text-indigo-500 mb-6" />
                    <h2 className="text-2xl font-bold text-slate-900 dark:text-white mb-2">Sales History</h2>
                    <p className="text-slate-500 dark:text-slate-400 max-w-lg mx-auto">
                        View past transactions, print receipts, and process refunds here.
                    </p>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
