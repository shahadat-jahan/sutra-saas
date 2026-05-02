import AdminLayout from '@/Layouts/AdminLayout';
import InputError from '@/Components/InputError';
import { Head, useForm, usePage } from '@inertiajs/react';
import { Globe, CreditCard, Save, Bell } from 'lucide-react';
import { useState } from 'react';

export default function Index() {
    const { appName, module_catalog } = usePage().props;
    const [activeTab, setActiveTab] = useState('billing');
    const moduleEntries = Object.entries(module_catalog || {});

    const initialModules = moduleEntries.reduce((acc, [moduleKey, moduleConfig]) => {
        acc[moduleKey] = { 
            monthly_price_bdt: Number(moduleConfig.monthly_price_bdt || 0),
            monthly_price_usd: Number(moduleConfig.monthly_price_usd || 0),
        };
        return acc;
    }, {});

    const { data, setData, patch, processing, errors } = useForm({
        modules: initialModules,
    });

    const tabs = [
        { id: 'general', name: 'General', icon: Globe },
        { id: 'notifications', name: 'Notifications', icon: Bell },
        { id: 'billing', name: 'Billing Config', icon: CreditCard },
    ];

    const submitPricing = (e) => {
        e.preventDefault();
        patch(route('admin.settings.module-pricing.update'), {
            preserveScroll: true,
        });
    };

    return (
        <AdminLayout header={<h2 className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">Settings</h2>}>
            <Head title="Platform Settings" />

            <div className="flex flex-col lg:flex-row gap-8">
                {/* Sidebar Navigation */}
                <div className="w-full lg:w-64 flex flex-col gap-1">
                    {tabs.map((tab) => (
                        <button
                            key={tab.id}
                            onClick={() => setActiveTab(tab.id)}
                            className={`flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-bold text-sm ${
                                activeTab === tab.id 
                                ? 'bg-indigo-600 text-white shadow-xl shadow-indigo-100 dark:shadow-none' 
                                : 'text-slate-500 dark:text-slate-400 hover:bg-white dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-slate-200 border border-transparent hover:border-slate-200 dark:hover:border-white/10'
                            }`}
                        >
                            <tab.icon className="w-4 h-4" />
                            {tab.name}
                        </button>
                    ))}
                </div>

                {/* Main Settings Form */}
                <div className="flex-1 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/10 shadow-sm overflow-hidden transition-colors">
                    <div className="p-8">
                        {activeTab !== 'billing' ? (
                            <div>
                                <h2 className="text-xl font-bold text-slate-900 dark:text-white transition-colors">
                                    {activeTab === 'general' ? 'General Settings' : 'Notifications'}
                                </h2>
                                <p className="text-slate-500 dark:text-slate-400 text-sm transition-colors mt-1">
                                    {activeTab === 'general'
                                        ? `Platform name: ${appName}. More general settings can be added later.`
                                        : 'Notification settings can be expanded later.'}
                                </p>
                            </div>
                        ) : (
                            <form onSubmit={submitPricing}>
                                <div className="mb-8">
                                    <h2 className="text-xl font-bold text-slate-900 dark:text-white transition-colors">Module Pricing (Monthly)</h2>
                                    <p className="text-slate-500 dark:text-slate-400 text-sm transition-colors">
                                        Update monthly price per module. POS remains mandatory in shop subscriptions.
                                    </p>
                                </div>

                                <div className="space-y-4 max-w-3xl">
                                    {moduleEntries.map(([moduleKey, moduleConfig]) => (
                                        <div
                                            key={moduleKey}
                                            className="rounded-xl border border-slate-200 dark:border-white/10 p-4 bg-slate-50 dark:bg-slate-800/50"
                                        >
                                            <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                                <div>
                                                    <h3 className="font-bold text-slate-900 dark:text-slate-100">
                                                        {moduleConfig.name}
                                                    </h3>
                                                    <p className="text-xs text-slate-500 dark:text-slate-400">
                                                        Key: {moduleKey}
                                                    </p>
                                                </div>
                                                <div className="grid grid-cols-1 md:grid-cols-2 gap-4 w-full md:w-80">
                                                    <div>
                                                        <label className="text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                                                            Price (BDT)
                                                        </label>
                                                        <input
                                                            type="number"
                                                            min="0"
                                                            value={data.modules[moduleKey]?.monthly_price_bdt ?? 0}
                                                            onChange={(e) => setData('modules', {
                                                                ...data.modules,
                                                                [moduleKey]: {
                                                                    ...data.modules[moduleKey],
                                                                    monthly_price_bdt: Number(e.target.value || 0),
                                                                },
                                                            })}
                                                            className="mt-1 block w-full bg-white dark:bg-slate-800 border-slate-200 dark:border-white/10 dark:text-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                                        />
                                                        <InputError message={errors[`modules.${moduleKey}.monthly_price_bdt`]} className="mt-1" />
                                                    </div>
                                                    <div>
                                                        <label className="text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                                                            Price (USD)
                                                        </label>
                                                        <input
                                                            type="number"
                                                            min="0"
                                                            value={data.modules[moduleKey]?.monthly_price_usd ?? 0}
                                                            onChange={(e) => setData('modules', {
                                                                ...data.modules,
                                                                [moduleKey]: {
                                                                    ...data.modules[moduleKey],
                                                                    monthly_price_usd: Number(e.target.value || 0),
                                                                },
                                                            })}
                                                            className="mt-1 block w-full bg-white dark:bg-slate-800 border-slate-200 dark:border-white/10 dark:text-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                                        />
                                                        <InputError message={errors[`modules.${moduleKey}.monthly_price_usd`]} className="mt-1" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    ))}
                                </div>

                                <div className="pt-4 border-t border-slate-100 dark:border-white/5 mt-8 flex items-center justify-end transition-colors">
                                    <button
                                        type="submit"
                                        disabled={processing}
                                        className="flex items-center gap-2 bg-indigo-600 text-white px-6 py-2.5 rounded-xl font-bold text-sm shadow-xl shadow-indigo-100 dark:shadow-none hover:bg-indigo-700 disabled:opacity-70"
                                    >
                                        <Save className="w-4 h-4" />
                                        Save Pricing
                                    </button>
                                </div>
                            </form>
                        )}
                    </div>
                </div>
            </div>
        </AdminLayout>
    );
}
