import AdminLayout from '@/Layouts/AdminLayout';
import { Head, router, usePage } from '@inertiajs/react';
import { 
    Search, 
    Filter, 
    MoreVertical, 
    ExternalLink,
    CheckCircle2,
    XCircle,
    Clock,
    Store
} from 'lucide-react';
import { useMemo, useState } from 'react';

export default function Index({ shops }) {
    const { business_types } = usePage().props;
    const { appDomain } = usePage().props;
    const [query, setQuery] = useState('');

    const handleStatusToggle = (shop) => {
        const newStatus = shop.status === 1 ? 0 : 1;
        router.patch(route('admin.shops.update', shop.uuid), {
            status: newStatus,
        }, {
            preserveScroll: true,
        });
    };

    const filteredShops = useMemo(() => {
        const q = query.trim().toLowerCase();
        if (!q) return shops;
        return shops.filter((shop) => (
            shop.name?.toLowerCase().includes(q) ||
            shop.slug?.toLowerCase().includes(q) ||
            String(shop.id).includes(q)
        ));
    }, [shops, query]);

    const getTenantUrl = (shop) => {
        const domain = appDomain || window.location.hostname;
        const port = window.location.port ? `:${window.location.port}` : '';
        return `${window.location.protocol}//${shop.slug}.${domain}${port}`;
    };

    return (
        <AdminLayout header={<h2 className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">Shops Management</h2>}>
            <Head title="Manage Shops" />

            {/* Header Actions */}
            <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                <div>
                    <h1 className="text-2xl font-bold text-slate-900 dark:text-white transition-colors">Registered Shops</h1>
                    <p className="text-slate-500 dark:text-slate-400 text-sm mt-1 transition-colors">Manage all tenant accounts and their statuses.</p>
                </div>
                <div className="flex items-center gap-3">
                    <div className="relative">
                        <Search className="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500" />
                        <input 
                            type="text" 
                            placeholder="Search shops..." 
                            value={query}
                            onChange={(e) => setQuery(e.target.value)}
                            className="pl-10 pr-4 py-2 border-slate-200 dark:border-white/10 bg-white dark:bg-slate-800 dark:text-slate-200 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500 w-full sm:w-64 transition-colors"
                        />
                    </div>
                    <button
                        type="button"
                        disabled
                        title="Coming soon"
                        className="p-2 border border-slate-200 dark:border-white/10 rounded-xl text-slate-400 dark:text-slate-500 cursor-not-allowed"
                    >
                        <Filter className="w-5 h-5" />
                    </button>
                </div>
            </div>

            {/* Shops Table */}
            <div className="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/10 shadow-sm overflow-hidden transition-colors">
                <div className="overflow-x-auto">
                    <table className="w-full text-left border-collapse">
                        <thead>
                            <tr className="bg-slate-50 dark:bg-slate-800 border-b border-slate-200 dark:border-white/5 transition-colors">
                                <th className="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Shop Name</th>
                                <th className="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Subdomain</th>
                                <th className="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Business Type</th>
                                <th className="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
                                <th className="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-slate-100 dark:divide-white/5 transition-colors">
                            {filteredShops.length > 0 ? filteredShops.map((shop) => (
                                <tr key={shop.id} className="hover:bg-slate-50/50 dark:hover:bg-white/5 transition-colors group">
                                    <td className="px-6 py-4">
                                        <div className="flex items-center gap-3">
                                            <div className="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-900/40 flex items-center justify-center text-indigo-600 dark:text-indigo-400 transition-colors">
                                                <Store className="w-5 h-5" />
                                            </div>
                                            <div>
                                                <span className="font-bold text-slate-900 dark:text-slate-200 block transition-colors">{shop.name}</span>
                                                <span className="text-xs text-slate-400 dark:text-slate-500">ID: #{shop.id}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td className="px-6 py-4">
                                        <a 
                                            href={getTenantUrl(shop)}
                                            target="_blank" 
                                            rel="noreferrer"
                                            className="inline-flex items-center gap-1.5 text-sm text-indigo-600 dark:text-indigo-400 font-medium hover:underline transition-colors"
                                        >
                                            {shop.slug}.{appDomain || window.location.hostname}
                                            <ExternalLink className="w-3.5 h-3.5" />
                                        </a>
                                    </td>
                                    <td className="px-6 py-4">
                                        <div className="flex flex-col">
                                            <span className="text-sm font-medium text-slate-700 dark:text-slate-300 transition-colors">
                                                {business_types.find(t => t.value === shop.business_type)?.label || 'Other'}
                                            </span>
                                        </div>
                                    </td>
                                    <td className="px-6 py-4">
                                        <div className={`inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition-colors ${
                                            shop.status === 1 
                                            ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-800' 
                                            : 'bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-400 border border-red-100 dark:border-red-800'
                                        }`}>
                                            {shop.status === 1 ? (
                                                <><CheckCircle2 className="w-3.5 h-3.5" /> Active</>
                                            ) : (
                                                <><XCircle className="w-3.5 h-3.5" /> Suspended</>
                                            )}
                                        </div>
                                    </td>
                                    <td className="px-6 py-4 text-right">
                                        <div className="flex items-center justify-end gap-2">
                                            <button 
                                                onClick={() => handleStatusToggle(shop)}
                                                className={`text-xs font-bold px-3 py-1.5 rounded-lg transition-colors ${
                                                    shop.status === 1 
                                                    ? 'bg-red-50 dark:bg-red-900/40 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/60' 
                                                    : 'bg-emerald-50 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 dark:hover:bg-emerald-900/60'
                                                }`}
                                            >
                                                {shop.status === 1 ? 'Suspend' : 'Activate'}
                                            </button>
                                            <button className="p-2 text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                                                <MoreVertical className="w-5 h-5" />
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            )) : (
                                <tr>
                                    <td colSpan="5" className="px-6 py-12 text-center">
                                        <div className="flex flex-col items-center">
                                            <Store className="w-12 h-12 text-slate-200 dark:text-slate-800 mb-2 transition-colors" />
                                            <p className="text-slate-500 dark:text-slate-400 font-medium transition-colors">No shops found</p>
                                        </div>
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
                {/* Pagination Placeholder */}
                <div className="px-6 py-4 bg-slate-50/50 dark:bg-slate-800/50 border-t border-slate-100 dark:border-white/5 flex items-center justify-between transition-colors">
                    <span className="text-sm text-slate-500 dark:text-slate-400">Showing {filteredShops.length} results</span>
                    <div className="flex items-center gap-2">
                        <button className="px-4 py-2 text-sm font-bold text-slate-400 dark:text-slate-600 bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/5 rounded-xl cursor-not-allowed transition-colors">Previous</button>
                        <button className="px-4 py-2 text-sm font-bold text-slate-600 dark:text-slate-400 bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/5 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">Next</button>
                    </div>
                </div>
            </div>
        </AdminLayout>
    );
}
