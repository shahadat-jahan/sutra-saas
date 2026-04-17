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
        <AuthenticatedLayout header="User Management">
            <Head title="Staff Management" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {/* Header Actions */}
                    <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 px-4 sm:px-0">
                        <div>
                            <h1 className="text-3xl font-black text-slate-900 tracking-tight">Staff & Roles</h1>
                            <p className="text-slate-500 mt-1">Manage your shop staff and their access levels.</p>
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
                    <div className="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                        <div className="overflow-x-auto">
                            <table className="w-full text-left">
                                <thead>
                                    <tr className="bg-slate-50/50 border-b border-slate-100 italic">
                                        <th className="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">User Details</th>
                                        <th className="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">Role</th>
                                        <th className="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-slate-100">
                                    {users.map((user) => (
                                        <tr key={user.id} className="hover:bg-slate-50/30 transition-colors">
                                            <td className="px-8 py-6">
                                                <div className="flex items-center gap-4">
                                                    <div className="w-12 h-12 rounded-2xl bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center text-slate-500 font-bold border border-white">
                                                        {user.name.charAt(0)}
                                                    </div>
                                                    <div>
                                                        <div className="font-bold text-slate-900">{user.name}</div>
                                                        <div className="flex items-center gap-1.5 text-xs text-slate-400 mt-0.5">
                                                            <Mail className="w-3 h-3" />
                                                            {user.email}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td className="px-8 py-6">
                                                <div className="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs font-bold border border-indigo-100 uppercase tracking-wider">
                                                    <ShieldCheck className="w-3.5 h-3.5" />
                                                    {user.id === 1 ? 'Owner' : 'Staff'}
                                                </div>
                                            </td>
                                            <td className="px-8 py-6 text-right">
                                                <div className="flex items-center justify-end gap-3">
                                                    <button 
                                                        onClick={() => handleDelete(user.id)}
                                                        className="p-2.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all"
                                                    >
                                                        <Trash2 className="w-5 h-5" />
                                                    </button>
                                                    <button className="p-2.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all">
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
                <form onSubmit={submit} className="p-8">
                    <h2 className="text-2xl font-black text-slate-900 mb-6 tracking-tight">Add New Staff Member</h2>
                    
                    <div className="space-y-6">
                        <div>
                            <InputLabel htmlFor="name" value="Full Name" />
                            <TextInput
                                id="name"
                                value={data.name}
                                onChange={(e) => setData('name', e.target.value)}
                                type="text"
                                className="mt-1 block w-full"
                                required
                            />
                            <InputError message={errors.name} className="mt-2" />
                        </div>

                        <div>
                            <InputLabel htmlFor="email" value="Email Address" />
                            <TextInput
                                id="email"
                                value={data.email}
                                onChange={(e) => setData('email', e.target.value)}
                                type="email"
                                className="mt-1 block w-full"
                                required
                            />
                            <InputError message={errors.email} className="mt-2" />
                        </div>

                        <div>
                            <InputLabel htmlFor="password" value="Initial Password" />
                            <TextInput
                                id="password"
                                value={data.password}
                                onChange={(e) => setData('password', e.target.value)}
                                type="password"
                                className="mt-1 block w-full"
                                required
                            />
                            <InputError message={errors.password} className="mt-2" />
                        </div>

                        <div>
                            <InputLabel htmlFor="role" value="Access Level" />
                            <select
                                id="role"
                                value={data.role}
                                onChange={(e) => setData('role', e.target.value)}
                                className="mt-1 block w-full border-slate-200 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition-all"
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
