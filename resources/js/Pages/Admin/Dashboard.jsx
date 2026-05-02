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
import { useTheme } from '@/Support/ThemeProvider';

export default function Dashboard({ stats, charts, announcements }) {
    const { mode } = useTheme();
    const isDark = mode === 'dark';
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
            color: isDark ? 'text-blue-400' : 'text-blue-600',
            bg: isDark ? 'bg-blue-500/20' : 'bg-blue-50'
        },
        { 
            name: 'Total Shops', 
            value: stats.total_shops, 
            icon: Store, 
            change: `${stats.shops_change_pct_30d >= 0 ? '+' : ''}${stats.shops_change_pct_30d}%`,
            trend: stats.shops_change_pct_30d >= 0 ? 'up' : 'down',
            color: isDark ? 'text-indigo-400' : 'text-indigo-600',
            bg: isDark ? 'bg-indigo-500/20' : 'bg-indigo-50'
        },
        { 
            name: 'Active Shops', 
            value: stats.active_shops, 
            icon: Activity, 
            change: `${stats.active_shops} active`,
            trend: 'up',
            color: isDark ? 'text-emerald-400' : 'text-emerald-600',
            bg: isDark ? 'bg-emerald-500/20' : 'bg-emerald-50'
        },
        { 
            name: 'Platform Growth', 
            value: `${Math.max(stats.users_change_pct_30d, stats.shops_change_pct_30d)}%`,
            icon: TrendingUp, 
            change: `${stats.new_users_30d} users / ${stats.new_shops_30d} shops (30d)`,
            trend: 'up',
            color: isDark ? 'text-violet-400' : 'text-violet-600',
            bg: isDark ? 'bg-violet-500/20' : 'bg-violet-50'
        },
    ];

    return (
        <AdminLayout header="Dashboard">
            <Head title="Admin Dashboard" />

            {/* Stats Grid */}
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                {statCards.map((stat, index) => (
                    <div key={index} className={`p-6 rounded-2xl border shadow-sm hover:shadow-md transition-shadow ${
                        isDark ? 'bg-slate-900 border-white/10' : 'bg-white border-slate-200'
                    }`}>
                        <div className="flex items-center justify-between mb-4">
                            <div className={`p-3 rounded-xl ${stat.bg} ${stat.color}`}>
                                <stat.icon className="w-6 h-6" />
                            </div>
                            <div className={`flex items-center gap-1 text-sm font-bold ${stat.trend === 'up' ? (isDark ? 'text-emerald-400' : 'text-emerald-600') : (isDark ? 'text-red-400' : 'text-red-600')}`}>
                                {stat.change}
                                {stat.trend === 'up' ? <ArrowUpRight className="w-4 h-4" /> : <ArrowDownRight className="w-4 h-4" />}
                            </div>
                        </div>
                        <h3 className={`text-sm font-medium ${isDark ? 'text-slate-400' : 'text-slate-500'}`}>{stat.name}</h3>
                        <p className={`text-2xl font-bold mt-1 ${isDark ? 'text-white' : 'text-slate-900'}`}>{stat.value}</p>
                    </div>
                ))}
            </div>

            {/* Charts Section */}
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <div className={`p-8 rounded-2xl border shadow-sm ${
                    isDark ? 'bg-slate-900 border-white/10' : 'bg-white border-slate-200'
                }`}>
                    <div className="flex items-center justify-between mb-8">
                        <div>
                            <h3 className={`text-lg font-bold ${isDark ? 'text-white' : 'text-slate-900'}`}>Platform Activity</h3>
                            <p className={`text-sm ${isDark ? 'text-slate-400' : 'text-slate-500'}`}>Overview of registrations over time</p>
                        </div>
                        <select
                            value={activityRange}
                            onChange={(e) => setActivityRange(e.target.value)}
                            className={`rounded-lg text-sm font-medium focus:ring-indigo-500 ${
                                isDark ? 'bg-slate-800 border-white/10 text-slate-300' : 'bg-slate-50 border-slate-200 text-slate-600'
                            }`}
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
                                        <stop offset="5%" stopColor="#6366f1" stopOpacity={isDark ? 0.3 : 0.1}/>
                                        <stop offset="95%" stopColor="#6366f1" stopOpacity={0}/>
                                    </linearGradient>
                                </defs>
                                <CartesianGrid strokeDasharray="3 3" vertical={false} stroke={isDark ? '#334155' : '#f1f5f9'} />
                                <XAxis dataKey="name" axisLine={false} tickLine={false} tick={{fill: isDark ? '#64748b' : '#94a3b8', fontSize: 12}} dy={10} />
                                <YAxis axisLine={false} tickLine={false} tick={{fill: isDark ? '#64748b' : '#94a3b8', fontSize: 12}} />
                                <Tooltip 
                                    contentStyle={{
                                        backgroundColor: isDark ? '#1e293b' : '#fff', 
                                        borderRadius: '12px', 
                                        border: isDark ? '1px solid rgba(255,255,255,0.1)' : 'none', 
                                        color: isDark ? '#f8fafc' : '#0f172a',
                                        boxShadow: '0 10px 15px -3px rgb(0 0 0 / 0.1)'
                                    }}
                                />
                                <Area type="monotone" dataKey="value" stroke="#6366f1" strokeWidth={3} fillOpacity={1} fill="url(#colorValue)" />
                            </AreaChart>
                        </ResponsiveContainer>
                    </div>
                </div>

                <div className={`p-8 rounded-2xl border shadow-sm ${
                    isDark ? 'bg-slate-900 border-white/10' : 'bg-white border-slate-200'
                }`}>
                    <div className="flex items-center justify-between mb-8">
                        <div>
                            <h3 className={`text-lg font-bold ${isDark ? 'text-white' : 'text-slate-900'}`}>Shop Performance</h3>
                            <p className={`text-sm ${isDark ? 'text-slate-400' : 'text-slate-500'}`}>Top performing sectors</p>
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
                                <CartesianGrid strokeDasharray="3 3" vertical={false} stroke={isDark ? '#334155' : '#f1f5f9'} />
                                <XAxis dataKey="name" axisLine={false} tickLine={false} tick={{fill: isDark ? '#64748b' : '#94a3b8', fontSize: 12}} dy={10} />
                                <YAxis axisLine={false} tickLine={false} tick={{fill: isDark ? '#64748b' : '#94a3b8', fontSize: 12}} />
                                <Tooltip 
                                    cursor={{fill: isDark ? '#334155' : '#f8fafc'}}
                                    contentStyle={{
                                        backgroundColor: isDark ? '#1e293b' : '#fff', 
                                        borderRadius: '12px', 
                                        border: isDark ? '1px solid rgba(255,255,255,0.1)' : 'none', 
                                        color: isDark ? '#f8fafc' : '#0f172a',
                                        boxShadow: '0 10px 15px -3px rgb(0 0 0 / 0.1)'
                                    }}
                                />
                                <Bar dataKey="value" fill="#818cf8" radius={[4, 4, 0, 0]} barSize={40} />
                            </BarChart>
                        </ResponsiveContainer>
                    </div>
                </div>
            </div>

            {/* Recent Activity / Bottom Row */}
            <div className={`rounded-2xl border shadow-sm overflow-hidden ${
                isDark ? 'bg-slate-900 border-white/10' : 'bg-white border-slate-200'
            }`}>
                <div className={`p-6 border-b flex items-center justify-between ${
                    isDark ? 'border-white/10' : 'border-slate-100'
                }`}>
                    <h3 className={`font-bold text-lg ${isDark ? 'text-white' : 'text-slate-900'}`}>Platform Announcements</h3>
                    <Link
                        href={route('admin.announcements.create')}
                        className="px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-bold shadow-lg shadow-indigo-100/20 hover:bg-indigo-700 transition-colors"
                    >
                        New Post
                    </Link>
                </div>
                <div className="p-8">
                    {announcements?.length ? (
                        <div className="space-y-4">
                            {announcements.map((a) => (
                                <div key={a.uuid} className={`p-4 rounded-2xl border transition-colors ${
                                    isDark ? 'border-white/10 bg-slate-800 hover:bg-slate-800/80' : 'border-slate-200 bg-white hover:bg-slate-50/50'
                                }`}>
                                    <div className="flex items-start justify-between gap-4">
                                        <div>
                                            <p className={`font-bold ${isDark ? 'text-white' : 'text-slate-900'}`}>{a.title}</p>
                                            <p className={`text-xs mt-1 ${isDark ? 'text-slate-400' : 'text-slate-500'}`}>
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
                            <div className={`w-16 h-16 rounded-full flex items-center justify-center mb-4 ${
                                isDark ? 'bg-slate-800' : 'bg-slate-50'
                            }`}>
                                <Activity className={`w-8 h-8 ${isDark ? 'text-slate-600' : 'text-slate-300'}`} />
                            </div>
                            <h4 className={`font-bold ${isDark ? 'text-white' : 'text-slate-900'}`}>No recent announcements</h4>
                            <p className={`text-sm max-w-xs mt-1 ${isDark ? 'text-slate-400' : 'text-slate-500'}`}>Keep your users informed about platform updates and maintenance.</p>
                        </div>
                    )}
                </div>
            </div>
        </AdminLayout>
    );
}
