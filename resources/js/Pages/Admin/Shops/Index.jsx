import AdminLayout from '@/Layouts/AdminLayout';
import Modal from '@/Components/Modal';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import SecondaryButton from '@/Components/SecondaryButton';
import { Head, router, useForm, usePage } from '@inertiajs/react';
import { Search, ExternalLink, CheckCircle2, XCircle, Store, Plus, Settings2 } from 'lucide-react';
import { useMemo, useState } from 'react';

export default function Index({ shops, module_catalog }) {
    const { business_types, appDomain } = usePage().props;
    const [query, setQuery] = useState('');
    const [isCreateOpen, setIsCreateOpen] = useState(false);
    const [editingShop, setEditingShop] = useState(null);

    const moduleOptions = Object.entries(module_catalog || {}).map(([key, value]) => ({
        key,
        name: value.name,
        monthly_price: Number(value.monthly_price || 0),
    }));

    const createForm = useForm({
        shop_name: '',
        business_type: business_types?.[0]?.value ?? 1,
        enabled_modules: ['pos'],
        is_free: false,
        status: 1,
        owner_name: '',
        owner_email: '',
        owner_password: '',
    });

    const updateForm = useForm({
        enabled_modules: ['pos'],
        is_free: false,
        status: 1,
    });

    const handleStatusToggle = (shop) => {
        const newStatus = shop.status === 1 ? 0 : 1;
        router.patch(route('admin.shops.update', shop.uuid), {
            status: newStatus,
        }, { preserveScroll: true });
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

    const toggleModule = (form, moduleKey) => {
        if (moduleKey === 'pos') {
            return;
        }

        if (form.data.enabled_modules.includes(moduleKey)) {
            form.setData('enabled_modules', form.data.enabled_modules.filter((m) => m !== moduleKey));
            return;
        }

        form.setData('enabled_modules', [...form.data.enabled_modules, moduleKey]);
    };

    const openEditModules = (shop) => {
        setEditingShop(shop);
        updateForm.clearErrors();
        updateForm.setData({
            enabled_modules: shop.enabled_modules?.length ? shop.enabled_modules : ['pos'],
            is_free: Boolean(shop.is_free),
            status: shop.status,
        });
    };

    const submitCreate = (e) => {
        e.preventDefault();
        createForm.post(route('admin.shops.store'), {
            preserveScroll: true,
            onSuccess: () => {
                setIsCreateOpen(false);
                createForm.reset();
                createForm.setData('enabled_modules', ['pos']);
            },
        });
    };

    const submitUpdateModules = (e) => {
        e.preventDefault();
        if (!editingShop) return;
        updateForm.patch(route('admin.shops.update', editingShop.uuid), {
            preserveScroll: true,
            onSuccess: () => setEditingShop(null),
        });
    };

    return (
        <AdminLayout header={<h2 className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">Shops Management</h2>}>
            <Head title="Manage Shops" />

            <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                <div>
                    <h1 className="text-2xl font-bold text-slate-900 dark:text-white transition-colors">Registered Shops</h1>
                    <p className="text-slate-500 dark:text-slate-400 text-sm mt-1 transition-colors">
                        Module-based billing with optional free access override.
                    </p>
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
                        onClick={() => setIsCreateOpen(true)}
                        className="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-600 text-white text-sm font-bold hover:bg-indigo-700 transition-colors"
                    >
                        <Plus className="w-4 h-4" />
                        New Shop
                    </button>
                </div>
            </div>

            <div className="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/10 shadow-sm overflow-hidden transition-colors">
                <div className="overflow-x-auto">
                    <table className="w-full text-left border-collapse">
                        <thead>
                            <tr className="bg-slate-50 dark:bg-slate-800 border-b border-slate-200 dark:border-white/5 transition-colors">
                                <th className="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Shop Name</th>
                                <th className="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Modules</th>
                                <th className="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Monthly Bill</th>
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
                                                <a
                                                    href={getTenantUrl(shop)}
                                                    target="_blank"
                                                    rel="noreferrer"
                                                    className="inline-flex items-center gap-1 text-xs text-indigo-600 dark:text-indigo-400 hover:underline"
                                                >
                                                    {shop.slug}.{appDomain || window.location.hostname}
                                                    <ExternalLink className="w-3 h-3" />
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td className="px-6 py-4">
                                        <div className="flex flex-wrap gap-2">
                                            {(shop.enabled_modules || []).map((moduleKey) => (
                                                <span key={moduleKey} className="px-2.5 py-1 text-xs rounded-full border border-indigo-200 bg-indigo-50 text-indigo-700 dark:border-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-300">
                                                    {(module_catalog?.[moduleKey]?.name || moduleKey)}
                                                </span>
                                            ))}
                                        </div>
                                    </td>
                                    <td className="px-6 py-4">
                                        {shop.is_free ? (
                                            <span className="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-300 dark:border-emerald-800">
                                                Free Access
                                            </span>
                                        ) : (
                                            <span className="text-sm font-bold text-slate-700 dark:text-slate-300">
                                                BDT {Number(shop.monthly_price || 0).toLocaleString()}/month
                                            </span>
                                        )}
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
                                                onClick={() => openEditModules(shop)}
                                                className="inline-flex items-center gap-1 text-xs font-bold px-3 py-1.5 rounded-lg bg-indigo-50 text-indigo-700 hover:bg-indigo-100 dark:bg-indigo-900/40 dark:text-indigo-300"
                                            >
                                                <Settings2 className="w-3.5 h-3.5" />
                                                Modules
                                            </button>
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
            </div>

            <Modal show={isCreateOpen} onClose={() => setIsCreateOpen(false)} maxWidth="2xl">
                <form onSubmit={submitCreate} className="p-8">
                    <h2 className="text-2xl font-black text-slate-900 dark:text-slate-100 mb-6">Create Shop</h2>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <InputLabel htmlFor="shop_name" value="Shop Name" />
                            <input id="shop_name" className="mt-1 block w-full rounded-xl border-slate-300" value={createForm.data.shop_name} onChange={(e) => createForm.setData('shop_name', e.target.value)} />
                            <InputError message={createForm.errors.shop_name} className="mt-2" />
                        </div>
                        <div>
                            <InputLabel htmlFor="business_type" value="Business Type" />
                            <select id="business_type" className="mt-1 block w-full rounded-xl border-slate-300" value={createForm.data.business_type} onChange={(e) => createForm.setData('business_type', Number(e.target.value))}>
                                {business_types.map((type) => <option key={type.value} value={type.value}>{type.label}</option>)}
                            </select>
                            <InputError message={createForm.errors.business_type} className="mt-2" />
                        </div>
                        <div>
                            <InputLabel htmlFor="owner_name" value="Owner Name" />
                            <input id="owner_name" className="mt-1 block w-full rounded-xl border-slate-300" value={createForm.data.owner_name} onChange={(e) => createForm.setData('owner_name', e.target.value)} />
                            <InputError message={createForm.errors.owner_name} className="mt-2" />
                        </div>
                        <div>
                            <InputLabel htmlFor="owner_email" value="Owner Email" />
                            <input id="owner_email" type="email" className="mt-1 block w-full rounded-xl border-slate-300" value={createForm.data.owner_email} onChange={(e) => createForm.setData('owner_email', e.target.value)} />
                            <InputError message={createForm.errors.owner_email} className="mt-2" />
                        </div>
                    </div>

                    <div className="mt-5">
                        <InputLabel value="Modules (POS Mandatory)" />
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-3 mt-2">
                            {moduleOptions.map((module) => {
                                const checked = createForm.data.enabled_modules.includes(module.key);
                                const isPos = module.key === 'pos';
                                return (
                                    <label key={module.key} className={`border rounded-xl px-3 py-2 ${checked ? 'border-indigo-300 bg-indigo-50' : 'border-slate-200 bg-white'}`}>
                                        <div className="flex items-center justify-between gap-2">
                                            <div className="flex items-center gap-2">
                                                <input type="checkbox" checked={checked} disabled={isPos} onChange={() => toggleModule(createForm, module.key)} />
                                                <span className="text-sm font-semibold">{module.name}{isPos ? ' (Mandatory)' : ''}</span>
                                            </div>
                                            <span className="text-xs text-slate-600">BDT {module.monthly_price}</span>
                                        </div>
                                    </label>
                                );
                            })}
                        </div>
                        <InputError message={createForm.errors.enabled_modules} className="mt-2" />
                    </div>

                    <label className="mt-4 inline-flex items-center gap-2 text-sm">
                        <input type="checkbox" checked={createForm.data.is_free} onChange={(e) => createForm.setData('is_free', e.target.checked)} />
                        Allow Free Access (no monthly billing)
                    </label>

                    <div className="mt-8 flex justify-end gap-3">
                        <SecondaryButton onClick={() => setIsCreateOpen(false)}>Cancel</SecondaryButton>
                        <PrimaryButton disabled={createForm.processing}>Create Shop</PrimaryButton>
                    </div>
                </form>
            </Modal>

            <Modal show={Boolean(editingShop)} onClose={() => setEditingShop(null)}>
                <form onSubmit={submitUpdateModules} className="p-8">
                    <h2 className="text-xl font-black text-slate-900 dark:text-slate-100 mb-5">
                        Update Modules {editingShop ? `- ${editingShop.name}` : ''}
                    </h2>
                    <div className="space-y-3">
                        {moduleOptions.map((module) => {
                            const checked = updateForm.data.enabled_modules.includes(module.key);
                            const isPos = module.key === 'pos';
                            return (
                                <label key={module.key} className={`border rounded-xl px-3 py-2 flex items-center justify-between ${checked ? 'border-indigo-300 bg-indigo-50' : 'border-slate-200 bg-white'}`}>
                                    <div className="flex items-center gap-2">
                                        <input type="checkbox" checked={checked} disabled={isPos} onChange={() => toggleModule(updateForm, module.key)} />
                                        <span className="text-sm font-semibold">{module.name}{isPos ? ' (Mandatory)' : ''}</span>
                                    </div>
                                    <span className="text-xs text-slate-600">BDT {module.monthly_price}/month</span>
                                </label>
                            );
                        })}
                    </div>
                    <InputError message={updateForm.errors.enabled_modules} className="mt-2" />

                    <label className="mt-4 inline-flex items-center gap-2 text-sm">
                        <input type="checkbox" checked={updateForm.data.is_free} onChange={(e) => updateForm.setData('is_free', e.target.checked)} />
                        Allow Free Access
                    </label>

                    <div className="mt-8 flex justify-end gap-3">
                        <SecondaryButton onClick={() => setEditingShop(null)}>Cancel</SecondaryButton>
                        <PrimaryButton disabled={updateForm.processing}>Save Changes</PrimaryButton>
                    </div>
                </form>
            </Modal>
        </AdminLayout>
    );
}
