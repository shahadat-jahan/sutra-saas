import AdminLayout from '@/Layouts/AdminLayout';
import Modal from '@/Components/Modal';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import SecondaryButton from '@/Components/SecondaryButton';
import { Head, useForm } from '@inertiajs/react';
import { CreditCard, History, Edit2, CheckCircle2, ShieldCheck, Zap, Crown } from 'lucide-react';
import { useState } from 'react';

export default function Index({ plans, available_modules }) {
    const [editingPlan, setEditingPlan] = useState(null);
    const [logPlan, setLogPlan] = useState(null);

    const { data, setData, patch, processing, errors, reset } = useForm({
        name: '',
        price_bdt: 0,
        price_usd: 0,
        features: [],
        is_active: true,
    });

    const openEdit = (plan) => {
        setEditingPlan(plan);
        setData({
            name: plan.name,
            price_bdt: plan.price_bdt,
            price_usd: plan.price_usd,
            features: plan.features || [],
            is_active: plan.is_active,
        });
    };

    const toggleFeature = (feature) => {
        if (data.features.includes(feature)) {
            setData('features', data.features.filter(f => f !== feature));
        } else {
            setData('features', [...data.features, feature]);
        }
    };

    const submit = (e) => {
        e.preventDefault();
        patch(route('admin.plans.update', editingPlan.id), {
            onSuccess: () => setEditingPlan(null),
        });
    };

    const getIcon = (slug) => {
        switch (slug) {
            case 'basic': return <Zap className="w-6 h-6 text-blue-500" />;
            case 'pro': return <Crown className="w-6 h-6 text-amber-500" />;
            case 'enterprise': return <ShieldCheck className="w-6 h-6 text-indigo-500" />;
            default: return <CreditCard className="w-6 h-6 text-slate-500" />;
        }
    };

    return (
        <AdminLayout header={<h2 className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">Subscription Plans</h2>}>
            <Head title="Subscription Plans" />

            <div className="mb-8">
                <h1 className="text-2xl font-bold text-slate-900 dark:text-white transition-colors">Pricing & Plans</h1>
                <p className="text-slate-500 dark:text-slate-400 text-sm mt-1 transition-colors">
                    Manage your platform's subscription tiers and regional pricing.
                </p>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                {plans.map((plan) => (
                    <div key={plan.id} className="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-white/10 shadow-sm overflow-hidden flex flex-col transition-all hover:shadow-md">
                        <div className="p-6 flex-1">
                            <div className="flex justify-between items-start mb-4">
                                <div className="p-3 rounded-2xl bg-slate-50 dark:bg-slate-800">
                                    {getIcon(plan.slug)}
                                </div>
                                <span className={`px-3 py-1 rounded-full text-xs font-bold ${
                                    plan.is_active 
                                    ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' 
                                    : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400'
                                }`}>
                                    {plan.is_active ? 'Active' : 'Inactive'}
                                </span>
                            </div>

                            <h3 className="text-xl font-bold text-slate-900 dark:text-white">{plan.name}</h3>
                            <p className="text-sm text-slate-500 dark:text-slate-400 mt-1 mb-6">
                                {plan.slug === 'basic' ? 'Essential tools for small businesses.' : plan.slug === 'pro' ? 'Advanced features for growing shops.' : 'Complete solution for large enterprises.'}
                            </p>

                            <div className="space-y-4 mb-8">
                                <div className="flex items-center justify-between p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50">
                                    <span className="text-sm font-medium text-slate-500 dark:text-slate-400">Bangladesh (BDT)</span>
                                    <span className="text-lg font-black text-slate-900 dark:text-white">৳{Number(plan.price_bdt).toLocaleString()}</span>
                                </div>
                                <div className="flex items-center justify-between p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50">
                                    <span className="text-sm font-medium text-slate-500 dark:text-slate-400">International (USD)</span>
                                    <span className="text-lg font-black text-slate-900 dark:text-white">${Number(plan.price_usd).toLocaleString()}</span>
                                </div>
                            </div>

                            <div className="space-y-3">
                                {plan.features?.map((feature, idx) => (
                                    <div key={idx} className="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-300">
                                        <CheckCircle2 className="w-4 h-4 text-emerald-500" />
                                        <span className="capitalize">{feature.replace('_', ' ')}</span>
                                    </div>
                                ))}
                            </div>
                        </div>

                        <div className="p-4 bg-slate-50 dark:bg-slate-800/30 border-t border-slate-100 dark:border-white/5 flex gap-2">
                            <button
                                onClick={() => openEdit(plan)}
                                className="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/10 text-sm font-bold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors"
                            >
                                <Edit2 className="w-4 h-4" />
                                Edit Price
                            </button>
                            <a
                                href={route('admin.plans.logs', plan.id)}
                                className="inline-flex items-center justify-center w-11 h-11 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/10 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors"
                                title="View Price History"
                            >
                                <History className="w-4 h-4" />
                            </a>
                        </div>
                    </div>
                ))}
            </div>

            <Modal show={Boolean(editingPlan)} onClose={() => setEditingPlan(null)} maxWidth="md">
                <form onSubmit={submit} className="p-8">
                    <h2 className="text-2xl font-black text-slate-900 dark:text-white mb-1">Update Plan</h2>
                    <p className="text-slate-500 text-sm mb-6">Editing {editingPlan?.name} plan details.</p>

                    <div className="space-y-5">
                        <div>
                            <InputLabel htmlFor="name" value="Plan Name" />
                            <input
                                id="name"
                                type="text"
                                className="w-full px-4 py-3 mt-1 rounded-2xl border-slate-200 dark:border-white/10 bg-white dark:bg-slate-800 dark:text-white focus:ring-indigo-500"
                                value={data.name}
                                onChange={(e) => setData('name', e.target.value)}
                            />
                            <InputError message={errors.name} className="mt-2" />
                        </div>

                        <div className="grid grid-cols-2 gap-4">
                            <div>
                                <InputLabel htmlFor="price_bdt" value="Price (BDT)" />
                                <div className="relative mt-1">
                                    <span className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold">৳</span>
                                    <input
                                        id="price_bdt"
                                        type="number"
                                        className="w-full pl-10 pr-4 py-3 rounded-2xl border-slate-200 dark:border-white/10 bg-white dark:bg-slate-800 dark:text-white focus:ring-indigo-500"
                                        value={data.price_bdt}
                                        onChange={(e) => setData('price_bdt', e.target.value)}
                                    />
                                </div>
                                <InputError message={errors.price_bdt} className="mt-2" />
                            </div>

                            <div>
                                <InputLabel htmlFor="price_usd" value="Price (USD)" />
                                <div className="relative mt-1">
                                    <span className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold">$</span>
                                    <input
                                        id="price_usd"
                                        type="number"
                                        className="w-full pl-10 pr-4 py-3 rounded-2xl border-slate-200 dark:border-white/10 bg-white dark:bg-slate-800 dark:text-white focus:ring-indigo-500"
                                        value={data.price_usd}
                                        onChange={(e) => setData('price_usd', e.target.value)}
                                    />
                                </div>
                                <InputError message={errors.price_usd} className="mt-2" />
                            </div>
                        </div>

                        <div>
                            <InputLabel value="Included Modules" />
                            <div className="grid grid-cols-1 gap-2 mt-2">
                                {Object.entries(available_modules || {}).map(([key, module]) => (
                                    <label key={key} className={`flex items-center justify-between p-3 rounded-xl border transition-colors cursor-pointer ${
                                        data.features.includes(key)
                                        ? 'bg-indigo-50 border-indigo-200 dark:bg-indigo-900/30 dark:border-indigo-800'
                                        : 'bg-white border-slate-200 dark:bg-slate-800 dark:border-white/10'
                                    }`}>
                                        <div className="flex items-center gap-2">
                                            <input
                                                type="checkbox"
                                                className="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                                checked={data.features.includes(key)}
                                                onChange={() => toggleFeature(key)}
                                            />
                                            <span className="text-sm font-semibold text-slate-700 dark:text-slate-200">{module.name}</span>
                                        </div>
                                        <div className="flex flex-col items-end">
                                            <span className="text-[10px] font-bold text-slate-500">৳{module.monthly_price_bdt}</span>
                                            <span className="text-[10px] text-slate-400">${module.monthly_price_usd}</span>
                                        </div>
                                    </label>
                                ))}
                                <label className={`flex items-center justify-between p-3 rounded-xl border transition-colors cursor-pointer ${
                                    data.features.includes('basic_reports')
                                    ? 'bg-indigo-50 border-indigo-200 dark:bg-indigo-900/30 dark:border-indigo-800'
                                    : 'bg-white border-slate-200 dark:bg-slate-800 dark:border-white/10'
                                }`}>
                                    <div className="flex items-center gap-2">
                                        <input
                                            type="checkbox"
                                            className="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                            checked={data.features.includes('basic_reports')}
                                            onChange={() => toggleFeature('basic_reports')}
                                        />
                                        <span className="text-sm font-semibold text-slate-700 dark:text-slate-200">Basic Reports</span>
                                    </div>
                                </label>
                                <label className={`flex items-center justify-between p-3 rounded-xl border transition-colors cursor-pointer ${
                                    data.features.includes('customization')
                                    ? 'bg-indigo-50 border-indigo-200 dark:bg-indigo-900/30 dark:border-indigo-800'
                                    : 'bg-white border-slate-200 dark:bg-slate-800 dark:border-white/10'
                                }`}>
                                    <div className="flex items-center gap-2">
                                        <input
                                            type="checkbox"
                                            className="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                            checked={data.features.includes('customization')}
                                            onChange={() => toggleFeature('customization')}
                                        />
                                        <span className="text-sm font-semibold text-slate-700 dark:text-slate-200">Customization</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div className="flex items-center gap-2">
                            <input
                                id="is_active"
                                type="checkbox"
                                className="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                checked={data.is_active}
                                onChange={(e) => setData('is_active', e.target.checked)}
                            />
                            <InputLabel htmlFor="is_active" value="Plan is active and visible to users" />
                        </div>
                    </div>

                    <div className="mt-8 flex gap-3">
                        <SecondaryButton className="flex-1 justify-center rounded-2xl py-3" onClick={() => setEditingPlan(null)}>
                            Cancel
                        </SecondaryButton>
                        <PrimaryButton className="flex-1 justify-center rounded-2xl py-3" disabled={processing}>
                            Save Changes
                        </PrimaryButton>
                    </div>
                </form>
            </Modal>
        </AdminLayout>
    );
}
