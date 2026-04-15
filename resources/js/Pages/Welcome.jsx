import { Head, Link } from '@inertiajs/react';

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
                            <div className="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/30">
                                <span className="text-white font-bold text-xl">S</span>
                            </div>
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
                            Sutra v2.0 POS Architecture is now live
                        </div>
                        
                        <h1 className="text-5xl md:text-7xl lg:text-8xl font-black text-white tracking-tighter leading-tight mb-8">
                            Manage your <span className="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400">entire business</span><br className="hidden md:block"/> from one dashboard.
                        </h1>
                        
                        <p className="text-lg md:text-xl text-slate-400 max-w-2xl mb-12 leading-relaxed font-light">
                            The ultimate all-in-one Point of Sale, Inventory, and Analytics platform designed exclusively to scale with your retail store, pharmacy, and enterprise.
                        </p>
                        
                        <div className="flex flex-col sm:flex-row gap-5 mb-20 w-full sm:w-auto">
                            <Link href={route('register')} className="px-8 py-4 text-base font-bold text-white bg-white text-slate-900 rounded-full shadow-[0_0_30px_rgba(255,255,255,0.2)] transition-all transform hover:scale-105 flex items-center justify-center gap-2">
                                Open Your Shop Now
                                <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fillRule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clipRule="evenodd" />
                                </svg>
                            </Link>
                            <a href="#features" className="px-8 py-4 text-base font-bold text-white bg-white/5 hover:bg-white/10 border border-white/10 rounded-full backdrop-blur-sm transition-all flex items-center justify-center">
                                Explore Features
                            </a>
                        </div>

                        {/* Glass dashboard preview */}
                        <div className="relative w-full aspect-video max-w-4xl rounded-2xl border border-white/10 bg-white/5 backdrop-blur-xl p-2 sm:p-4 shadow-2xl overflow-hidden group">
                            <div className="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent z-10"></div>
                            
                            {/* Fake UI Header */}
                            <div className="flex gap-2 mb-4 px-2 items-center border-b border-white/5 pb-4">
                                <div className="flex gap-1.5">
                                    <div className="w-3 h-3 rounded-full bg-red-500/80"></div>
                                    <div className="w-3 h-3 rounded-full bg-yellow-500/80"></div>
                                    <div className="w-3 h-3 rounded-full bg-green-500/80"></div>
                                </div>
                                <div className="mx-auto w-1/3 h-4 bg-white/5 rounded-full"></div>
                            </div>
                            
                            {/* Fake UI Content */}
                            <div className="grid grid-cols-4 gap-4 h-full pb-12 opacity-80 group-hover:opacity-100 transition duration-500">
                                <div className="col-span-1 flex flex-col gap-3 border-r border-white/5 pr-4">
                                    <div className="h-8 bg-white/10 rounded-md"></div>
                                    <div className="h-8 bg-white/5 rounded-md"></div>
                                    <div className="h-8 bg-white/5 rounded-md"></div>
                                    <div className="h-8 bg-white/5 rounded-md mt-auto"></div>
                                </div>
                                <div className="col-span-3 flex flex-col gap-4 pl-2">
                                    <div className="flex gap-4">
                                        <div className="h-24 flex-1 bg-gradient-to-br from-indigo-500/20 to-purple-500/10 border border-white/5 rounded-xl"></div>
                                        <div className="h-24 flex-1 bg-white/5 border border-white/5 rounded-xl"></div>
                                        <div className="h-24 flex-1 bg-white/5 border border-white/5 rounded-xl"></div>
                                    </div>
                                    <div className="h-full bg-white/5 border border-white/5 rounded-xl"></div>
                                </div>
                            </div>
                        </div>
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
