import AdminLayout from '@/Layouts/AdminLayout';
import { Head, Link } from '@inertiajs/react';
import { ArrowLeft, History, User, Calendar, ArrowRight } from 'lucide-react';

export default function ModuleLogs({ module_key, module_name, logs }) {
    return (
        <AdminLayout header={<h2 className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">Module Price Audit</h2>}>
            <Head title={`Audit Log - ${module_name}`} />

            <div className="mb-8 flex items-center justify-between">
                <div>
                    <Link 
                        href={route('admin.settings.index')}
                        className="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-indigo-600 transition-colors mb-2"
                    >
                        <ArrowLeft className="w-4 h-4" />
                        Back to Settings
                    </Link>
                    <h1 className="text-2xl font-bold text-slate-900 dark:text-white transition-colors">
                        {module_name} - Pricing History
                    </h1>
                    <p className="text-slate-500 dark:text-slate-400 text-sm mt-1 transition-colors">
                        Audit trail for configuration overrides.
                    </p>
                </div>
                <div className="p-3 rounded-2xl bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400">
                    <History className="w-6 h-6" />
                </div>
            </div>

            <div className="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-white/10 shadow-sm overflow-hidden transition-colors">
                <div className="overflow-x-auto">
                    <table className="w-full text-left border-collapse">
                        <thead>
                            <tr className="bg-slate-50 dark:bg-slate-800 border-b border-slate-200 dark:border-white/5 transition-colors">
                                <th className="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Date & Time</th>
                                <th className="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Changed By</th>
                                <th className="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-right">Price Change</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-slate-100 dark:divide-white/5 transition-colors">
                            {logs.length > 0 ? logs.map((log) => (
                                <tr key={log.id} className="hover:bg-slate-50/50 dark:hover:bg-white/5 transition-colors group">
                                    <td className="px-6 py-4">
                                        <div className="flex items-center gap-2 text-sm text-slate-700 dark:text-slate-300">
                                            <Calendar className="w-4 h-4 text-slate-400" />
                                            {new Date(log.created_at).toLocaleString(undefined, {
                                                year: 'numeric',
                                                month: 'short',
                                                day: 'numeric',
                                                hour: '2-digit',
                                                minute: '2-digit'
                                            })}
                                        </div>
                                    </td>
                                    <td className="px-6 py-4">
                                        <div className="flex items-center gap-2">
                                            <div className="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                                                <User className="w-4 h-4 text-slate-500" />
                                            </div>
                                            <span className="text-sm font-bold text-slate-900 dark:text-slate-200">{log.user?.name || 'System'}</span>
                                        </div>
                                    </td>
                                    <td className="px-6 py-4 text-right">
                                        <div className="flex flex-col gap-2">
                                            <div className="flex items-center justify-end gap-2 text-sm">
                                                <span className="text-slate-400 line-through font-medium">৳{Number(log.old_price_bdt).toLocaleString()}</span>
                                                <ArrowRight className="w-3 h-3 text-slate-400" />
                                                <span className="text-slate-900 dark:text-white font-black">৳{Number(log.new_price_bdt).toLocaleString()}</span>
                                            </div>
                                            <div className="flex items-center justify-end gap-2 text-sm">
                                                <span className="text-slate-400 line-through font-medium">${Number(log.old_price_usd).toLocaleString()}</span>
                                                <ArrowRight className="w-3 h-3 text-slate-400" />
                                                <span className="text-slate-900 dark:text-white font-black">${Number(log.new_price_usd).toLocaleString()}</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            )) : (
                                <tr>
                                    <td colSpan="3" className="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                                        No pricing history found for this module.
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>
        </AdminLayout>
    );
}
