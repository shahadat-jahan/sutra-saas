import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, usePage } from '@inertiajs/react';

export default function Dashboard() {
    const { auth } = usePage().props;

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-2xl font-bold leading-tight text-slate-800 dark:text-slate-200">
                    Retail Overview
                </h2>
            }
        >
            <Head title="Dashboard" />

            <div className="py-10 bg-slate-50 dark:bg-slate-950 min-h-[calc(100vh-64px)] transition-colors duration-300">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    {/* Welcome Header */}
                    <div className="mb-8">
                        <h1 className="text-3xl font-extrabold text-slate-900 dark:text-white">Welcome back, {auth.user.name}!</h1>
                        <p className="text-slate-500 dark:text-slate-400 mt-1">Here is what's happening with your shop today.</p>
                    </div>

                    {/* Stats Grid */}
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div className="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-white/10 transition-colors">
                            <p className="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Total Sales</p>
                            <h3 className="text-3xl font-bold text-slate-900 dark:text-white">$0.00</h3>
                            <div className="mt-4 flex items-center text-xs text-green-600 dark:text-green-400 font-bold">
                                <span>+0% from yesterday</span>
                            </div>
                        </div>
                        <div className="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-white/10 transition-colors">
                            <p className="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Active Orders</p>
                            <h3 className="text-3xl font-bold text-slate-900 dark:text-white">0</h3>
                            <div className="mt-4 flex items-center text-xs text-slate-400 dark:text-slate-500 font-bold">
                                <span>Check inventory logs</span>
                            </div>
                        </div>
                        <div className="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-white/10 transition-colors">
                            <p className="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Visitors</p>
                            <h3 className="text-3xl font-bold text-slate-900 dark:text-white">1</h3>
                            <div className="mt-4 flex items-center text-xs text-indigo-600 dark:text-indigo-400 font-bold">
                                <span>Live tracking active</span>
                            </div>
                        </div>
                    </div>

                    {/* Content Section */}
                    <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div className="lg:col-span-2 bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-white/10 overflow-hidden transition-colors">
                            <div className="p-8">
                                <div className="flex items-center justify-between mb-6">
                                    <h3 className="text-lg font-bold text-slate-900 dark:text-white">Getting Started</h3>
                                </div>
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div className="space-y-4">
                                        <div className="flex gap-4 p-4 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-white/5 transition-colors">
                                            <div className="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-500/20 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold">1</div>
                                            <div>
                                                <h4 className="font-bold text-slate-800 dark:text-slate-200">Add your first product</h4>
                                                <p className="text-sm text-slate-500 dark:text-slate-400">Go to Inventory and start adding items to your stock.</p>
                                            </div>
                                        </div>
                                        <div className="flex gap-4 p-4 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-white/5 transition-colors">
                                            <div className="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-500/20 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold">2</div>
                                            <div>
                                                <h4 className="font-bold text-slate-800 dark:text-slate-200">Configure POS</h4>
                                                <p className="text-sm text-slate-500 dark:text-slate-400">Set up your terminal and start processing sales.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="relative group cursor-pointer overflow-hidden rounded-2xl bg-indigo-600 dark:bg-indigo-700 p-8 text-white transition-colors">
                                        <div className="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-3xl transition group-hover:scale-110"></div>
                                        <h3 className="text-xl font-bold mb-2 text-white">Need help?</h3>
                                        <p className="text-indigo-100 dark:text-indigo-200 mb-6 font-light">Check out our documentation for advanced multitenant configurations.</p>
                                        <button className="px-6 py-2 bg-white text-indigo-600 dark:bg-slate-900 dark:text-white rounded-full font-bold text-sm shadow-lg transition-colors hover:bg-slate-50 dark:hover:bg-slate-800">Documentation</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Platform Announcements */}
                        <div className="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-white/10 p-6 transition-colors">
                            <h3 className="text-lg font-bold text-slate-900 dark:text-white mb-6">Platform Updates</h3>
                            <div className="space-y-4">
                                {usePage().props.announcements?.length ? (
                                    usePage().props.announcements.map((announcement) => (
                                        <div key={announcement.uuid} className="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-white/5 transition-colors">
                                            <h4 className="font-bold text-slate-800 dark:text-slate-200 text-sm">{announcement.title}</h4>
                                            <p className="text-xs text-slate-500 dark:text-slate-400 mt-1 line-clamp-2">{announcement.body}</p>
                                            <span className="text-[10px] text-indigo-500 dark:text-indigo-400 mt-2 block font-medium uppercase tracking-wider">{announcement.published_at}</span>
                                        </div>
                                    ))
                                ) : (
                                    <div className="text-center py-8">
                                        <p className="text-sm text-slate-400">No new announcements</p>
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}

