import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, usePage } from '@inertiajs/react';
import { BarChart3 } from 'lucide-react';

export default function ReportsIndex() {
    const { auth } = usePage().props;
    return (
        <AuthenticatedLayout header="Reports & Analytics">
            <Head title="Reports" />
            <div className="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div className="bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-slate-200 dark:border-white/10 p-12 text-center">
                    <BarChart3 className="w-16 h-16 mx-auto text-indigo-500 mb-6" />
                    <h2 className="text-2xl font-bold text-slate-900 dark:text-white mb-2">Reports & Analytics</h2>
                    <p className="text-slate-500 dark:text-slate-400 max-w-lg mx-auto">
                        Deep dive into your shop's performance metrics and export custom reports.
                    </p>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
