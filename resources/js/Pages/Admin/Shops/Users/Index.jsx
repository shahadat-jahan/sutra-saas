import AdminLayout from '@/Layouts/AdminLayout';
import { Head, Link, useForm } from '@inertiajs/react';
import { Mail, Plus, Trash2, Pencil, ArrowLeft } from 'lucide-react';
import Modal from '@/Components/Modal';
import InputLabel from '@/Components/InputLabel';
import TextInput from '@/Components/TextInput';
import InputError from '@/Components/InputError';
import PrimaryButton from '@/Components/PrimaryButton';
import SecondaryButton from '@/Components/SecondaryButton';
import { useMemo, useState } from 'react';

export default function Index({ shop, users, roles }) {
    const [isCreateOpen, setIsCreateOpen] = useState(false);
    const [editingUser, setEditingUser] = useState(null);

    const roleOptions = useMemo(() => roles?.map((r) => r.name) ?? [], [roles]);

    const {
        data,
        setData,
        post,
        patch,
        delete: destroy,
        processing,
        errors,
        reset,
        clearErrors,
    } = useForm({
        name: '',
        email: '',
        password: '',
        role: roleOptions[0] ?? 'staff',
    });

    const openCreate = () => {
        clearErrors();
        reset();
        setData('role', roleOptions[0] ?? 'staff');
        setEditingUser(null);
        setIsCreateOpen(true);
    };

    const openEdit = (user) => {
        clearErrors();
        setEditingUser(user);
        setData({
            name: user.name,
            email: user.email,
            password: '',
            role: user.roles?.[0]?.name ?? (roleOptions[0] ?? 'staff'),
        });
        setIsCreateOpen(true);
    };

    const submit = (e) => {
        e.preventDefault();
        if (editingUser) {
            patch(route('admin.shops.users.update', { shop: shop.uuid, user: editingUser.uuid }), {
                preserveScroll: true,
                onSuccess: () => {
                    setIsCreateOpen(false);
                    setEditingUser(null);
                    reset();
                },
            });
            return;
        }

        post(route('admin.shops.users.store', { shop: shop.uuid }), {
            preserveScroll: true,
            onSuccess: () => {
                setIsCreateOpen(false);
                reset();
            },
        });
    };

    const handleDelete = (user) => {
        if (!confirm(`Delete ${user.name}?`)) return;
        destroy(route('admin.shops.users.destroy', { shop: shop.uuid, user: user.uuid }), {
            preserveScroll: true,
        });
    };

    return (
        <AdminLayout header={`Shop Users — ${shop.name}`}>
            <Head title={`Users — ${shop.name}`} />

            <div className="flex items-center justify-between mb-8 gap-4">
                <div>
                    <div className="flex items-center gap-3">
                        <Link
                            href={route('admin.shops.index')}
                            className="inline-flex items-center gap-2 text-slate-600 hover:text-slate-900 font-bold text-sm"
                        >
                            <ArrowLeft className="w-4 h-4" />
                            Back
                        </Link>
                        <span className="text-slate-300">/</span>
                        <span className="text-sm font-bold text-slate-600">{shop.slug}</span>
                    </div>
                    <h1 className="text-2xl font-bold text-slate-900 mt-2">Shop Users</h1>
                    <p className="text-slate-500 text-sm mt-1">Create, edit, and remove users for this shop.</p>
                </div>

                <div className="flex items-center gap-2">
                    <Link
                        href={route('admin.shops.roles.index', { shop: shop.uuid })}
                        className="px-4 py-2 rounded-xl text-sm font-bold border border-slate-200 text-slate-600 hover:bg-slate-50"
                    >
                        Manage Roles
                    </Link>
                    <button
                        type="button"
                        onClick={openCreate}
                        className="inline-flex items-center gap-2 bg-indigo-600 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-xl shadow-indigo-100 hover:bg-indigo-700 transition-all"
                    >
                        <Plus className="w-4 h-4" />
                        New User
                    </button>
                </div>
            </div>

            <div className="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div className="overflow-x-auto">
                    <table className="w-full text-left">
                        <thead>
                            <tr className="bg-slate-50 border-b border-slate-200">
                                <th className="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">User</th>
                                <th className="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Role</th>
                                <th className="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Created</th>
                                <th className="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-slate-100">
                            {users?.length ? users.map((u) => (
                                <tr key={u.id} className="hover:bg-slate-50/50 transition-colors">
                                    <td className="px-6 py-4">
                                        <div className="flex items-center gap-3">
                                            <div className="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-700 font-bold border border-slate-200">
                                                {u.name?.charAt(0)?.toUpperCase()}
                                            </div>
                                            <div>
                                                <div className="font-bold text-slate-900">{u.name}</div>
                                                <div className="flex items-center gap-1.5 text-sm text-slate-600">
                                                    <Mail className="w-4 h-4 text-slate-400" />
                                                    {u.email}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td className="px-6 py-4">
                                        <span className="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                            {u.roles?.[0]?.name ?? '—'}
                                        </span>
                                    </td>
                                    <td className="px-6 py-4 text-sm text-slate-600">
                                        {u.created_at ? new Date(u.created_at).toLocaleString() : '—'}
                                    </td>
                                    <td className="px-6 py-4 text-right">
                                        <div className="inline-flex items-center gap-2">
                                            <button
                                                type="button"
                                                onClick={() => openEdit(u)}
                                                className="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all"
                                                title="Edit"
                                            >
                                                <Pencil className="w-5 h-5" />
                                            </button>
                                            <button
                                                type="button"
                                                onClick={() => handleDelete(u)}
                                                className="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all"
                                                title="Delete"
                                            >
                                                <Trash2 className="w-5 h-5" />
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            )) : (
                                <tr>
                                    <td colSpan="4" className="px-6 py-12 text-center text-slate-500">
                                        No users found.
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>

            <Modal show={isCreateOpen} onClose={() => setIsCreateOpen(false)}>
                <form onSubmit={submit} className="p-8">
                    <h2 className="text-2xl font-black text-slate-900 mb-6 tracking-tight">
                        {editingUser ? 'Edit User' : 'Create User'}
                    </h2>

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
                            <InputLabel htmlFor="password" value={editingUser ? 'New Password (optional)' : 'Initial Password (optional)'} />
                            <TextInput
                                id="password"
                                value={data.password}
                                onChange={(e) => setData('password', e.target.value)}
                                type="password"
                                className="mt-1 block w-full"
                            />
                            <p className="text-xs text-slate-500 mt-1">
                                Leave blank to auto-generate a secure password (sent by email).
                            </p>
                            <InputError message={errors.password} className="mt-2" />
                        </div>

                        <div>
                            <InputLabel htmlFor="role" value="Role" />
                            <select
                                id="role"
                                value={data.role}
                                onChange={(e) => setData('role', e.target.value)}
                                className="mt-1 block w-full border-slate-200 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition-all"
                            >
                                {roleOptions.map((r) => (
                                    <option key={r} value={r}>{r}</option>
                                ))}
                            </select>
                            <InputError message={errors.role} className="mt-2" />
                        </div>
                    </div>

                    <div className="mt-8 flex justify-end gap-3">
                        <SecondaryButton onClick={() => setIsCreateOpen(false)}>
                            Cancel
                        </SecondaryButton>
                        <PrimaryButton className="px-8" disabled={processing}>
                            {editingUser ? 'Save' : 'Create'}
                        </PrimaryButton>
                    </div>
                </form>
            </Modal>
        </AdminLayout>
    );
}

