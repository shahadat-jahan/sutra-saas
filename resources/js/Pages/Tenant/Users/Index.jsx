import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';
import { 
    UserPlus, 
    Search, 
    MoreVertical, 
    Shield, 
    Trash2,
    Mail,
    User as UserIcon,
    ShieldCheck
} from 'lucide-react';
import { useState } from 'react';
import Modal from '@/Components/Modal';
import InputLabel from '@/Components/InputLabel';
import TextInput from '@/Components/TextInput';
import InputError from '@/Components/InputError';
import PrimaryButton from '@/Components/PrimaryButton';
import SecondaryButton from '@/Components/SecondaryButton';

export default function Index({ users }) {
    const [isCreateModalOpen, setIsCreateModalOpen] = useState(false);
    
    const { data, setData, post, processing, errors, reset, delete: destroy } = useForm({
        name: '',
        email: '',
        password: '',
        role: 'staff',
    });

    const submit = (e) => {
        e.preventDefault();
        post(route('tenant.users.store', { subdomain: window.location.host.split('.')[0] }), {
            onSuccess: () => {
                setIsCreateModalOpen(false);
                reset();
            },
        });
    };

    const handleDelete = (id) => {
        if (confirm('Are you sure you want to delete this user?')) {
            destroy(route('tenant.users.destroy', { 
                subdomain: window.location.host.split('.')[0],
                user: id 
            }));
        }
    };

    return (
        <AuthenticatedLayout header={<h2 className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">User Management</h2>}>
            <Head title="Staff Management" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {/* Header Actions */}
                    <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 px-4 sm:px-0">
                        <div>
                            <h1 className="text-3xl font-black text-slate-900 dark:text-white tracking-tight transition-colors">Staff & Roles</h1>
                            <p className="text-slate-500 dark:text-slate-400 mt-1 transition-colors">Manage your shop staff and their access levels.</p>
                        </div>
                        <button 
                            onClick={() => setIsCreateModalOpen(true)}
                            className="inline-flex items-center justify-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl transition-all shadow-lg shadow-indigo-600/20 active:scale-95"
                        >
                            <UserPlus className="w-5 h-5" />
                            Add New Staff
                        </button>
                    </div>

                    {/* Users List */}
                    <div className="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden transition-colors">
                        <div className="overflow-x-auto">
                            <table className="w-full text-left">
                                <thead>
                                    <tr className="bg-slate-50/50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800/80 italic transition-colors">
                                        <th className="px-8 py-5 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">User Details</th>
                                        <th className="px-8 py-5 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Role</th>
                                        <th className="px-8 py-5 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-slate-100 dark:divide-slate-800 transition-colors">
                                    {users.map((user) => (
                                        <tr key={user.id} className="group hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-all duration-300">
                                            <td className="px-8 py-6">
                                                <div className="flex items-center gap-4">
                                                    <div className="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-50 to-slate-100 dark:from-indigo-900/40 dark:to-slate-800 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-black border border-white dark:border-slate-700 shadow-sm group-hover:scale-110 transition-all duration-300">
                                                        {user.name.charAt(0).toUpperCase()}
                                                    </div>
                                                    <div>
                                                        <div className="font-bold text-slate-900 dark:text-slate-200 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{user.name}</div>
                                                        <div className="flex items-center gap-1.5 text-xs text-slate-400 dark:text-slate-500 mt-0.5">
                                                            <Mail className="w-3 h-3" />
                                                            {user.email}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td className="px-8 py-6">
                                                {user.roles && user.roles.length > 0 ? (
                                                    user.roles.map(role => (
                                                        <div key={role.id} className="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-white dark:bg-slate-800 text-indigo-700 dark:text-indigo-400 text-[10px] font-black border border-slate-200 dark:border-slate-700 uppercase tracking-tighter shadow-sm transition-colors">
                                                            <ShieldCheck className="w-3 h-3" />
                                                            {role.name.replace('-', ' ')}
                                                        </div>
                                                    ))
                                                ) : (
                                                    <div className="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-slate-100 dark:bg-slate-800/50 text-slate-400 dark:text-slate-500 text-[10px] font-black border border-slate-200 dark:border-slate-700/50 uppercase tracking-tighter shadow-sm transition-colors">
                                                        No Role
                                                    </div>
                                                )}
                                            </td>
                                            <td className="px-8 py-6 text-right">
                                                <div className="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                    <button 
                                                        onClick={() => handleDelete(user.id)}
                                                        className="p-2 text-slate-400 dark:text-slate-500 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-all active:scale-90"
                                                        title="Delete User"
                                                    >
                                                        <Trash2 className="w-5 h-5" />
                                                    </button>
                                                    <button className="p-2 text-slate-400 dark:text-slate-500 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-xl transition-all active:scale-90">
                                                        <MoreVertical className="w-5 h-5" />
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {/* Create User Modal */}
            <Modal show={isCreateModalOpen} onClose={() => setIsCreateModalOpen(false)}>
                <form onSubmit={submit} className="p-8 bg-white dark:bg-slate-900 transition-colors">
                    <h2 className="text-2xl font-black text-slate-900 dark:text-white mb-6 tracking-tight">Add New Staff Member</h2>
                    
                    <div className="space-y-6">
                        <div>
                            <InputLabel htmlFor="name" value="Full Name" className="dark:text-slate-300" />
                            <TextInput
                                id="name"
                                value={data.name}
                                onChange={(e) => setData('name', e.target.value)}
                                type="text"
                                className="mt-1 block w-full dark:bg-slate-800 dark:border-slate-700 dark:text-white"
                                required
                            />
                            <InputError message={errors.name} className="mt-2" />
                        </div>

                        <div>
                            <InputLabel htmlFor="email" value="Email Address" className="dark:text-slate-300" />
                            <TextInput
                                id="email"
                                value={data.email}
                                onChange={(e) => setData('email', e.target.value)}
                                type="email"
                                className="mt-1 block w-full dark:bg-slate-800 dark:border-slate-700 dark:text-white"
                                required
                            />
                            <InputError message={errors.email} className="mt-2" />
                        </div>

                        <div>
                            <InputLabel htmlFor="password" value="Initial Password" className="dark:text-slate-300" />
                            <TextInput
                                id="password"
                                value={data.password}
                                onChange={(e) => setData('password', e.target.value)}
                                type="password"
                                className="mt-1 block w-full dark:bg-slate-800 dark:border-slate-700 dark:text-white"
                                required
                            />
                            <InputError message={errors.password} className="mt-2" />
                        </div>

                        <div>
                            <InputLabel htmlFor="role" value="Access Level" className="dark:text-slate-300" />
                            <select
                                id="role"
                                value={data.role}
                                onChange={(e) => setData('role', e.target.value)}
                                className="mt-1 block w-full border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition-all"
                            >
                                <option value="staff">Staff Member</option>
                                <option value="shop-owner">Shop Manager (Full Access)</option>
                            </select>
                            <InputError message={errors.role} className="mt-2" />
                        </div>
                    </div>

                    <div className="mt-8 flex justify-end gap-3">
                        <SecondaryButton onClick={() => setIsCreateModalOpen(false)}>
                            Cancel
                        </SecondaryButton>
                        <PrimaryButton className="px-8" disabled={processing}>
                            Create Account
                        </PrimaryButton>
                    </div>
                </form>
            </Modal>
        </AuthenticatedLayout>
    );
}
