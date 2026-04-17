import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import GuestLayout from '@/Layouts/GuestLayout';
import { Head, Link, useForm, usePage } from '@inertiajs/react';

export default function Register() {
    const { business_types } = usePage().props;
    const { data, setData, post, processing, errors, reset } = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        shop_name: '',
        business_type: 1,
    });

    const submit = (e) => {
        e.preventDefault();

        post(route('register'), {
            onFinish: () => reset('password', 'password_confirmation'),
        });
    };

    return (
        <GuestLayout>
            <div className="mb-8 text-center">
                <h1 className="text-3xl font-black text-white mb-2">Create Your Shop</h1>
                <p className="text-slate-400">Start your <span className="text-indigo-400 font-bold">14-day free trial</span> today.</p>
            </div>

            <form onSubmit={submit} className="space-y-5">
                <div className="mb-4">
                    <InputLabel htmlFor="shop_name" value="Shop Name" className="text-slate-300" />

                    <TextInput
                        id="shop_name"
                        name="shop_name"
                        value={data.shop_name}
                        className="mt-1 block w-full bg-white/5 border-white/10 text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-xl transition-all"
                        autoComplete="organization"
                        isFocused={true}
                        onChange={(e) => setData('shop_name', e.target.value)}
                        required
                    />

                    <InputError message={errors.shop_name} className="mt-2" />
                </div>

                <div className="mb-4">
                    <InputLabel htmlFor="business_type" value="Business Type" className="text-slate-300" />
                    
                    <select
                        id="business_type"
                        className="mt-1 block w-full bg-white/5 border-white/10 text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm transition-all"
                        value={data.business_type}
                        onChange={(e) => setData('business_type', parseInt(e.target.value))}
                    >
                        {business_types.map((type) => (
                            <option key={type.value} value={type.value} className="bg-slate-900">
                                {type.label}
                            </option>
                        ))}
                    </select>

                    <InputError message={errors.business_type} className="mt-2" />
                </div>
                
                <hr className="my-8 border-white/5" />
                
                <div>
                    <InputLabel htmlFor="name" value="Name" className="text-slate-300" />

                    <TextInput
                        id="name"
                        name="name"
                        value={data.name}
                        className="mt-1 block w-full bg-white/5 border-white/10 text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-xl transition-all"
                        autoComplete="name"
                        onChange={(e) => setData('name', e.target.value)}
                        required
                    />

                    <InputError message={errors.name} className="mt-2" />
                </div>

                <div className="mt-4">
                    <InputLabel htmlFor="email" value="Email" className="text-slate-300" />

                    <TextInput
                        id="email"
                        type="email"
                        name="email"
                        value={data.email}
                        className="mt-1 block w-full bg-white/5 border-white/10 text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-xl transition-all"
                        autoComplete="username"
                        onChange={(e) => setData('email', e.target.value)}
                        required
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
                        autoComplete="new-password"
                        onChange={(e) => setData('password', e.target.value)}
                        required
                    />

                    <InputError message={errors.password} className="mt-2" />
                </div>

                <div className="mt-4">
                    <InputLabel
                        htmlFor="password_confirmation"
                        value="Confirm Password"
                        className="text-slate-300"
                    />

                    <TextInput
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        value={data.password_confirmation}
                        className="mt-1 block w-full bg-white/5 border-white/10 text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-xl transition-all"
                        autoComplete="new-password"
                        onChange={(e) =>
                            setData('password_confirmation', e.target.value)
                        }
                        required
                    />

                    <InputError
                        message={errors.password_confirmation}
                        className="mt-2"
                    />
                </div>

                <div className="mt-8 flex flex-col gap-4">
                    <PrimaryButton className="w-full justify-center py-4 text-base bg-indigo-600 hover:bg-indigo-500 rounded-xl transition-all shadow-[0_0_20px_rgba(79,70,229,0.3)]" disabled={processing}>
                        Create My Account
                    </PrimaryButton>

                    <div className="flex items-center justify-center">
                        <Link
                            href={route('login')}
                            className="text-sm text-slate-400 hover:text-white transition-colors"
                        >
                            Already have an account? <span className="underline">Log in</span>
                        </Link>
                    </div>
                </div>
            </form>
        </GuestLayout>
    );
}
