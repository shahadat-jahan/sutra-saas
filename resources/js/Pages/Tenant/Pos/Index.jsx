import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, usePage } from '@inertiajs/react';
import { Calculator } from 'lucide-react';

export default function PosIndex() {
    const { auth } = usePage().props;
    return (
        <AuthenticatedLayout header="Point of Sale">
            <Head title="Point of Sale" />
            <div className="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div className="bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-slate-200 dark:border-white/10 p-12 text-center">
                    <Calculator className="w-16 h-16 mx-auto text-indigo-500 mb-6" />
                    <h2 className="text-2xl font-bold text-slate-900 dark:text-white mb-2">Point of Sale</h2>
                    <p className="text-slate-500 dark:text-slate-400 max-w-lg mx-auto">
                        The full POS terminal interface is being loaded into this module. Stay tuned!
                    </p>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
