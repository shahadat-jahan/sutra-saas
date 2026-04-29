import AdminLayout from '@/Layouts/AdminLayout';
import { Head, Link } from '@inertiajs/react';
import { 
    Users, 
    Store, 
    TrendingUp, 
    Activity,
    ArrowUpRight,
    ArrowDownRight
} from 'lucide-react';
import { useMemo, useState } from 'react';
import { 
    BarChart, 
    Bar, 
    XAxis, 
    YAxis, 
    CartesianGrid, 
    Tooltip, 
    ResponsiveContainer,
    AreaChart,
    Area
} from 'recharts';

export default function Dashboard({ stats, charts, announcements }) {
    const [activityRange, setActivityRange] = useState('6m');
    const activityData = useMemo(() => (
        activityRange === '12m' ? charts.activity_12m : charts.activity_6m
    ), [activityRange, charts]);

    const statCards = [
        { 
            name: 'Total Users', 
            value: stats.total_users, 
            icon: Users, 
            change: `${stats.users_change_pct_30d >= 0 ? '+' : ''}${stats.users_change_pct_30d}%`,
            trend: stats.users_change_pct_30d >= 0 ? 'up' : 'down',
            color: 'text-blue-600',
            bg: 'bg-blue-50'
        },
        { 
            name: 'Total Shops', 
            value: stats.total_shops, 
            icon: Store, 
            change: `${stats.shops_change_pct_30d >= 0 ? '+' : ''}${stats.shops_change_pct_30d}%`,
            trend: stats.shops_change_pct_30d >= 0 ? 'up' : 'down',
            color: 'text-indigo-600',
            bg: 'bg-indigo-50'
        },
        { 
            name: 'Active Shops', 
            value: stats.active_shops, 
            icon: Activity, 
            change: `${stats.active_shops} active`,
            trend: 'up',
            color: 'text-emerald-600',
            bg: 'bg-emerald-50'
        },
        { 
            name: 'Platform Growth', 
            value: `${Math.max(stats.users_change_pct_30d, stats.shops_change_pct_30d)}%`,
            icon: TrendingUp, 
            change: `${stats.new_users_30d} users / ${stats.new_shops_30d} shops (30d)`,
            trend: 'up',
            color: 'text-violet-600',
            bg: 'bg-violet-50'
        },
    ];

    return (
        <AdminLayout header="Dashboard">
            <Head title="Admin Dashboard" />

            {/* Stats Grid */}
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                {statCards.map((stat, index) => (
                    <div key={index} className="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                        <div className="flex items-center justify-between mb-4">
                            <div className={`p-3 rounded-xl ${stat.bg} ${stat.color}`}>
                                <stat.icon className="w-6 h-6" />
                            </div>
                            <div className={`flex items-center gap-1 text-sm font-bold ${stat.trend === 'up' ? 'text-emerald-600' : 'text-red-600'}`}>
                                {stat.change}
                                {stat.trend === 'up' ? <ArrowUpRight className="w-4 h-4" /> : <ArrowDownRight className="w-4 h-4" />}
                            </div>
                        </div>
                        <h3 className="text-slate-500 text-sm font-medium">{stat.name}</h3>
                        <p className="text-2xl font-bold text-slate-900 mt-1">{stat.value}</p>
                    </div>
                ))}
            </div>

            {/* Charts Section */}
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <div className="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
                    <div className="flex items-center justify-between mb-8">
                        <div>
                            <h3 className="text-lg font-bold text-slate-900">Platform Activity</h3>
                            <p className="text-slate-500 text-sm">Overview of registrations over time</p>
                        </div>
                        <select
                            value={activityRange}
                            onChange={(e) => setActivityRange(e.target.value)}
                            className="bg-slate-50 border-slate-200 rounded-lg text-sm font-medium focus:ring-indigo-500 text-slate-600"
                        >
                            <option value="6m">Last 6 Months</option>
                            <option value="12m">Last Year</option>
                        </select>
                    </div>
                    <div className="h-80 w-full">
                        <ResponsiveContainer width="100%" height="100%">
                            <AreaChart data={activityData}>
                                <defs>
                                    <linearGradient id="colorValue" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="5%" stopColor="#6366f1" stopOpacity={0.1}/>
                                        <stop offset="95%" stopColor="#6366f1" stopOpacity={0}/>
                                    </linearGradient>
                                </defs>
                                <CartesianGrid strokeDasharray="3 3" vertical={false} stroke="#f1f5f9" />
                                <XAxis dataKey="name" axisLine={false} tickLine={false} tick={{fill: '#94a3b8', fontSize: 12}} dy={10} />
                                <YAxis axisLine={false} tickLine={false} tick={{fill: '#94a3b8', fontSize: 12}} />
                                <Tooltip 
                                    contentStyle={{backgroundColor: '#fff', borderRadius: '12px', border: 'none', boxShadow: '0 10px 15px -3px rgb(0 0 0 / 0.1)'}}
                                />
                                <Area type="monotone" dataKey="value" stroke="#6366f1" strokeWidth={3} fillOpacity={1} fill="url(#colorValue)" />
                            </AreaChart>
                        </ResponsiveContainer>
                    </div>
                </div>

                <div className="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
                    <div className="flex items-center justify-between mb-8">
                        <div>
                            <h3 className="text-lg font-bold text-slate-900">Shop Performance</h3>
                            <p className="text-slate-500 text-sm">Top performing sectors</p>
                        </div>
                        <Link
                            href={route('admin.shops.index')}
                            className="text-indigo-600 font-bold text-sm hover:underline"
                        >
                            View All
                        </Link>
                    </div>
                    <div className="h-80 w-full">
                        <ResponsiveContainer width="100%" height="100%">
                            <BarChart data={charts.sectors}>
                                <CartesianGrid strokeDasharray="3 3" vertical={false} stroke="#f1f5f9" />
                                <XAxis dataKey="name" axisLine={false} tickLine={false} tick={{fill: '#94a3b8', fontSize: 12}} dy={10} />
                                <YAxis axisLine={false} tickLine={false} tick={{fill: '#94a3b8', fontSize: 12}} />
                                <Tooltip 
                                    cursor={{fill: '#f8fafc'}}
                                    contentStyle={{backgroundColor: '#fff', borderRadius: '12px', border: 'none', boxShadow: '0 10px 15px -3px rgb(0 0 0 / 0.1)'}}
                                />
                                <Bar dataKey="value" fill="#818cf8" radius={[4, 4, 0, 0]} barSize={40} />
                            </BarChart>
                        </ResponsiveContainer>
                    </div>
                </div>
            </div>

            {/* Recent Activity / Bottom Row */}
            <div className="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div className="p-6 border-b border-slate-100 flex items-center justify-between">
                    <h3 className="font-bold text-slate-900 text-lg">Platform Announcements</h3>
                    <Link
                        href={route('admin.announcements.create')}
                        className="px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-colors"
                    >
                        New Post
                    </Link>
                </div>
                <div className="p-8">
                    {announcements?.length ? (
                        <div className="space-y-4">
                            {announcements.map((a) => (
                                <div key={a.uuid} className="p-4 rounded-2xl border border-slate-200 bg-white hover:bg-slate-50/50 transition-colors">
                                    <div className="flex items-start justify-between gap-4">
                                        <div>
                                            <p className="font-bold text-slate-900">{a.title}</p>
                                            <p className="text-xs text-slate-500 mt-1">
                                                Published: {a.published_at ? new Date(a.published_at).toLocaleString() : '—'}
                                            </p>
                                        </div>
                                        <Link
                                            href={route('admin.announcements.index')}
                                            className="text-indigo-600 font-bold text-sm hover:underline whitespace-nowrap"
                                        >
                                            View all
                                        </Link>
                                    </div>
                                </div>
                            ))}
                        </div>
                    ) : (
                        <div className="flex flex-col items-center justify-center text-center py-12">
                            <div className="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                <Activity className="w-8 h-8 text-slate-300" />
                            </div>
                            <h4 className="font-bold text-slate-900">No recent announcements</h4>
                            <p className="text-slate-500 text-sm max-w-xs mt-1">Keep your users informed about platform updates and maintenance.</p>
                        </div>
                    )}
                </div>
            </div>
        </AdminLayout>
    );
}
