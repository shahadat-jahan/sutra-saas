import AdminLayout from '@/Layouts/AdminLayout';
import { Head } from '@inertiajs/react';
import { 
    Search, 
    UserPlus, 
    MoreHorizontal, 
    Mail, 
    Shield, 
    Calendar,
    User as UserIcon,
    Store
} from 'lucide-react';
import { useMemo, useState } from 'react';

export default function Index({ users }) {
    const [query, setQuery] = useState('');

    const filteredUsers = useMemo(() => {
        const q = query.trim().toLowerCase();
        if (!q) return users;
        return users.filter((user) => (
            user.name?.toLowerCase().includes(q) ||
            user.email?.toLowerCase().includes(q) ||
            String(user.id).includes(q)
        ));
    }, [users, query]);

    return (
        <AdminLayout header={<h2 className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">User Management</h2>}>
            <Head title="Manage Users" />

            <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                <div>
                    <h1 className="text-2xl font-bold text-slate-900 dark:text-white transition-colors">Platform Users</h1>
                    <p className="text-slate-500 dark:text-slate-400 text-sm mt-1 transition-colors">Supervise all registered accounts across the platform.</p>
                </div>
                <button
                    type="button"
                    disabled
                    title="Coming soon"
                    className="flex items-center gap-2 bg-indigo-600/60 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-indigo-100 dark:shadow-none cursor-not-allowed"
                >
                    <UserPlus className="w-4 h-4" />
                    Add New User
                </button>
            </div>

            <div className="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/10 shadow-sm overflow-hidden transition-colors">
                <div className="p-4 border-b border-slate-100 dark:border-white/5 flex items-center justify-between transition-colors">
                    <div className="relative flex-1 max-w-md">
                        <Search className="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500" />
                        <input 
                            type="text" 
                            placeholder="Find users by name, email or ID..." 
                            value={query}
                            onChange={(e) => setQuery(e.target.value)}
                            className="w-full pl-10 pr-4 py-2 bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 dark:text-slate-200 dark:placeholder-slate-500 transition-colors"
                        />
                    </div>
                </div>

                <div className="overflow-x-auto">
                    <table className="w-full text-left">
                        <thead>
                            <tr className="border-b border-slate-100 dark:border-white/5 transition-colors">
                                <th className="px-6 py-4 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">User</th>
                                <th className="px-6 py-4 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Contact</th>
                                <th className="px-6 py-4 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Assigned Shop</th>
                                <th className="px-6 py-4 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Join Date</th>
                                <th className="px-6 py-4 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-slate-50 dark:divide-white/5 transition-colors">
                            {filteredUsers.map((user) => (
                                <tr key={user.id} className="hover:bg-slate-50/80 dark:hover:bg-white/5 transition-colors">
                                    <td className="px-6 py-4">
                                        <div className="flex items-center gap-3">
                                            <div className="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-600 dark:text-slate-400 font-bold border border-slate-200 dark:border-white/5 transition-colors">
                                                {user.name.charAt(0)}
                                            </div>
                                            <div>
                                                <span className="font-bold text-slate-800 dark:text-slate-200 block text-sm transition-colors">{user.name}</span>
                                                <div className="flex items-center gap-1.5">
                                                    <Shield className="w-3 h-3 text-indigo-500 dark:text-indigo-400" />
                                                    <span className="text-[10px] uppercase font-bold text-slate-400 dark:text-slate-500 tracking-wider">Member</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td className="px-6 py-4">
                                        <div className="flex items-center gap-1.5 text-sm text-slate-600 dark:text-slate-400 transition-colors">
                                            <Mail className="w-4 h-4 text-slate-400 dark:text-slate-500" />
                                            {user.email}
                                        </div>
                                    </td>
                                    <td className="px-6 py-4">
                                        {user.shop ? (
                                            <div className="flex items-center gap-2">
                                                <div className="px-2 py-0.5 rounded bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 text-xs font-bold border border-indigo-100 dark:border-indigo-800 uppercase tracking-tight transition-colors">
                                                    {user.shop.name}
                                                </div>
                                            </div>
                                        ) : (
                                            <span className="text-xs text-slate-400 dark:text-slate-500 italic font-medium transition-colors">No Shop Linked</span>
                                        )}
                                    </td>
                                    <td className="px-6 py-4">
                                        <div className="flex items-center gap-1.5 text-sm text-slate-600 dark:text-slate-400 transition-colors">
                                            <Calendar className="w-4 h-4 text-slate-400 dark:text-slate-500" />
                                            {new Date(user.created_at).toLocaleDateString()}
                                        </div>
                                    </td>
                                    <td className="px-6 py-4 text-right">
                                        <button className="p-2 text-slate-400 dark:text-slate-500 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors rounded-lg hover:bg-indigo-50 dark:hover:bg-indigo-900/20">
                                            <MoreHorizontal className="w-5 h-5" />
                                        </button>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
                
                <div className="px-6 py-4 border-t border-slate-100 dark:border-white/5 flex items-center justify-between text-sm transition-colors">
                    <p className="text-slate-500 dark:text-slate-400">Showing {filteredUsers.length} users</p>
                    <div className="flex gap-2">
                        <button className="text-slate-600 dark:text-slate-400 font-bold hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Previous</button>
                        <button className="text-slate-600 dark:text-slate-400 font-bold hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Next</button>
                    </div>
                </div>
            </div>
        </AdminLayout>
    );
}
