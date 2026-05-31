import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, usePage } from '@inertiajs/react';
import { ShoppingCart, Package, Users, TrendingUp, AlertCircle, Clock } from 'lucide-react';

export default function Dashboard({ dashboardData, shopName }) {
    const { auth, announcements } = usePage().props;
    const { stats, inventory, recent_sales } = dashboardData || {};

    const StatCard = ({ title, value, subtitle, icon: Icon, colorClass }) => (
        <div className="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-white/10 hover:shadow-md transition-all duration-300 group">
            <div className="flex justify-between items-start mb-4">
                <div className={`p-3 rounded-xl ${colorClass} bg-opacity-10 dark:bg-opacity-20`}>
                    <Icon className={`w-6 h-6 ${colorClass.replace('bg-', 'text-')}`} />
                </div>
                {subtitle && (
                    <span className={`text-xs font-bold px-2.5 py-1 rounded-full ${
                        subtitle.includes('+') ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 
                        subtitle.includes('-') ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' :
                        'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400'
                    }`}>
                        {subtitle}
                    </span>
                )}
            </div>
            <div>
                <p className="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">{title}</p>
                <h3 className="text-3xl font-extrabold text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                    {value}
                </h3>
            </div>
        </div>
    );

    return (
        <AuthenticatedLayout
            header={
                <div className="flex items-center justify-between">
                    <h2 className="text-2xl font-bold leading-tight text-slate-800 dark:text-slate-200">
                        {shopName} Dashboard
                    </h2>
                    <span className="px-3 py-1 bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400 rounded-full text-xs font-bold tracking-wide">
                        Live Data
                    </span>
                </div>
            }
        >
            <Head title={`${shopName} - Dashboard`} />

            <div className="py-8 bg-slate-50 dark:bg-slate-950 min-h-[calc(100vh-64px)] transition-colors duration-300">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-8">
                    
                    {/* Welcome Section */}
                    <div className="bg-gradient-to-r from-indigo-600 to-violet-600 rounded-3xl p-8 text-white shadow-lg relative overflow-hidden">
                        <div className="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
                        <div className="absolute bottom-0 left-0 -ml-16 -mb-16 w-48 h-48 bg-indigo-400 opacity-20 rounded-full blur-2xl"></div>
                        
                        <div className="relative z-10">
                            <h1 className="text-3xl font-extrabold mb-2 tracking-tight">Welcome back, {auth.user.name}! 👋</h1>
                            <p className="text-indigo-100 text-lg max-w-2xl font-light">Here's your daily summary for {shopName}. Keep up the great work!</p>
                        </div>
                    </div>

                    {/* Stats Grid */}
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <StatCard 
                            title="Today's Sales" 
                            value={`$${stats?.today_sales || '0.00'}`}
                            subtitle={stats?.sales_change_pct ? `${stats.sales_change_pct > 0 ? '+' : ''}${stats.sales_change_pct}%` : null}
                            icon={TrendingUp}
                            colorClass="bg-green-500 text-green-500"
                        />
                        <StatCard 
                            title="Active Orders" 
                            value={stats?.active_orders || '0'}
                            icon={ShoppingCart}
                            colorClass="bg-blue-500 text-blue-500"
                        />
                        <StatCard 
                            title="Total Customers" 
                            value={stats?.total_customers || '0'}
                            icon={Users}
                            colorClass="bg-indigo-500 text-indigo-500"
                        />
                        <StatCard 
                            title="Total Products" 
                            value={inventory?.total_products || '0'}
                            icon={Package}
                            colorClass="bg-purple-500 text-purple-500"
                        />
                    </div>

                    <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        {/* Recent Sales Table */}
                        <div className="lg:col-span-2 bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-slate-200 dark:border-white/10 overflow-hidden transition-colors flex flex-col">
                            <div className="p-6 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
                                <h3 className="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                    <Clock className="w-5 h-5 text-indigo-500" />
                                    Recent Transactions
                                </h3>
                            </div>
                            <div className="p-0 overflow-x-auto flex-1">
                                {recent_sales?.length > 0 ? (
                                    <table className="w-full text-sm text-left">
                                        <thead className="text-xs text-slate-500 uppercase bg-slate-50 dark:bg-slate-800/50 dark:text-slate-400 font-semibold">
                                            <tr>
                                                <th className="px-6 py-4 rounded-tl-lg">Customer</th>
                                                <th className="px-6 py-4">Amount</th>
                                                <th className="px-6 py-4">Status</th>
                                                <th className="px-6 py-4 text-right">Time</th>
                                            </tr>
                                        </thead>
                                        <tbody className="divide-y divide-slate-100 dark:divide-slate-800">
                                            {recent_sales.map((sale) => (
                                                <tr key={sale.id} className="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                                    <td className="px-6 py-4 font-medium text-slate-900 dark:text-white">{sale.customer}</td>
                                                    <td className="px-6 py-4 font-bold text-indigo-600 dark:text-indigo-400">${sale.amount}</td>
                                                    <td className="px-6 py-4">
                                                        <span className={`px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider ${
                                                            sale.status === 'Paid' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' :
                                                            'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400'
                                                        }`}>
                                                            {sale.status}
                                                        </span>
                                                    </td>
                                                    <td className="px-6 py-4 text-right text-slate-500 text-xs">{sale.time}</td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                ) : (
                                    <div className="flex flex-col items-center justify-center py-12 text-center h-full">
                                        <div className="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mb-4">
                                            <ShoppingCart className="w-8 h-8 text-slate-400" />
                                        </div>
                                        <p className="text-slate-500 dark:text-slate-400 font-medium">No recent sales to show.</p>
                                    </div>
                                )}
                            </div>
                        </div>

                        <div className="space-y-8">
                            {/* Low Stock Alerts */}
                            <div className="bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-slate-200 dark:border-white/10 p-6 transition-colors">
                                <h3 className="text-lg font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                                    <AlertCircle className="w-5 h-5 text-red-500" />
                                    Low Stock Alerts
                                </h3>
                                <div className="space-y-4">
                                    {inventory?.low_stock?.length > 0 ? (
                                        inventory.low_stock.map((item) => (
                                            <div key={item.uuid} className="flex items-center justify-between p-3 rounded-xl bg-red-50 dark:bg-red-900/10 border border-red-100 dark:border-red-900/20">
                                                <div>
                                                    <h4 className="font-bold text-slate-800 dark:text-slate-200 text-sm">{item.name}</h4>
                                                    <p className="text-xs text-red-600 dark:text-red-400 mt-0.5 font-medium">{item.stock_quantity} left in stock</p>
                                                </div>
                                                <button className="text-xs font-bold bg-white dark:bg-slate-800 text-red-600 dark:text-red-400 px-3 py-1.5 rounded-lg border border-red-100 dark:border-red-800/30 hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors">
                                                    Restock
                                                </button>
                                            </div>
                                        ))
                                    ) : (
                                        <div className="text-center py-6">
                                            <p className="text-sm font-medium text-slate-400">Inventory looks healthy! 🎉</p>
                                        </div>
                                    )}
                                </div>
                            </div>

                            {/* Announcements */}
                            <div className="bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-slate-200 dark:border-white/10 p-6 transition-colors">
                                <h3 className="text-lg font-bold text-slate-900 dark:text-white mb-6">Platform Updates</h3>
                                <div className="space-y-4">
                                    {announcements?.length > 0 ? (
                                        announcements.map((announcement) => (
                                            <div key={announcement.uuid} className="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-white/5 transition-colors group">
                                                <h4 className="font-bold text-slate-800 dark:text-slate-200 text-sm group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{announcement.title}</h4>
                                                <p className="text-xs text-slate-500 dark:text-slate-400 mt-1 line-clamp-2">{announcement.body}</p>
                                                <span className="text-[10px] text-indigo-500 dark:text-indigo-400 mt-2 block font-bold uppercase tracking-wider">{announcement.published_at}</span>
                                            </div>
                                        ))
                                    ) : (
                                        <div className="text-center py-6">
                                            <p className="text-sm font-medium text-slate-400">No new announcements</p>
                                        </div>
                                    )}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
