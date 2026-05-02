import { useState } from 'react';
import { Head, Link, usePage } from '@inertiajs/react';
import { Check, Globe, BarChart3, Users, LayoutDashboard, Clock } from 'lucide-react';
import { ThemeToggle, useTheme } from '@/Support/ThemeProvider';
import { useAdminBranding } from '@/Support/BrandingProvider';

export default function Welcome({ auth, plans }) {
    const { mode } = useTheme();
    const isDark = mode === 'dark';
    const adminBranding = useAdminBranding();

    const { appName } = usePage().props;
    const [isMenuOpen, setIsMenuOpen] = useState(false);
    
    const moduleIcons = {
        basic: Globe,
        pro: BarChart3,
        enterprise: Users,
    };

    return (
        <div className={`min-h-screen selection:bg-indigo-500 selection:text-white transition-colors duration-500 overflow-x-hidden ${
            isDark ? 'bg-slate-950' : 'bg-white'
        }`}>
            <Head title={`Welcome to ${appName}`} />

            <div className="relative">
                {/* Background Blobs */}
                {isDark && (
                    <>
                        <div className="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-indigo-600/20 rounded-full mix-blend-screen filter blur-[120px] animate-pulse"></div>
                        <div className="absolute top-[20%] right-[-5%] w-[400px] h-[400px] bg-purple-600/20 rounded-full mix-blend-screen filter blur-[120px] animate-pulse" style={{animationDelay: "2s"}}></div>
                        <div className="absolute -bottom-40 left-1/2 w-96 h-96 bg-blue-300 rounded-full mix-blend-multiply filter blur-[100px] opacity-20 animate-pulse" style={{animationDelay: "4s"}}></div>
                    </>
                )}

                {/* Premium Background Elements */}
                <div className="fixed inset-0 z-0 pointer-events-none overflow-hidden">
                    {/* Grid Pattern */}
                    <div className={`absolute inset-0 ${isDark ? 'opacity-[0.03]' : 'opacity-[0.02]'}`} 
                         style={{ backgroundImage: `radial-gradient(${isDark ? '#fff' : '#000'} 1px, transparent 1px)`, backgroundSize: '32px 32px' }}>
                    </div>

                    {/* Elegant Tilted Watermark with Faded Edges */}
                    <div 
                        className="absolute -top-32 -right-32 w-[600px] h-[600px] transition-opacity duration-700"
                        style={{
                            transform: 'rotate(-25deg)',
                            opacity: isDark ? '0.08' : '0.05',
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

                {/* Hero Banner Background Decoration */}
                <div 
                    className="absolute top-0 left-0 right-0 h-[600px] opacity-10 pointer-events-none z-0 overflow-hidden"
                >
                    <div 
                        className="absolute inset-0 scale-110 blur-sm"
                        style={{
                            backgroundImage: `url(${adminBranding.banner})`,
                            backgroundSize: 'cover',
                            backgroundPosition: 'center'
                        }}
                    ></div>
                    <div className={`absolute inset-0 bg-gradient-to-b ${
                        isDark ? 'from-slate-950/0 via-slate-950/50 to-slate-950' : 'from-white/0 via-white/50 to-white'
                    }`}></div>
                </div>

                <div className="relative z-10 flex flex-col min-h-screen">
                    <header className="flex items-center justify-between px-4 sm:px-6 py-4 sm:py-6 w-full max-w-7xl mx-auto relative z-50">
                        <Link 
                            href={auth.user 
                                ? (auth.user.roles?.some(r => r.name === 'super-admin') ? route('admin.dashboard') : (auth.user.shop ? route('dashboard', { subdomain: auth.user.shop.slug }) : '/'))
                                : '/'
                            } 
                            className="flex items-center gap-2 sm:gap-3 group"
                        >
                            <img src={adminBranding.logo} className={`w-10 h-10 sm:w-12 sm:h-12 rounded-xl shadow-lg object-contain transition-transform duration-300 group-hover:scale-110 ${
                                isDark ? 'shadow-indigo-500/30' : 'shadow-indigo-300/30'
                            }`} alt={`${appName} Logo`} />
                            <span className={`text-xl sm:text-2xl font-bold bg-clip-text text-transparent ${
                                isDark
                                    ? 'bg-gradient-to-r from-white to-slate-400'
                                    : 'bg-gradient-to-r from-slate-900 to-slate-600'
                            } tracking-tight`}>{appName}</span>
                        </Link>

                        {/* Desktop Navigation */}
                        <nav className={`hidden lg:flex gap-8 text-sm font-medium ${isDark ? 'text-slate-400' : 'text-slate-600'}`}>
                            <a href="#features" className={`${isDark ? 'hover:text-white' : 'hover:text-slate-900'} transition cursor-pointer`}>Features</a>
                            <a href="#solutions" className={`${isDark ? 'hover:text-white' : 'hover:text-slate-900'} transition cursor-pointer`}>Solutions</a>
                            <a href="#pricing" className={`${isDark ? 'hover:text-white' : 'hover:text-slate-900'} transition cursor-pointer`}>Pricing</a>
                        </nav>

                        <div className="flex gap-2 sm:gap-4 items-center">
                            <ThemeToggle />
                            
                            {/* Desktop Auth Buttons */}
                            <div className="hidden md:flex gap-3 items-center">
                                {auth.user ? (
                                    <Link
                                        href={auth.user.roles?.some(r => r.name === 'super-admin') 
                                            ? route('admin.dashboard') 
                                            : (auth.user.shop ? route('dashboard', { subdomain: auth.user.shop.slug }) : '#')
                                        }
                                        className={`px-5 py-2 text-sm font-bold rounded-full transition-all ${
                                            isDark
                                                ? 'text-white bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/10'
                                                : 'text-slate-900 bg-indigo-500/10 hover:bg-indigo-500/20 border border-indigo-500/20'
                                        }`}
                                    >
                                        Dashboard
                                    </Link>
                                ) : (
                                    <>
                                        <Link
                                            href={route('login')}
                                            className={`px-4 py-2 text-sm font-semibold transition-colors ${
                                                isDark ? 'text-slate-400 hover:text-white' : 'text-slate-600 hover:text-slate-900'
                                            }`}
                                        >
                                            Log in
                                        </Link>
                                        <Link
                                            href={route('register')}
                                            className={`px-6 py-2.5 text-sm font-bold text-white rounded-full transition-all transform hover:scale-105 ${
                                                isDark
                                                    ? 'bg-indigo-600 shadow-lg shadow-indigo-500/20'
                                                    : 'bg-indigo-600 shadow-lg shadow-indigo-600/20'
                                            }`}
                                        >
                                            Start Free Trial
                                        </Link>
                                    </>
                                )}
                            </div>
                            
                            {/* Mobile Menu Button */}
                            <button 
                                onClick={() => setIsMenuOpen(!isMenuOpen)}
                                className={`lg:hidden p-2 rounded-xl transition-colors ${
                                    isDark ? 'text-white hover:bg-white/10' : 'text-slate-900 hover:bg-slate-100'
                                }`}
                                aria-label="Toggle menu"
                            >
                                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    {isMenuOpen ? (
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                                    ) : (
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6h16M4 12h16M4 18h16" />
                                    )}
                                </svg>
                            </button>
                        </div>
                    </header>

                    {/* Mobile Navigation Menu */}
                    <div className={`lg:hidden fixed inset-0 z-[100] transition-all duration-300 ${
                        isMenuOpen ? 'opacity-100 visible' : 'opacity-0 invisible pointer-events-none'
                    }`}>
                        <div className={`absolute inset-0 backdrop-blur-xl ${isDark ? 'bg-slate-950/95' : 'bg-white/95'}`} onClick={() => setIsMenuOpen(false)}></div>
                        <nav className="relative flex flex-col items-center justify-center h-full gap-8 text-2xl font-bold">
                            <a href="#features" onClick={() => setIsMenuOpen(false)} className={`${isDark ? 'text-white' : 'text-slate-900'}`}>Features</a>
                            <a href="#solutions" onClick={() => setIsMenuOpen(false)} className={`${isDark ? 'text-white' : 'text-slate-900'}`}>Solutions</a>
                            <a href="#pricing" onClick={() => setIsMenuOpen(false)} className={`${isDark ? 'text-white' : 'text-slate-900'}`}>Pricing</a>
                            <div className={`w-12 h-1 ${isDark ? 'bg-white/10' : 'bg-slate-200'} rounded-full my-4`}></div>
                            {auth.user ? (
                                <Link
                                    href={auth.user.roles?.some(r => r.name === 'super-admin') 
                                        ? route('admin.dashboard') 
                                        : (auth.user.shop ? route('dashboard', { subdomain: auth.user.shop.slug }) : '#')
                                    }
                                    className="text-indigo-500"
                                    onClick={() => setIsMenuOpen(false)}
                                >
                                    Dashboard
                                </Link>
                            ) : (
                                <>
                                    <Link href={route('login')} onClick={() => setIsMenuOpen(false)} className={isDark ? 'text-white' : 'text-slate-900'}>Log in</Link>
                                    <Link 
                                        href={route('register')} 
                                        onClick={() => setIsMenuOpen(false)}
                                        className="px-10 py-4 bg-indigo-600 text-white rounded-2xl shadow-xl shadow-indigo-600/20"
                                    >
                                        Start Free Trial
                                    </Link>
                                </>
                            )}
                            
                            <button 
                                onClick={() => setIsMenuOpen(false)}
                                className={`mt-10 p-4 rounded-full ${isDark ? 'bg-white/5 text-white' : 'bg-slate-100 text-slate-900'}`}
                            >
                                <svg className="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </nav>
                    </div>

                    <main className="flex-1 flex flex-col items-center justify-center text-center px-4 w-full max-w-5xl mx-auto mt-16 mb-24 z-10">
                        {usePage().props.announcements?.length > 0 && (
                            <div className={`inline-flex items-center gap-3 px-4 py-1.5 rounded-full border backdrop-blur-md text-sm font-medium mb-10 ${
                                isDark
                                    ? 'border-indigo-500/30 bg-indigo-500/10 text-indigo-300'
                                    : 'border-indigo-300/50 bg-indigo-100/50 text-indigo-600'
                            }`}>
                                <span className={`flex h-2 w-2 rounded-full animate-ping ${
                                    isDark ? 'bg-indigo-400' : 'bg-indigo-500'
                                }`}></span>
                                {usePage().props.announcements[0].title}
                            </div>
                        )}

                        <h1 className={`text-5xl md:text-7xl lg:text-8xl font-black tracking-tighter leading-tight mb-8 ${
                            isDark
                                ? 'text-white'
                                : 'text-slate-900'
                        }`}>
                            Manage your <span className={`text-transparent bg-clip-text ${
                                isDark
                                    ? 'bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400'
                                    : 'bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500'
                            }`}>entire business</span><br className="hidden md:block"/> from one dashboard.
                        </h1>

                        <p className={`text-lg md:text-xl max-w-2xl mb-12 leading-relaxed font-light ${
                            isDark ? 'text-slate-400' : 'text-slate-600'
                        }`}>
                            The ultimate all-in-one Point of Sale, Inventory, and Analytics platform designed exclusively to scale with your business.
                        </p>

                        <div className="flex flex-col sm:flex-row gap-5 mb-20 w-full sm:w-auto">
                            <Link href={route('register')} className={`px-8 py-4 text-base font-bold rounded-full shadow-lg transition-all transform hover:scale-105 flex items-center justify-center gap-2 ${
                                isDark
                                    ? 'text-slate-900 bg-white shadow-[0_0_30px_rgba(255,255,255,0.2)]'
                                    : 'text-white bg-indigo-600 shadow-[0_0_30px_rgba(79,70,229,0.3)]'
                            }`}>
                                Open Your Shop Now
                                <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fillRule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clipRule="evenodd" />
                                </svg>
                            </Link>
                            <a href="#features" className={`px-8 py-4 text-base font-bold rounded-full transition-all flex items-center justify-center ${
                                isDark
                                    ? 'text-white bg-white/5 hover:bg-white/10 border border-white/10'
                                    : 'text-slate-900 bg-slate-200/50 hover:bg-slate-300/50 border border-slate-300'
                            }`}>
                                Explore Features
                            </a>
                        </div>

                        {/* Pricing Section */}
                        <section id="pricing" className="mt-40 w-full max-w-7xl mx-auto scroll-mt-20">
                            <div className="text-center mb-16">
                                <h2 className={`text-4xl md:text-5xl font-black mb-4 tracking-tight ${
                                    isDark ? 'text-white' : 'text-slate-900'
                                }`}>Simple, Transparent <span className="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-400">Pricing</span></h2>
                                <p className={`text-lg max-w-2xl mx-auto ${
                                    isDark ? 'text-slate-400' : 'text-slate-600'
                                }`}>Pay monthly for only the modules you use. POS is mandatory for every shop.</p>
                            </div>

                            <div className="grid grid-cols-1 md:grid-cols-3 gap-8 px-4">
                                {plans?.map((plan) => {
                                    const Icon = moduleIcons[plan.slug] ?? Globe;
                                    const currency = usePage().props.currency || 'BDT';
                                    const price = currency === 'BDT' ? `BDT ${Number(plan.price_bdt).toLocaleString()}` : `$${Number(plan.price_usd).toLocaleString()}`;

                                    return (
                                    <div key={plan.id} className={`p-8 rounded-3xl border flex flex-col transition-all duration-300 group shadow-2xl ${
                                        isDark
                                            ? 'border-white/5 bg-white/5 backdrop-blur-sm'
                                            : 'border-slate-200 bg-slate-50/50'
                                    }`}>
                                        <div className="mb-6">
                                            <div className={`w-12 h-12 rounded-2xl flex items-center justify-center mb-4 ${
                                                isDark
                                                    ? 'bg-white/10 text-slate-300'
                                                    : 'bg-slate-200 text-slate-600'
                                            }`}>
                                                <Icon className="w-6 h-6" />
                                            </div>
                                            <h3 className={`text-2xl font-bold mb-2 ${
                                                isDark ? 'text-white' : 'text-slate-900'
                                            }`}>{plan.name}</h3>
                                            <div className={`flex items-baseline gap-1 mb-2 ${
                                                isDark ? 'text-slate-500' : 'text-slate-600'
                                             }`}>
                                                 <span className={`text-3xl font-black ${
                                                     isDark ? 'text-white' : 'text-slate-900'
                                                 }`}>{price}</span>
                                                 <span>/month</span>
                                             </div>
                                             <p className={`text-sm ${
                                                 isDark ? 'text-slate-400' : 'text-slate-600'
                                             }`}>
                                                 {plan.slug === 'enterprise' ? 'Custom solutions for large businesses.' : 'Scale your business with this plan.'}
                                             </p>
                                         </div>
                                         <ul className={`space-y-4 mb-10 flex-1 ${
                                             isDark ? 'text-slate-300' : 'text-slate-700'
                                         }`}>
                                             {plan.features?.map((feature, i) => (
                                                <li key={i} className="flex items-center gap-3 text-sm">
                                                    <Check className={`w-4 h-4 ${isDark ? 'text-slate-500' : 'text-slate-400'}`} />
                                                    <span className="capitalize">{feature.replace('_', ' ')}</span>
                                                </li>
                                             ))}
                                         </ul>
                                         <Link
                                             href={route('register')}
                                             className={`w-full py-3 px-6 rounded-xl font-bold text-center transition-all ${
                                                 isDark
                                                     ? 'border border-white/10 bg-white/5 text-white hover:bg-white/10'
                                                     : 'border border-slate-300 bg-slate-100 text-slate-900 hover:bg-slate-200'
                                             }`}
                                         >
                                             Get Started
                                         </Link>
                                     </div>
                                 )})}
                             </div>
                        </section>

                        {/* Free Trial Section */}
                        <section className="mt-40 mb-20 w-full max-w-5xl mx-auto px-4">
                            <div className={`relative p-12 rounded-[40px] overflow-hidden border group ${
                                isDark
                                    ? 'border-white/10 bg-gradient-to-br from-indigo-600/20 to-purple-600/20 backdrop-blur-xl'
                                    : 'border-indigo-300/30 bg-gradient-to-br from-indigo-100/30 to-purple-100/30 backdrop-blur-sm'
                            }`}>
                                {isDark ? (
                                    <>
                                        <div className="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-indigo-500/20 rounded-full blur-[80px] group-hover:bg-indigo-500/30 transition-colors duration-700"></div>
                                        <div className="absolute bottom-0 left-0 -ml-20 -mb-20 w-96 h-96 bg-purple-500/20 rounded-full blur-[80px] group-hover:bg-purple-500/30 transition-colors duration-700"></div>
                                    </>
                                ) : (
                                    <>
                                        <div className="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-indigo-300/20 rounded-full blur-[80px] group-hover:bg-indigo-300/30 transition-colors duration-700"></div>
                                        <div className="absolute bottom-0 left-0 -ml-20 -mb-20 w-96 h-96 bg-purple-300/20 rounded-full blur-[80px] group-hover:bg-purple-300/30 transition-colors duration-700"></div>
                                    </>
                                )}

                                <div className={`relative z-10 flex flex-col md:flex-row items-center justify-between gap-12`}>
                                    <div className="flex-1 text-left">
                                        <h2 className={`text-3xl md:text-5xl font-black mb-6 leading-tight ${
                                            isDark ? 'text-white' : 'text-slate-900'
                                        }`}>Ready to transform your business?</h2>
                                        <div className="flex flex-col gap-4">
                                            <div className={`flex items-center gap-4 ${
                                                isDark ? 'text-slate-300' : 'text-slate-700'
                                            }`}>
                                                <div className={`p-2 rounded-lg ${
                                                    isDark
                                                        ? 'bg-green-500/20 text-green-400'
                                                        : 'bg-green-100 text-green-600'
                                                }`}>
                                                    <Clock className="w-5 h-5" />
                                                </div>
                                                <p className="text-lg">Setup in less than 5 minutes</p>
                                            </div>
                                            <div className={`flex items-center gap-4 ${
                                                isDark ? 'text-slate-300' : 'text-slate-700'
                                            }`}>
                                                <div className={`p-2 rounded-lg ${
                                                    isDark
                                                        ? 'bg-blue-500/20 text-blue-400'
                                                        : 'bg-blue-100 text-blue-600'
                                                }`}>
                                                    <LayoutDashboard className="w-5 h-5" />
                                                </div>
                                                <p className="text-lg">No credit card required for free trial</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="flex flex-col gap-4 min-w-[280px]">
                                        <Link href={route('register')} className={`px-10 py-5 font-black text-xl rounded-2xl transform hover:scale-105 active:scale-95 transition-all ${
                                            isDark
                                                ? 'bg-white text-slate-900 shadow-2xl shadow-white/10'
                                                : 'bg-indigo-600 text-white shadow-2xl shadow-indigo-600/20'
                                        }`}>
                                            Start Free Trial Now
                                        </Link>
                                        <p className={`text-sm text-center ${
                                            isDark ? 'text-slate-500' : 'text-slate-600'
                                        }`}>{`Join 1,000+ businesses using ${appName}`}</p>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </main>

                    <footer className={`text-center py-8 border-t text-sm z-10 w-full max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center px-6 ${
                        isDark
                            ? 'border-white/5 text-slate-500'
                            : 'border-slate-300 text-slate-600'
                    }`}>
                        <p>&copy; {new Date().getFullYear()} {appName}.</p>
                        <div className={`flex gap-4 mt-4 sm:mt-0 ${
                            isDark ? 'hover:text-white' : 'hover:text-slate-900'
                        }`}>
                            <a href="#" className="transition">Privacy</a>
                            <a href="#" className="transition">Terms</a>
                        </div>
                    </footer>
                </div>
            </div>
        </div>
    );
}
