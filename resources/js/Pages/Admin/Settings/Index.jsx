import AdminLayout from '@/Layouts/AdminLayout';
import { Head } from '@inertiajs/react';
import { 
    Globe, 
    Mail, 
    Lock, 
    Shield, 
    CreditCard,
    Save,
    Bell
} from 'lucide-react';
import { useState } from 'react';

export default function Index() {
    const [activeTab, setActiveTab] = useState('general');

    const tabs = [
        { id: 'general', name: 'General', icon: Globe },
        { id: 'notifications', name: 'Notifications', icon: Bell },
        { id: 'security', name: 'Security', icon: Shield },
        { id: 'billing', name: 'Billing Config', icon: CreditCard },
    ];

    return (
        <AdminLayout header="Settings">
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
                                ? 'bg-indigo-600 text-white shadow-xl shadow-indigo-100' 
                                : 'text-slate-500 hover:bg-white hover:text-slate-900 border border-transparent hover:border-slate-200'
                            }`}
                        >
                            <tab.icon className="w-4 h-4" />
                            {tab.name}
                        </button>
                    ))}
                </div>

                {/* Main Settings Form */}
                <div className="flex-1 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div className="p-8">
                        <div className="mb-8">
                            <h2 className="text-xl font-bold text-slate-900">General Settings</h2>
                            <p className="text-slate-500 text-sm">Configure the main platform identity and preferences.</p>
                        </div>

                        <div className="space-y-6 max-w-2xl text-left">
                            <div className="grid grid-cols-1 gap-1">
                                <label className="text-sm font-bold text-slate-700">Platform Name</label>
                                <input 
                                    type="text" 
                                    defaultValue="Sutra SaaS"
                                    className="mt-1 block w-full border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-indigo-500 focus:border-indigo-500"
                                />
                            </div>

                            <div className="grid grid-cols-1 gap-1">
                                <label className="text-sm font-bold text-slate-700">Support Email</label>
                                <input 
                                    type="email" 
                                    defaultValue="support@sutrasaas.com"
                                    className="mt-1 block w-full border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-indigo-500 focus:border-indigo-500"
                                />
                            </div>

                            <div className="pt-4 border-t border-slate-100 mt-8 flex items-center justify-between">
                                <p className="text-xs text-slate-400 font-medium italic">Last updated: Today at 2:00 PM</p>
                                <button className="flex items-center gap-2 bg-indigo-600 text-white px-6 py-2.5 rounded-xl font-bold text-sm shadow-xl shadow-indigo-100 hover:bg-indigo-700 transition-all">
                                    <Save className="w-4 h-4" />
                                    Save Changes
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AdminLayout>
    );
}
