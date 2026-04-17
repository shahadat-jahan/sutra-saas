import ApplicationLogo from '@/Components/ApplicationLogo';
import { Link } from '@inertiajs/react';

export default function GuestLayout({ children }) {
    return (
        <div className="flex min-h-screen flex-col items-center bg-slate-950 pt-6 sm:justify-center sm:pt-0 relative overflow-hidden">
            {/* Background Decorations */}
            <div className="absolute -top-20 -left-20 w-96 h-96 bg-indigo-600 rounded-full mix-blend-multiply filter blur-[100px] opacity-20"></div>
            <div className="absolute -bottom-20 -right-20 w-96 h-96 bg-purple-600 rounded-full mix-blend-multiply filter blur-[100px] opacity-20"></div>

            <div className="relative z-10 transition-all duration-500">
                <Link href="/">
                    <img src="/images/logo.png" className="w-20 h-20 rounded-2xl shadow-2xl shadow-indigo-500/20" alt="Sutra Logo" />
                </Link>
            </div>

            <div className="relative z-10 mt-6 w-full overflow-hidden bg-white/5 backdrop-blur-xl px-10 py-8 shadow-[0_0_50px_rgba(0,0,0,0.3)] border border-white/10 sm:max-w-md sm:rounded-[2rem] transition-all">
                {children}
            </div>
        </div>
    );
}
