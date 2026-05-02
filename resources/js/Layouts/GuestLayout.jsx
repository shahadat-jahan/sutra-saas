import { Link, usePage } from '@inertiajs/react';
import { useAdminBranding } from '@/Support/BrandingProvider';
import { ThemeToggle, useTheme } from '@/Support/ThemeProvider';

export default function GuestLayout({ children }) {
    const { appName } = usePage().props;
    const adminBranding = useAdminBranding();
    const { mode } = useTheme();
    const isDark = mode === 'dark';

    return (
        <div className={`flex min-h-screen flex-col items-center pt-6 sm:justify-center sm:pt-0 relative overflow-hidden transition-colors duration-500 ${
            isDark ? 'bg-slate-950 text-white' : 'bg-slate-50 text-slate-900'
        }`}>

            <div className="absolute top-6 right-6 z-50">
                <ThemeToggle />
            </div>

            <div className="fixed inset-0 z-0 pointer-events-none overflow-hidden">
                <div className={`absolute inset-0 ${isDark ? 'opacity-[0.03]' : 'opacity-[0.02]'}`}
                     style={{ backgroundImage: `radial-gradient(${isDark ? '#fff' : '#000'} 1px, transparent 1px)`, backgroundSize: '32px 32px' }}>
                </div>

                <div
                    className="absolute -top-20 -right-20 w-[400px] h-[400px] transition-opacity duration-700"
                    style={{
                        transform: 'rotate(-25deg)',
                        opacity: isDark ? '0.08' : '0.06',
                        maskImage: 'radial-gradient(circle at center, black 40%, transparent 90%)',
                        WebkitMaskImage: 'radial-gradient(circle at center, black 40%, transparent 90%)'
                    }}
                >
                    <img
                        src={adminBranding.watermark}
                        className="w-full h-full object-contain"
                        alt=""
                    />
                </div>
            </div>

            {/* Background Decorations */}
            <div className={`absolute -top-20 -left-20 w-96 h-96 rounded-full mix-blend-multiply filter blur-[100px] opacity-20 ${
                isDark ? 'bg-indigo-600' : 'bg-indigo-300'
            }`}></div>
            <div className={`absolute -bottom-20 -right-20 w-96 h-96 rounded-full mix-blend-multiply filter blur-[100px] opacity-20 ${
                isDark ? 'bg-purple-600' : 'bg-purple-300'
            }`}></div>

            <div className="relative z-10 transition-all duration-500">
                <Link href="/">
                    <img
                        src={adminBranding.logo}
                        className={`w-20 h-20 rounded-2xl shadow-2xl object-contain ${
                            isDark ? 'shadow-indigo-500/20 brightness-125' : 'shadow-indigo-300/30'
                        }`}
                        alt={`${appName} Logo`}
                    />
                </Link>
            </div>

            <div className={`relative z-10 mt-6 w-full overflow-hidden px-10 py-8 sm:max-w-md sm:rounded-[2rem] transition-all duration-500 ${
                isDark
                    ? 'bg-white/5 backdrop-blur-xl shadow-[0_0_50px_rgba(0,0,0,0.3)] border border-white/10'
                    : 'bg-white backdrop-blur-xl shadow-[0_10px_40px_rgba(0,0,0,0.08)] border border-slate-200'
            }`}>
                {children}
            </div>
        </div>
    );
}
