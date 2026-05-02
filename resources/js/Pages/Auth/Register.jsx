import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import GuestLayout from '@/Layouts/GuestLayout';
import { Head, Link, useForm, usePage } from '@inertiajs/react';
import { useTheme } from '@/Support/ThemeProvider';

export default function Register() {
    const { mode } = useTheme();
    const isDark = mode === 'dark';
    const { business_types, appName } = usePage().props;
    const queryParams = new URLSearchParams(window.location.search);
    const initialPlan = parseInt(queryParams.get('plan') || '1'); // Default to 1 (Basic)

    const { data, setData, post, processing, errors, reset } = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        shop_name: '',
        business_type: 1,
        plan: initialPlan,
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
                <h1 className={`text-3xl font-black mb-2 transition-colors ${isDark ? 'text-white' : 'text-slate-900'}`}>Create Your Shop</h1>
                {data.plan === 1 ? (
                    <p className={`${isDark ? 'text-slate-400' : 'text-slate-600'} transition-colors`}>Start your <span className="text-indigo-400 font-bold">14-day free trial</span> today.</p>
                ) : (
                    <p className={`${isDark ? 'text-slate-400' : 'text-slate-600'} transition-colors`}>Join {appName} as a <span className="text-indigo-400 font-bold capitalize">
                        {usePage().props.plans.find(p => p.value === data.plan)?.label || 'Partner'}
                    </span>.</p>
                )}
            </div>

            <form onSubmit={submit} className="space-y-5">
                <div className="mb-4">
                    <InputLabel htmlFor="shop_name" value="Shop Name" className={isDark ? 'text-slate-300' : 'text-slate-700'} />

                    <TextInput
                        id="shop_name"
                        name="shop_name"
                        value={data.shop_name}
                        className={`mt-1 block w-full rounded-xl transition-all ${
                            isDark ? 'bg-white/5 border-white/10 text-white' : 'bg-slate-50 border-slate-200 text-slate-900'
                        }`}
                        autoComplete="organization"
                        isFocused={true}
                        onChange={(e) => setData('shop_name', e.target.value)}
                        required
                    />

                    <InputError message={errors.shop_name} className="mt-2" />
                </div>

                <div className="mb-4">
                    <InputLabel htmlFor="business_type" value="Business Type" className={isDark ? 'text-slate-300' : 'text-slate-700'} />
                    
                    <select
                        id="business_type"
                        className={`mt-1 block w-full rounded-xl shadow-sm transition-all ${
                            isDark ? 'bg-white/5 border-white/10 text-white' : 'bg-slate-50 border-slate-200 text-slate-900'
                        }`}
                        value={data.business_type}
                        onChange={(e) => setData('business_type', parseInt(e.target.value))}
                    >
                        {business_types.map((type) => (
                            <option key={type.value} value={type.value} className={isDark ? 'bg-slate-900 text-white' : 'bg-white text-slate-900'}>
                                {type.label}
                            </option>
                        ))}
                    </select>

                    <InputError message={errors.business_type} className="mt-2" />
                </div>
                
                <hr className={`my-8 ${isDark ? 'border-white/5' : 'border-slate-100'}`} />
                
                <div>
                    <InputLabel htmlFor="name" value="Name" className={isDark ? 'text-slate-300' : 'text-slate-700'} />

                    <TextInput
                        id="name"
                        name="name"
                        value={data.name}
                        className={`mt-1 block w-full rounded-xl transition-all ${
                            isDark ? 'bg-white/5 border-white/10 text-white' : 'bg-slate-50 border-slate-200 text-slate-900'
                        }`}
                        autoComplete="name"
                        onChange={(e) => setData('name', e.target.value)}
                        required
                    />

                    <InputError message={errors.name} className="mt-2" />
                </div>

                <div className="mt-4">
                    <InputLabel htmlFor="email" value="Email" className={isDark ? 'text-slate-300' : 'text-slate-700'} />

                    <TextInput
                        id="email"
                        type="email"
                        name="email"
                        value={data.email}
                        className={`mt-1 block w-full rounded-xl transition-all ${
                            isDark ? 'bg-white/5 border-white/10 text-white' : 'bg-slate-50 border-slate-200 text-slate-900'
                        }`}
                        autoComplete="username"
                        onChange={(e) => setData('email', e.target.value)}
                        required
                    />

                    <InputError message={errors.email} className="mt-2" />
                </div>

                <div className="mt-4">
                    <InputLabel htmlFor="password" value="Password" className={isDark ? 'text-slate-300' : 'text-slate-700'} />

                    <TextInput
                        id="password"
                        type="password"
                        name="password"
                        value={data.password}
                        className={`mt-1 block w-full rounded-xl transition-all ${
                            isDark ? 'bg-white/5 border-white/10 text-white' : 'bg-slate-50 border-slate-200 text-slate-900'
                        }`}
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
                        className={isDark ? 'text-slate-300' : 'text-slate-700'}
                    />

                    <TextInput
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        value={data.password_confirmation}
                        className={`mt-1 block w-full rounded-xl transition-all ${
                            isDark ? 'bg-white/5 border-white/10 text-white' : 'bg-slate-50 border-slate-200 text-slate-900'
                        }`}
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
                    <PrimaryButton className={`w-full justify-center py-4 text-base rounded-xl transition-all ${
                        isDark 
                            ? 'bg-indigo-600 hover:bg-indigo-500 shadow-[0_0_20px_rgba(79,70,229,0.3)]' 
                            : 'bg-indigo-600 text-white hover:bg-indigo-700 shadow-[0_10px_20px_rgba(79,70,229,0.2)]'
                    }`} disabled={processing}>
                        Create My Account
                    </PrimaryButton>

                    <div className="flex items-center justify-center">
                        <Link
                            href={route('login')}
                            className={`text-sm transition-colors ${
                                isDark ? 'text-slate-400 hover:text-white' : 'text-slate-600 hover:text-slate-900'
                            }`}
                        >
                            Already have an account? <span className="underline">Log in</span>
                        </Link>
                    </div>
                </div>
            </form>
        </GuestLayout>
    );
}
