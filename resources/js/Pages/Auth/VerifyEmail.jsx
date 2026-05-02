import PrimaryButton from '@/Components/PrimaryButton';
import GuestLayout from '@/Layouts/GuestLayout';
import { Head, Link, useForm } from '@inertiajs/react';
import { useTheme } from '@/Support/ThemeProvider';

export default function VerifyEmail({ status }) {
    const { mode } = useTheme();
    const isDark = mode === 'dark';
    const { post, processing } = useForm({});

    const submit = (e) => {
        e.preventDefault();

        post(route('verification.send'));
    };

    return (
        <GuestLayout>
            <Head title="Email Verification" />

            <div className={`mb-4 text-sm transition-colors ${isDark ? 'text-slate-400' : 'text-slate-600'}`}>
                Thanks for signing up! Before getting started, could you verify
                your email address by clicking on the link we just emailed to
                you? If you didn't receive the email, we will gladly send you
                another.
            </div>

            {status === 'verification-link-sent' && (
                <div className="mb-4 text-sm font-medium text-green-600">
                    A new verification link has been sent to the email address
                    you provided during registration.
                </div>
            )}

            <form onSubmit={submit}>
                <div className="mt-4 flex items-center justify-between">
                    <PrimaryButton className={`px-6 py-3 rounded-xl transition-all ${
                        isDark 
                            ? 'bg-indigo-600 hover:bg-indigo-500' 
                            : 'bg-indigo-600 text-white hover:bg-indigo-700'
                    }`} disabled={processing}>
                        Resend Verification Email
                    </PrimaryButton>

                    <Link
                        href="/logout"
                        method="post"
                        as="button"
                        className={`text-sm underline transition-colors ${
                            isDark ? 'text-slate-400 hover:text-white' : 'text-slate-600 hover:text-slate-900'
                        }`}
                    >
                        Log Out
                    </Link>
                </div>
            </form>
        </GuestLayout>
    );
}
