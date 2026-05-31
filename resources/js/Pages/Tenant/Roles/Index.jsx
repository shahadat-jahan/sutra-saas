import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';
import { 
    Shield, 
    Plus, 
    MoreVertical, 
    Trash2,
    ShieldCheck,
    CheckCircle2
} from 'lucide-react';
import { useState } from 'react';
import Modal from '@/Components/Modal';
import InputLabel from '@/Components/InputLabel';
import TextInput from '@/Components/TextInput';
import InputError from '@/Components/InputError';
import PrimaryButton from '@/Components/PrimaryButton';
import SecondaryButton from '@/Components/SecondaryButton';

export default function Index({ roles, permissions }) {
    const [isCreateModalOpen, setIsCreateModalOpen] = useState(false);
    
    const { data, setData, post, processing, errors, reset, delete: destroy } = useForm({
        name: '',
        permissions: [],
    });

    const submit = (e) => {
        e.preventDefault();
        post(route('tenant.roles.store', { subdomain: window.location.host.split('.')[0] }), {
            onSuccess: () => {
                setIsCreateModalOpen(false);
                reset();
            },
        });
    };

    const handleDelete = (id) => {
        if (confirm('Are you sure you want to delete this role?')) {
            destroy(route('tenant.roles.destroy', { 
                subdomain: window.location.host.split('.')[0],
                role: id 
            }));
        }
    };

    const togglePermission = (permissionName) => {
        const currentPermissions = [...data.permissions];
        if (currentPermissions.includes(permissionName)) {
            setData('permissions', currentPermissions.filter(p => p !== permissionName));
        } else {
            setData('permissions', [...currentPermissions, permissionName]);
        }
    };

    return (
        <AuthenticatedLayout header={<h2 className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">Role Management</h2>}>
            <Head title="Role Management" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {/* Header Actions */}
                    <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 px-4 sm:px-0">
                        <div>
                            <h1 className="text-3xl font-black text-slate-900 dark:text-white tracking-tight transition-colors">Shop Roles</h1>
                            <p className="text-slate-500 dark:text-slate-400 mt-1 transition-colors">Manage roles and permissions for your staff.</p>
                        </div>
                        <button 
                            onClick={() => setIsCreateModalOpen(true)}
                            className="inline-flex items-center justify-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl transition-all shadow-lg shadow-indigo-600/20 active:scale-95"
                        >
                            <Plus className="w-5 h-5" />
                            Add New Role
                        </button>
                    </div>

                    {/* Roles List */}
                    <div className="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden transition-colors">
                        <div className="overflow-x-auto">
                            <table className="w-full text-left">
                                <thead>
                                    <tr className="bg-slate-50/50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800/80 italic transition-colors">
                                        <th className="px-8 py-5 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Role Name</th>
                                        <th className="px-8 py-5 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Permissions</th>
                                        <th className="px-8 py-5 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-slate-100 dark:divide-slate-800 transition-colors">
                                    {roles.map((role) => (
                                        <tr key={role.id} className="group hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-all duration-300">
                                            <td className="px-8 py-6">
                                                <div className="flex items-center gap-4">
                                                    <div className="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-50 to-slate-100 dark:from-indigo-900/40 dark:to-slate-800 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-black border border-white dark:border-slate-700 shadow-sm group-hover:scale-110 transition-all duration-300">
                                                        <Shield className="w-6 h-6" />
                                                    </div>
                                                    <div>
                                                        <div className="font-bold text-slate-900 dark:text-slate-200 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors capitalize">
                                                            {role.name.replace('-', ' ')}
                                                        </div>
                                                        {role.team_id === null && (
                                                            <span className="inline-block mt-1 px-2 py-0.5 text-[10px] font-bold bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 rounded-full uppercase">Global Role</span>
                                                        )}
                                                    </div>
                                                </div>
                                            </td>
                                            <td className="px-8 py-6 max-w-md">
                                                <div className="flex flex-wrap gap-2">
                                                {role.permissions && role.permissions.length > 0 ? (
                                                    role.permissions.map((permission, index) => (
                                                        <span key={index} className="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-medium border border-slate-200 dark:border-slate-700 shadow-sm transition-colors">
                                                            <CheckCircle2 className="w-3 h-3 text-indigo-500" />
                                                            {permission}
                                                        </span>
                                                    ))
                                                ) : (
                                                    <span className="text-sm text-slate-400 dark:text-slate-500 italic">No specific permissions assigned</span>
                                                )}
                                                </div>
                                            </td>
                                            <td className="px-8 py-6 text-right">
                                                <div className={`flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 ${role.team_id === null ? 'invisible' : ''}`}>
                                                    <button 
                                                        onClick={() => handleDelete(role.id)}
                                                        className="p-2 text-slate-400 dark:text-slate-500 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-all active:scale-90"
                                                        title="Delete Role"
                                                    >
                                                        <Trash2 className="w-5 h-5" />
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

            {/* Create Role Modal */}
            <Modal show={isCreateModalOpen} onClose={() => setIsCreateModalOpen(false)} maxWidth="2xl">
                <form onSubmit={submit} className="p-8 bg-white dark:bg-slate-900 transition-colors">
                    <h2 className="text-2xl font-black text-slate-900 dark:text-white mb-6 tracking-tight">Create Custom Role</h2>
                    
                    <div className="space-y-6">
                        <div>
                            <InputLabel htmlFor="name" value="Role Name" className="dark:text-slate-300" />
                            <TextInput
                                id="name"
                                value={data.name}
                                onChange={(e) => setData('name', e.target.value)}
                                type="text"
                                className="mt-1 block w-full dark:bg-slate-800 dark:border-slate-700 dark:text-white"
                                required
                                placeholder="e.g. Cashier, Manager"
                            />
                            <InputError message={errors.name} className="mt-2" />
                        </div>

                        <div>
                            <InputLabel value="Permissions" className="mb-3 dark:text-slate-300" />
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-60 overflow-y-auto p-4 border border-slate-200 dark:border-slate-700 rounded-xl bg-slate-50 dark:bg-slate-800/50">
                                {permissions.map((permission) => (
                                    <label key={permission} className="flex items-center gap-3 p-2 rounded-lg hover:bg-white dark:hover:bg-slate-800 cursor-pointer transition-colors border border-transparent hover:border-slate-200 dark:hover:border-slate-700">
                                        <input
                                            type="checkbox"
                                            className="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-900"
                                            checked={data.permissions.includes(permission)}
                                            onChange={() => togglePermission(permission)}
                                        />
                                        <span className="text-sm font-medium text-slate-700 dark:text-slate-300 capitalize">{permission.replace(/_/g, ' ')}</span>
                                    </label>
                                ))}
                            </div>
                            <InputError message={errors.permissions} className="mt-2" />
                        </div>
                    </div>

                    <div className="mt-8 flex justify-end gap-3">
                        <SecondaryButton onClick={() => setIsCreateModalOpen(false)}>
                            Cancel
                        </SecondaryButton>
                        <PrimaryButton className="px-8" disabled={processing}>
                            Create Role
                        </PrimaryButton>
                    </div>
                </form>
            </Modal>
        </AuthenticatedLayout>
    );
}
