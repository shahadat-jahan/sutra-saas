import AdminLayout from '@/Layouts/AdminLayout';
import { Head, useForm, usePage } from '@inertiajs/react';
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

export default function Index({ shops }) {
    const { patch } = useForm();

    const handleStatusToggle = (shop) => {
        const newStatus = shop.status === 1 ? 0 : 1;
        patch(route('admin.shops.update', shop.id), {
            status: newStatus
        });
    };

    return (
        <AdminLayout header="Shops Management">
            <Head title="Manage Shops" />

            {/* Header Actions */}
            <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                <div>
                    <h1 className="text-2xl font-bold text-slate-900">Registered Shops</h1>
                    <p className="text-slate-500 text-sm mt-1">Manage all tenant accounts and their statuses.</p>
                </div>
                <div className="flex items-center gap-3">
                    <div className="relative">
                        <Search className="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" />
                        <input 
                            type="text" 
                            placeholder="Search shops..." 
                            className="pl-10 pr-4 py-2 border-slate-200 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500 w-full sm:w-64"
                        />
                    </div>
                    <button className="p-2 border border-slate-200 rounded-xl hover:bg-slate-50 text-slate-500">
                        <Filter className="w-5 h-5" />
                    </button>
                </div>
            </div>

            {/* Shops Table */}
            <div className="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div className="overflow-x-auto">
                    <table className="w-full text-left border-collapse">
                        <thead>
                            <tr className="bg-slate-50 border-b border-slate-200">
                                <th className="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Shop Name</th>
                                <th className="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Subdomain</th>
                                <th className="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Business Type</th>
                                <th className="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                                <th className="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-slate-100">
                            {shops.length > 0 ? shops.map((shop) => (
                                <tr key={shop.id} className="hover:bg-slate-50/50 transition-colors group">
                                    <td className="px-6 py-4">
                                        <div className="flex items-center gap-3">
                                            <div className="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                                                <Store className="w-5 h-5" />
                                            </div>
                                            <div>
                                                <span className="font-bold text-slate-900 block">{shop.name}</span>
                                                <span className="text-xs text-slate-400">ID: #{shop.id}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td className="px-6 py-4">
                                        <a 
                                            href={`http://${shop.slug}.localhost:8000`} 
                                            target="_blank" 
                                            className="inline-flex items-center gap-1.5 text-sm text-indigo-600 font-medium hover:underline"
                                        >
                                            {shop.slug}.sutra.com
                                            <ExternalLink className="w-3.5 h-3.5" />
                                        </a>
                                    </td>
                                    <td className="px-6 py-4">
                                        <div className="flex flex-col">
                                            <span className="text-sm font-medium text-slate-700">
                                                {business_types.find(t => t.value === shop.business_type)?.label || 'Other'}
                                            </span>
                                        </div>
                                    </td>
                                    <td className="px-6 py-4">
                                        <div className={`inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold ${
                                            shop.status === 1 
                                            ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' 
                                            : 'bg-red-50 text-red-700 border border-red-100'
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
                                                    ? 'bg-red-50 text-red-600 hover:bg-red-100' 
                                                    : 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100'
                                                }`}
                                            >
                                                {shop.status === 1 ? 'Suspend' : 'Activate'}
                                            </button>
                                            <button className="p-2 text-slate-400 hover:text-slate-600 transition-colors">
                                                <MoreVertical className="w-5 h-5" />
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            )) : (
                                <tr>
                                    <td colSpan="5" className="px-6 py-12 text-center">
                                        <div className="flex flex-col items-center">
                                            <Store className="w-12 h-12 text-slate-200 mb-2" />
                                            <p className="text-slate-500 font-medium">No shops found</p>
                                        </div>
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
                {/* Pagination Placeholder */}
                <div className="px-6 py-4 bg-slate-50/50 border-t border-slate-100 flex items-center justify-between">
                    <span className="text-sm text-slate-500">Showing {shops.length} results</span>
                    <div className="flex items-center gap-2">
                        <button className="px-4 py-2 text-sm font-bold text-slate-400 bg-white border border-slate-200 rounded-xl cursor-not-allowed">Previous</button>
                        <button className="px-4 py-2 text-sm font-bold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50">Next</button>
                    </div>
                </div>
            </div>
        </AdminLayout>
    );
}
