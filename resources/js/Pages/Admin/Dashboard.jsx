import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, usePage } from '@inertiajs/react';

export default function Dashboard() {
    const { auth } = usePage().props;

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-2xl font-bold leading-tight text-slate-800">
                    Retail Overview
                </h2>
            }
        >
            <Head title="Dashboard" />

            <div className="py-10 bg-slate-50 min-h-[calc(100vh-64px)]">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    {/* Welcome Header */}
                    <div className="mb-8">
                        <h1 className="text-3xl font-extrabold text-slate-900">Welcome back, {auth.user.name}!</h1>
                        <p className="text-slate-500 mt-1">Here is what's happening with your shop today.</p>
                    </div>

                    {/* Stats Grid */}
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div className="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                            <p className="text-sm font-medium text-slate-500 mb-1">Total Sales</p>
                            <h3 className="text-3xl font-bold text-slate-900">$0.00</h3>
                            <div className="mt-4 flex items-center text-xs text-green-600 font-bold">
                                <span>+0% from yesterday</span>
                            </div>
                        </div>
                        <div className="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                            <p className="text-sm font-medium text-slate-500 mb-1">Active Orders</p>
                            <h3 className="text-3xl font-bold text-slate-900">0</h3>
                            <div className="mt-4 flex items-center text-xs text-slate-400 font-bold">
                                <span>Check inventory logs</span>
                            </div>
                        </div>
                        <div className="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                            <p className="text-sm font-medium text-slate-500 mb-1">Visitors</p>
                            <h3 className="text-3xl font-bold text-slate-900">1</h3>
                            <div className="mt-4 flex items-center text-xs text-indigo-600 font-bold">
                                <span>Live tracking active</span>
                            </div>
                        </div>
                    </div>

                    {/* Content Section */}
                    <div className="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        <div className="p-8">
                            <div className="flex items-center justify-between mb-6">
                                <h3 className="text-lg font-bold text-slate-900">Getting Started</h3>
                            </div>
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div className="space-y-4">
                                    <div className="flex gap-4 p-4 rounded-xl bg-slate-50 border border-slate-100">
                                        <div className="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold">1</div>
                                        <div>
                                            <h4 className="font-bold text-slate-800">Add your first product</h4>
                                            <p className="text-sm text-slate-500">Go to Inventory and start adding items to your stock.</p>
                                        </div>
                                    </div>
                                    <div className="flex gap-4 p-4 rounded-xl bg-slate-50 border border-slate-100">
                                        <div className="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold">2</div>
                                        <div>
                                            <h4 className="font-bold text-slate-800">Configure POS</h4>
                                            <p className="text-sm text-slate-500">Set up your terminal and start processing sales.</p>
                                        </div>
                                    </div>
                                </div>
                                <div className="relative group cursor-pointer overflow-hidden rounded-2xl bg-indigo-600 p-8 text-white">
                                    <div className="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-3xl transition group-hover:scale-110"></div>
                                    <h3 className="text-xl font-bold mb-2">Need help?</h3>
                                    <p className="text-indigo-100 mb-6 font-light">Check out our documentation for advanced multitenant configurations.</p>
                                    <button className="px-6 py-2 bg-white text-indigo-600 rounded-full font-bold text-sm shadow-lg">Documentation</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}

