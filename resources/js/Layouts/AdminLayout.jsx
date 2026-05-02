import ApplicationLogo from '@/Components/ApplicationLogo';
import { Link, usePage } from '@inertiajs/react';
import { 
    LayoutDashboard, 
    Store, 
    Users, 
    Megaphone,
    Settings, 
    LogOut, 
    Menu, 
    X, 
    Bell,
    Search,
    ChevronRight
} from 'lucide-react';
import { useState } from 'react';

import { useAdminBranding } from '@/Support/BrandingProvider';
import { ThemeToggle, useTheme } from '@/Support/ThemeProvider';

export default function AdminLayout({ children, header }) {
    const user = usePage().props.auth.user;
    const [isSidebarOpen, setIsSidebarOpen] = useState(true);
    const adminBranding = useAdminBranding();
    const { mode } = useTheme();
    const isDark = mode === 'dark';

    const navigation = [
        { name: 'Dashboard', href: route('admin.dashboard'), icon: LayoutDashboard, current: route().current('admin.dashboard') },
        { name: 'Announcements', href: route('admin.announcements.index'), icon: Megaphone, current: route().current('admin.announcements.*') },
        { name: 'Shops', href: route('admin.shops.index'), icon: Store, current: route().current('admin.shops.*') },
        { name: 'Users', href: route('admin.users.index'), icon: Users, current: route().current('admin.users.*') },
        { name: 'Settings', href: route('admin.settings.index'), icon: Settings, current: route().current('admin.settings.*') },
    ];

    return (
        <div className={`min-h-screen relative transition-colors duration-300 ${isDark ? 'bg-slate-950 text-slate-300' : 'bg-[#F8FAFC] text-slate-600'}`}>
            {/* Watermark Background */}
            <div 
                className={`fixed inset-0 z-0 pointer-events-none ${isDark ? 'opacity-10' : 'opacity-5'}`}
                style={{
                    backgroundImage: `url(${adminBranding.watermark})`,
                    backgroundRepeat: 'no-repeat',
                    backgroundPosition: 'center',
                    backgroundSize: '50%',
                }}
            ></div>

            {/* Sidebar */}
            <aside 
                className={`fixed inset-y-0 left-0 z-50 w-72 border-r transition-transform duration-300 ease-in-out transform ${isSidebarOpen ? 'translate-x-0' : '-translate-x-full'} lg:translate-x-0 ${
                    isDark ? 'bg-slate-900 border-white/10' : 'bg-white border-slate-200'
                }`}
            >
                <div className="flex flex-col h-full relative z-10">
                    {/* Logo */}
                    <div className={`flex items-center h-20 px-8 border-b relative overflow-hidden ${
                        isDark ? 'border-white/10' : 'border-slate-100'
                    }`}>
                        {/* Sidebar Banner (Optional, very light behind logo) */}
                        <div 
                            className="absolute inset-0 opacity-10 pointer-events-none"
                            style={{
                                backgroundImage: `url(${adminBranding.banner})`,
                                backgroundSize: 'cover',
                                backgroundPosition: 'center'
                            }}
                        ></div>
                        <Link href="/" className="flex items-center gap-3 relative z-10">
                            <img src={adminBranding.logo} className={`w-10 h-10 object-contain ${isDark ? 'brightness-125' : ''}`} alt="Admin Logo" />
                            <span className="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-indigo-500 to-violet-500 tracking-tight">
                                Sutra SaaS
                            </span>
                        </Link>
                    </div>

                    {/* Navigation */}
                    <nav className="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                        <div className={`px-4 mb-4 text-xs font-semibold uppercase tracking-wider ${isDark ? 'text-slate-500' : 'text-slate-400'}`}>
                            Main Menu
                        </div>
                        {navigation.map((item) => (
                            <Link
                                key={item.name}
                                href={item.href}
                                className={`flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group ${
                                    item.current 
                                    ? (isDark ? 'bg-indigo-500/10 text-indigo-400 shadow-sm' : 'bg-indigo-50 text-indigo-700 shadow-sm')
                                    : (isDark ? 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900')
                                }`}
                            >
                                <item.icon className={`w-5 h-5 transition-colors ${
                                    item.current 
                                        ? (isDark ? 'text-indigo-400' : 'text-indigo-600') 
                                        : (isDark ? 'text-slate-500 group-hover:text-slate-300' : 'text-slate-400 group-hover:text-slate-600')
                                }`} />
                                <span className="font-medium">{item.name}</span>
                                {item.current && <ChevronRight className="ml-auto w-4 h-4" />}
                            </Link>
                        ))}
                    </nav>

                    {/* User Profile */}
                    <div className={`p-4 border-t ${isDark ? 'border-white/10' : 'border-slate-100'}`}>
                        <div className={`flex items-center gap-3 p-3 rounded-xl border mb-4 ${
                            isDark ? 'bg-slate-800 border-white/10' : 'bg-slate-50 border-slate-100'
                        }`}>
                            <div className="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold shadow-md">
                                {user.name.charAt(0)}
                            </div>
                            <div className="flex-1 min-w-0">
                                <p className={`text-sm font-bold truncate ${isDark ? 'text-white' : 'text-slate-900'}`}>{user.name}</p>
                                <p className={`text-xs truncate ${isDark ? 'text-slate-400' : 'text-slate-500'}`}>{user.email}</p>
                            </div>
                        </div>
                        <Link
                            href="/logout"
                            method="post"
                            as="button"
                            className={`flex items-center gap-3 w-full px-4 py-3 rounded-xl transition-colors font-medium ${
                                isDark ? 'text-slate-400 hover:bg-red-500/10 hover:text-red-400' : 'text-slate-500 hover:bg-red-50 hover:text-red-600'
                            }`}
                        >
                            <LogOut className="w-5 h-5" />
                            <span>Sign Out</span>
                        </Link>
                    </div>
                </div>
            </aside>

            {/* Main Content */}
            <div className={`transition-all duration-300 relative z-10 ${isSidebarOpen ? 'lg:pl-72' : ''}`}>
                {/* Header */}
                <header className={`h-20 backdrop-blur-md border-b sticky top-0 z-40 ${
                    isDark ? 'bg-slate-950/80 border-white/10' : 'bg-white/80 border-slate-200'
                }`}>
                    <div className="h-full px-6 flex items-center justify-between">
                        <div className="flex items-center gap-4">
                            <button 
                                onClick={() => setIsSidebarOpen(!isSidebarOpen)}
                                className={`p-2 rounded-lg transition-colors lg:hidden ${
                                    isDark ? 'text-slate-400 hover:bg-slate-800' : 'text-slate-500 hover:bg-slate-100'
                                }`}
                            >
                                {isSidebarOpen ? <X className="w-6 h-6" /> : <Menu className="w-6 h-6" />}
                            </button>
                            <div className={`hidden md:flex items-center gap-2 text-sm font-medium ${isDark ? 'text-slate-500' : 'text-slate-400'}`}>
                                <span>Platform Admin</span>
                                <ChevronRight className="w-4 h-4" />
                                <span className={isDark ? 'text-slate-200' : 'text-slate-900'}>{header}</span>
                            </div>
                        </div>

                        <div className="flex items-center gap-4">
                            <div className="relative hidden sm:block">
                                <Search className="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" />
                                <input 
                                    type="text" 
                                    placeholder="Search..." 
                                    className={`pl-10 pr-4 py-2 border-none rounded-full text-sm focus:ring-2 focus:ring-indigo-500 w-64 transition-all ${
                                        isDark ? 'bg-slate-800 text-white placeholder-slate-400' : 'bg-slate-100 text-slate-900'
                                    }`}
                                />
                            </div>
                            
                            <ThemeToggle />

                            <button className={`p-2 rounded-lg relative ${
                                isDark ? 'text-slate-400 hover:bg-slate-800' : 'text-slate-500 hover:bg-slate-100'
                            }`}>
                                <Bell className="w-6 h-6" />
                                <span className={`absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border-2 transition-colors ${
                                    isDark ? 'border-slate-950' : 'border-white'
                                }`}></span>
                            </button>
                            <div className={`h-8 w-[1px] mx-2 ${isDark ? 'bg-slate-800' : 'bg-slate-200'}`}></div>
                            <div className="w-10 h-10 rounded-full overflow-hidden border-2 border-indigo-100 shadow-sm">
                                <img 
                                    src={`https://ui-avatars.com/api/?name=${user.name}&background=6366f1&color=fff`} 
                                    alt={user.name} 
                                />
                            </div>
                        </div>
                    </div>
                </header>

                {/* Page Content */}
                <main className="p-8">
                    {children}
                </main>
            </div>
        </div>
    );
}
