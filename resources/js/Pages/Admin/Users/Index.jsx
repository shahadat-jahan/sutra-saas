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

export default function Index({ users }) {
    return (
        <AdminLayout header="User Management">
            <Head title="Manage Users" />

            <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                <div>
                    <h1 className="text-2xl font-bold text-slate-900">Platform Users</h1>
                    <p className="text-slate-500 text-sm mt-1">Supervise all registered accounts across the platform.</p>
                </div>
                <button className="flex items-center gap-2 bg-indigo-600 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all active:scale-95">
                    <UserPlus className="w-4 h-4" />
                    Add New User
                </button>
            </div>

            <div className="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div className="p-4 border-b border-slate-100 flex items-center justify-between">
                    <div className="relative flex-1 max-w-md">
                        <Search className="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" />
                        <input 
                            type="text" 
                            placeholder="Find users by name, email or ID..." 
                            className="w-full pl-10 pr-4 py-2 bg-slate-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-indigo-500"
                        />
                    </div>
                </div>

                <div className="overflow-x-auto">
                    <table className="w-full text-left">
                        <thead>
                            <tr className="border-b border-slate-100">
                                <th className="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">User</th>
                                <th className="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Contact</th>
                                <th className="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Assigned Shop</th>
                                <th className="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Join Date</th>
                                <th className="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-slate-50">
                            {users.map((user) => (
                                <tr key={user.id} className="hover:bg-slate-50/80 transition-colors">
                                    <td className="px-6 py-4">
                                        <div className="flex items-center gap-3">
                                            <div className="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-bold border border-slate-200">
                                                {user.name.charAt(0)}
                                            </div>
                                            <div>
                                                <span className="font-bold text-slate-800 block text-sm">{user.name}</span>
                                                <div className="flex items-center gap-1.5">
                                                    <Shield className="w-3 h-3 text-indigo-500" />
                                                    <span className="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Member</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td className="px-6 py-4">
                                        <div className="flex items-center gap-1.5 text-sm text-slate-600">
                                            <Mail className="w-4 h-4 text-slate-400" />
                                            {user.email}
                                        </div>
                                    </td>
                                    <td className="px-6 py-4">
                                        {user.shop ? (
                                            <div className="flex items-center gap-2">
                                                <div className="px-2 py-0.5 rounded bg-indigo-50 text-indigo-600 text-xs font-bold border border-indigo-100 uppercase tracking-tight">
                                                    {user.shop.name}
                                                </div>
                                            </div>
                                        ) : (
                                            <span className="text-xs text-slate-400 italic font-medium">No Shop Linked</span>
                                        )}
                                    </td>
                                    <td className="px-6 py-4">
                                        <div className="flex items-center gap-1.5 text-sm text-slate-600">
                                            <Calendar className="w-4 h-4 text-slate-400" />
                                            {new Date(user.created_at).toLocaleDateString()}
                                        </div>
                                    </td>
                                    <td className="px-6 py-4 text-right">
                                        <button className="p-2 text-slate-400 hover:text-indigo-600 transition-colors rounded-lg hover:bg-indigo-50">
                                            <MoreHorizontal className="w-5 h-5" />
                                        </button>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
                
                <div className="px-6 py-4 border-t border-slate-100 flex items-center justify-between text-sm">
                    <p className="text-slate-500">Showing {users.length} users</p>
                    <div className="flex gap-2">
                        <button className="text-slate-600 font-bold hover:text-indigo-600">Previous</button>
                        <button className="text-slate-600 font-bold hover:text-indigo-600">Next</button>
                    </div>
                </div>
            </div>
        </AdminLayout>
    );
}
