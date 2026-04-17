import { Head, Link, usePage } from '@inertiajs/react';
import { Check, Shield, Zap, Building2, Globe, BarChart3, Users, LayoutDashboard, Clock } from 'lucide-react';

export default function Welcome({ auth }) {
    return (
        <>
            <Head title="Sutra SaaS - Elevate Your Business" />
            <div className="min-h-screen bg-slate-950 font-sans text-slate-300 selection:bg-indigo-500 selection:text-white overflow-hidden relative">
                {/* Background Blobs for Glassmorphism */}
                <div className="absolute -top-20 -left-20 w-96 h-96 bg-purple-600 rounded-full mix-blend-multiply filter blur-[100px] opacity-30 animate-pulse"></div>
                <div className="absolute top-40 -right-20 w-96 h-96 bg-indigo-600 rounded-full mix-blend-multiply filter blur-[100px] opacity-30 animate-pulse" style={{animationDelay: "2s"}}></div>
                <div className="absolute -bottom-40 left-1/2 w-96 h-96 bg-blue-600 rounded-full mix-blend-multiply filter blur-[100px] opacity-30 animate-pulse" style={{animationDelay: "4s"}}></div>

                <div className="relative z-10 flex flex-col min-h-screen">
                    <header className="flex items-center justify-between px-6 py-6 w-full max-w-7xl mx-auto">
                        <div className="flex items-center gap-3">
                            <img src="/images/logo.png" className="w-12 h-12 rounded-xl shadow-lg shadow-indigo-500/30" alt="Sutra Logo" />
                            <span className="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-white to-slate-400 tracking-tight">Sutra</span>
                        </div>
                        
                        <nav className="hidden md:flex gap-8 text-sm font-medium z-20">
                            <a href="#features" className="hover:text-white transition cursor-pointer">Features</a>
                            <a href="#solutions" className="hover:text-white transition cursor-pointer">Solutions</a>
                            <a href="#pricing" className="hover:text-white transition cursor-pointer">Pricing</a>
                        </nav>

                        <nav className="flex gap-4 items-center z-20">
                            {auth.user ? (
                                <Link
                                    href={auth.user?.shop ? route('dashboard', { subdomain: auth.user.shop.slug }) : '#'}
                                    className="px-6 py-2.5 text-sm font-bold text-white bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/10 rounded-full transition-all"
                                >
                                    Go to Dashboard
                                </Link>
                            ) : (
                                <>
                                    <Link
                                        href={route('login')}
                                        className="hidden sm:block px-5 py-2.5 text-sm font-semibold hover:text-white transition-colors"
                                    >
                                        Log in
                                    </Link>
                                    <Link
                                        href={route('register')}
                                        className="px-6 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 shadow-[0_0_20px_rgba(79,70,229,0.4)] rounded-full transition-all transform hover:scale-105"
                                    >
                                        Start Free Trial
                                    </Link>
                                </>
                            )}
                        </nav>
                    </header>

                    <main className="flex-1 flex flex-col items-center justify-center text-center px-4 w-full max-w-5xl mx-auto mt-16 mb-24 z-10">
                        <div className="inline-flex items-center gap-3 px-4 py-1.5 rounded-full border border-indigo-500/30 bg-indigo-500/10 text-indigo-300 text-sm font-medium mb-10 backdrop-blur-md">
                            <span className="flex h-2 w-2 rounded-full bg-indigo-400 animate-ping"></span>
                            Sutra v1.0 POS Architecture is now live
                        </div>
                        
                        <h1 className="text-5xl md:text-7xl lg:text-8xl font-black text-white tracking-tighter leading-tight mb-8">
                            Manage your <span className="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400">entire business</span><br className="hidden md:block"/> from one dashboard.
                        </h1>
                        
                        <p className="text-lg md:text-xl text-slate-400 max-w-2xl mb-12 leading-relaxed font-light">
                            The ultimate all-in-one Point of Sale, Inventory, and Analytics platform designed exclusively to scale with your retail store, pharmacy, and enterprise.
                        </p>
                        
                        <div className="flex flex-col sm:flex-row gap-5 mb-20 w-full sm:w-auto">
                            <Link href={route('register')} className="px-8 py-4 text-base font-bold text-slate-900 bg-white rounded-full shadow-[0_0_30px_rgba(255,255,255,0.2)] transition-all transform hover:scale-105 flex items-center justify-center gap-2">
                                Open Your Shop Now
                                <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fillRule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clipRule="evenodd" />
                                </svg>
                            </Link>
                            <a href="#features" className="px-8 py-4 text-base font-bold text-white bg-white/5 hover:bg-white/10 border border-white/10 rounded-full backdrop-blur-sm transition-all flex items-center justify-center">
                                Explore Features
                            </a>
                        </div>

                        {/* Pricing Section */}
                        <section id="pricing" className="mt-40 w-full max-w-7xl mx-auto scroll-mt-20">
                            <div className="text-center mb-16">
                                <h2 className="text-4xl md:text-5xl font-black text-white mb-4 tracking-tight">Simple, Transparent <span className="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-400">Pricing</span></h2>
                                <p className="text-slate-400 text-lg max-w-2xl mx-auto">Choose the perfect plan for your business scale. No hidden fees, cancel anytime.</p>
                            </div>

                            <div className="grid grid-cols-1 md:grid-cols-3 gap-8 px-4">
                                {usePage().props.plans.map((plan) => (
                                    <div key={plan.value} className={`p-8 rounded-3xl border flex flex-col transition-all duration-300 group shadow-2xl ${
                                        plan.value === 2 
                                        ? 'border-indigo-500/50 bg-indigo-500/5 backdrop-blur-md relative transform scale-105' 
                                        : 'border-white/5 bg-white/5 backdrop-blur-sm'
                                    }`}>
                                        {plan.value === 2 && (
                                            <div className="absolute -top-4 left-1/2 -translate-x-1/2 px-4 py-1 bg-indigo-500 text-white text-xs font-bold rounded-full uppercase tracking-widest shadow-lg shadow-indigo-500/50">
                                                Most Popular
                                            </div>
                                        )}
                                        <div className="mb-6">
                                            <div className={`w-12 h-12 rounded-2xl flex items-center justify-center mb-4 ${
                                                plan.value === 2 ? 'bg-indigo-500 text-white shadow-lg' : 'bg-white/10 text-slate-300'
                                            }`}>
                                                {plan.value === 1 ? <Zap className="w-6 h-6" /> : (plan.value === 2 ? <Shield className="w-6 h-6" /> : <Building2 className="w-6 h-6" />)}
                                            </div>
                                            <h3 className="text-2xl font-bold text-white mb-2">{plan.label}</h3>
                                            <div className="flex items-baseline gap-1 mb-2">
                                                <span className="text-4xl font-black text-white">{plan.price}</span>
                                                {plan.value !== 3 && <span className="text-slate-500">/{plan.value === 1 ? 'forever' : 'month'}</span>}
                                            </div>
                                            <p className="text-slate-400 text-sm">
                                                {plan.value === 1 ? 'Perfect for small shops.' : (plan.value === 2 ? 'Scale your growing business.' : 'Tailored enterprise solutions.')}
                                            </p>
                                        </div>
                                        <ul className="space-y-4 mb-10 flex-1">
                                            {plan.modules.map((module) => (
                                                <li key={module} className="flex items-center gap-3 text-slate-300 text-sm">
                                                    <Check className={`w-4 h-4 ${plan.value === 2 ? 'text-indigo-400' : 'text-slate-500'}`} /> 
                                                    <span className="capitalize">{module.replace('_', ' ')}</span>
                                                </li>
                                            ))}
                                        </ul>
                                        <Link 
                                            href={route('register', { plan: plan.value })} 
                                            className={`w-full py-3 px-6 rounded-xl font-bold text-center transition-all ${
                                                plan.value === 2 
                                                ? 'bg-indigo-500 text-white hover:bg-indigo-600 shadow-lg shadow-indigo-500/25' 
                                                : 'border border-white/10 bg-white/5 text-white hover:bg-white/10'
                                            }`}
                                        >
                                            {plan.value === 1 ? 'Start 14-Day Free Trial' : (plan.value === 3 ? 'Contact Sales' : 'Get Started')}
                                        </Link>
                                    </div>
                                ))}
                            </div>
                        </section>

                        {/* Free Trial Section */}
                        <section className="mt-40 mb-20 w-full max-w-5xl mx-auto px-4">
                            <div className="relative p-12 rounded-[40px] overflow-hidden border border-white/10 bg-gradient-to-br from-indigo-600/20 to-purple-600/20 backdrop-blur-xl group">
                                <div className="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-indigo-500/20 rounded-full blur-[80px] group-hover:bg-indigo-500/30 transition-colors duration-700"></div>
                                <div className="absolute bottom-0 left-0 -ml-20 -mb-20 w-96 h-96 bg-purple-500/20 rounded-full blur-[80px] group-hover:bg-purple-500/30 transition-colors duration-700"></div>
                                
                                <div className="relative z-10 flex flex-col md:flex-row items-center justify-between gap-12">
                                    <div className="flex-1 text-left">
                                        <h2 className="text-3xl md:text-5xl font-black text-white mb-6 leading-tight">Ready to transform your business?</h2>
                                        <div className="flex flex-col gap-4">
                                            <div className="flex items-center gap-4 text-slate-300">
                                                <div className="p-2 rounded-lg bg-green-500/20 text-green-400">
                                                    <Clock className="w-5 h-5" />
                                                </div>
                                                <p className="text-lg">Setup in less than 5 minutes</p>
                                            </div>
                                            <div className="flex items-center gap-4 text-slate-300">
                                                <div className="p-2 rounded-lg bg-blue-500/20 text-blue-400">
                                                    <LayoutDashboard className="w-5 h-5" />
                                                </div>
                                                <p className="text-lg">No credit card required for free trial</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="flex flex-col gap-4 min-w-[280px]">
                                        <Link href={route('register')} className="px-10 py-5 bg-white text-slate-900 font-black text-xl rounded-2xl shadow-2xl shadow-white/10 transform hover:scale-105 active:scale-95 transition-all">
                                            Start Free Trial Now
                                        </Link>
                                        <p className="text-slate-500 text-sm text-center">Join 1,000+ businesses using Sutra</p>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </main>

                    <footer className="text-center py-8 border-t border-white/5 text-slate-500 text-sm z-10 w-full max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center px-6">
                        <p>&copy; {new Date().getFullYear()} Sutra SaaS. Multi-tenant Architecture.</p>
                        <div className="flex gap-4 mt-4 sm:mt-0">
                            <a href="#" className="hover:text-white transition">Privacy</a>
                            <a href="#" className="hover:text-white transition">Terms</a>
                        </div>
                    </footer>
                </div>
            </div>
        </>
    );
}
