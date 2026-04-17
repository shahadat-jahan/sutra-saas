import Checkbox from '@/Components/Checkbox';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import GuestLayout from '@/Layouts/GuestLayout';
import { Head, Link, useForm } from '@inertiajs/react';

export default function Login({ status, canResetPassword }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const submit = (e) => {
        e.preventDefault();

        post(route('login'), {
            onFinish: () => reset('password'),
        });
    };

    return (
        <GuestLayout>
            <Head title="Log in" />

            {status && (
                <div className="mb-4 text-sm font-medium text-green-600">
                    {status}
                </div>
            )}

            <form onSubmit={submit}>
                <div>
                    <InputLabel htmlFor="email" value="Email" className="text-slate-300" />

                    <TextInput
                        id="email"
                        type="email"
                        name="email"
                        value={data.email}
                        className="mt-1 block w-full bg-white/5 border-white/10 text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-xl transition-all"
                        autoComplete="username"
                        isFocused={true}
                        onChange={(e) => setData('email', e.target.value)}
                    />

                    <InputError message={errors.email} className="mt-2" />
                </div>

                <div className="mt-4">
                    <InputLabel htmlFor="password" value="Password" className="text-slate-300" />

                    <TextInput
                        id="password"
                        type="password"
                        name="password"
                        value={data.password}
                        className="mt-1 block w-full bg-white/5 border-white/10 text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-xl transition-all"
                        autoComplete="current-password"
                        onChange={(e) => setData('password', e.target.value)}
                    />

                    <InputError message={errors.password} className="mt-2" />
                </div>

                <div className="mt-4 block">
                    <label className="flex items-center">
                        <Checkbox
                            name="remember"
                            checked={data.remember}
                            onChange={(e) =>
                                setData('remember', e.target.checked)
                            }
                        />
                        <span className="ms-2 text-sm text-slate-400">
                            Remember me
                        </span>
                    </label>
                </div>

                <div className="mt-8 flex flex-col gap-4">
                    <PrimaryButton className="w-full justify-center py-4 text-base bg-indigo-600 hover:bg-indigo-500 rounded-xl transition-all shadow-[0_0_20px_rgba(79,70,229,0.3)]" disabled={processing}>
                        Log in
                    </PrimaryButton>

                    {canResetPassword && (
                        <div className="flex items-center justify-center">
                            <Link
                                href={route('password.request')}
                                className="text-sm text-slate-400 hover:text-white transition-colors underline decoration-slate-400/30 hover:decoration-white"
                            >
                                Forgot your password?
                            </Link>
                        </div>
                    )}
                </div>
            </form>
        </GuestLayout>
    );
}
