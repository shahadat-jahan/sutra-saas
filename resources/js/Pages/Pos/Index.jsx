import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, usePage, useForm } from '@inertiajs/react';
import React, { useState, useMemo } from 'react';
import TextInput from '@/Components/TextInput';
import PrimaryButton from '@/Components/PrimaryButton';
import InputLabel from '@/Components/InputLabel';
import InputError from '@/Components/InputError';
import { ShoppingCart, User, Plus, Search, Trash2, Pill, CheckCircle2, UserPlus } from 'lucide-react';
import { useTheme } from '@/Support/ThemeProvider';

/**
 * POS Component
 */
export default function Index({ products = [], customers = [], enabledModules = [] }) {
    const { auth } = usePage().props;
    const { mode } = useTheme();
    const isDark = mode === 'dark';
    const isPharmaEnabled = enabledModules.includes('pharma');

    const [searchTerm, setSearchTerm] = useState('');
    const [isCreditSale, setIsCreditSale] = useState(false);

    // Inertia form state
    const { data, setData, post, processing, errors } = useForm({
        items: [],
        customer_id: null,
        payment_method: 'Cash',
        total_amount: 0,
        paid_amount: 0,
    });

    // Filtered products based on search
    const filteredProducts = useMemo(() => {
        return products.filter(p =>
            p.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
            (p.attributes?.generic_name?.toLowerCase().includes(searchTerm.toLowerCase()))
        );
    }, [searchTerm, products]);

    const addToCart = (product) => {
        const existing = data.items.find(i => i.id === product.id);
        let newItems;
        if (existing) {
            newItems = data.items.map(i => i.id === product.id ? { ...i, quantity: i.quantity + 1 } : i);
        } else {
            newItems = [...data.items, {
                id: product.id,
                name: product.name,
                price: product.price,
                quantity: 1,
                generic_name: product.attributes?.generic_name
            }];
        }

        const total = newItems.reduce((acc, i) => acc + (i.price * i.quantity), 0);
        setData(d => ({ ...d, items: newItems, total_amount: total }));
    };

    const handleCheckout = (e) => {
        e.preventDefault();
        post(route('tenant.pos.store', { subdomain: auth.user.shop.slug }));
    };

    return (
        <AuthenticatedLayout
            header={<h2 className={`font-semibold text-xl leading-tight ${isDark ? 'text-white' : 'text-slate-800'}`}>Point of Sale</h2>}
        >
            <Head title={`POS - ${usePage().props.appName}`} />

            <div className="py-6 h-[calc(100vh-120px)] overflow-hidden">
                <div className="max-w-[1600px] mx-auto sm:px-6 lg:px-8 h-full">
                    <div className="flex gap-6 h-full">

                        {/* 1. Product Grid Section */}
                        <div className={`flex-1 rounded-2xl shadow-sm border flex flex-col overflow-hidden transition-colors ${
                            isDark ? 'bg-slate-900 border-white/10' : 'bg-white border-slate-200'
                        }`}>
                            <div className={`p-4 border-b flex items-center gap-4 ${isDark ? 'border-white/5' : 'border-slate-100'}`}>
                                <div className="relative flex-1">
                                    <Search className={`absolute left-3 top-3 h-4 w-4 ${isDark ? 'text-slate-500' : 'text-slate-400'}`} />
                                    <TextInput
                                        className={`pl-10 w-full border-none focus:ring-2 focus:ring-indigo-500 ${
                                            isDark ? 'bg-slate-800 text-white placeholder-slate-500' : 'bg-slate-50 text-slate-900'
                                        }`}
                                        placeholder="Search products by name or generic..."
                                        value={searchTerm}
                                        onChange={(e) => setSearchTerm(e.target.value)}
                                    />
                                </div>
                                <div className="flex gap-2">
                                    <button className={`p-2 rounded-lg transition-colors ${
                                        isDark ? 'bg-slate-800 text-slate-400 hover:bg-slate-700' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'
                                    }`}>
                                        <Plus className="h-5 w-5" />
                                    </button>
                                </div>
                            </div>

                            <div className={`flex-1 overflow-y-auto p-6 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 ${
                                isDark ? 'bg-slate-950/50' : 'bg-slate-50/50'
                            }`}>
                                {filteredProducts.map(product => (
                                    <div
                                        key={product.id}
                                        onClick={() => addToCart(product)}
                                        className={`p-4 rounded-xl border cursor-pointer transition-all group ${
                                            isDark 
                                            ? 'bg-slate-900 border-white/10 hover:border-indigo-500 hover:bg-slate-800/50' 
                                            : 'bg-white border-slate-200 hover:border-indigo-500 hover:shadow-md'
                                        }`}
                                    >
                                        <div className="flex justify-between items-start mb-2">
                                            <h4 className={`font-bold line-clamp-1 ${isDark ? 'text-slate-200' : 'text-slate-800'}`}>{product.name}</h4>
                                            {isPharmaEnabled && (
                                                <Pill className={`h-4 w-4 text-blue-500 opacity-50 group-hover:opacity-100 transition-opacity`} />
                                            )}
                                        </div>

                                        {isPharmaEnabled && product.attributes?.generic_name && (
                                            <p className={`text-[10px] font-medium uppercase tracking-tight px-2 py-0.5 rounded-full inline-block mb-2 ${
                                                isDark ? 'bg-blue-500/10 text-blue-400' : 'bg-blue-50 text-blue-600'
                                            }`}>
                                                {product.attributes.generic_name}
                                            </p>
                                        )}

                                        <div className="flex justify-between items-center mt-2">
                                            <span className="text-indigo-500 font-bold">${product.price}</span>
                                            <span className={`text-xs ${isDark ? 'text-slate-500' : 'text-slate-400'}`}>Stock: {product.stock}</span>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>

                        {/* 2. Cart & Checkout Section */}
                        <div className={`w-[400px] border rounded-2xl shadow-sm flex flex-col overflow-hidden transition-colors ${
                            isDark ? 'bg-slate-900 border-white/10' : 'bg-white border-slate-200'
                        }`}>
                            <div className={`p-4 border-b ${isDark ? 'bg-slate-800/30 border-white/5' : 'bg-slate-50/50 border-slate-100'}`}>
                                <div className="flex items-center justify-between mb-4">
                                    <h3 className={`font-bold flex items-center gap-2 ${isDark ? 'text-slate-200' : 'text-slate-800'}`}>
                                        <ShoppingCart className="h-5 w-5 text-indigo-500" />
                                        Cart Items
                                    </h3>
                                    <span className={`text-xs font-bold px-2 py-1 rounded-full ${
                                        isDark ? 'bg-indigo-500/20 text-indigo-400' : 'bg-indigo-100 text-indigo-700'
                                    }`}>
                                        {data.items.length} Items
                                    </span>
                                </div>

                                <div className={`flex p-1 rounded-xl mb-4 ${isDark ? 'bg-slate-800' : 'bg-slate-200'}`}>
                                    <button
                                        onClick={() => { setIsCreditSale(false); setData('payment_method', 'Cash'); }}
                                        className={`flex-1 py-2 text-xs font-bold rounded-lg transition-all ${
                                            !isCreditSale 
                                            ? (isDark ? 'bg-slate-700 text-white shadow-sm' : 'bg-white shadow-sm text-slate-800') 
                                            : (isDark ? 'text-slate-500 hover:text-slate-400' : 'text-slate-500')
                                        }`}
                                    >
                                        Cash (Paid)
                                    </button>
                                    <button
                                        onClick={() => { setIsCreditSale(true); setData('payment_method', 'Credit'); }}
                                        className={`flex-1 py-2 text-xs font-bold rounded-lg transition-all ${
                                            isCreditSale 
                                            ? 'bg-red-500 text-white shadow-sm' 
                                            : (isDark ? 'text-slate-500 hover:text-slate-400' : 'text-slate-500')
                                        }`}
                                    >
                                        Baki (Credit)
                                    </button>
                                </div>

                                <div className="space-y-2">
                                    <div className="flex justify-between items-center">
                                        <InputLabel className={`text-[10px] uppercase font-black ${isDark ? 'text-slate-500' : 'text-slate-400'}`} value={isCreditSale ? "Customer (Required)" : "Customer (Optional)"} />
                                        <button className="text-[10px] text-indigo-500 font-bold hover:underline flex items-center gap-0.5">
                                            <UserPlus className="h-3 w-3" /> Quick Add
                                        </button>
                                    </div>
                                    <select
                                        className={`w-full rounded-xl text-sm focus:ring-indigo-500 border-none transition-colors ${
                                            isDark ? 'bg-slate-800 text-white' : 'bg-white border-slate-200 text-slate-900'
                                        }`}
                                        value={data.customer_id || ''}
                                        onChange={(e) => setData('customer_id', e.target.value || null)}
                                    >
                                        <option value="">Walk-in Customer</option>
                                        {customers.map(c => (
                                            <option key={c.id} value={c.id}>{c.name} ({c.phone})</option>
                                        ))}
                                    </select>
                                    {isCreditSale && !data.customer_id && (
                                        <p className="text-[10px] text-red-400 font-medium">* Please select a customer for credit sales.</p>
                                    )}
                                </div>
                            </div>

                            {/* Cart List */}
                            <div className="flex-1 overflow-y-auto p-4 space-y-3">
                                {data.items.length === 0 ? (
                                    <div className={`h-full flex flex-col items-center justify-center opacity-50 ${isDark ? 'text-slate-600' : 'text-slate-300'}`}>
                                        <ShoppingCart className="h-12 w-12 mb-2" />
                                        <p className="text-sm font-medium">Cart is empty</p>
                                    </div>
                                ) : (
                                    data.items.map(item => (
                                        <div key={item.id} className={`flex justify-between items-center p-3 rounded-xl border transition-colors ${
                                            isDark ? 'bg-slate-800/30 border-white/5' : 'bg-slate-50/30 border-slate-100'
                                        }`}>
                                            <div className="flex-1 min-w-0 pr-2">
                                                <p className={`text-sm font-bold truncate ${isDark ? 'text-slate-200' : 'text-slate-800'}`}>{item.name}</p>
                                                <p className={`text-[10px] ${isDark ? 'text-slate-500' : 'text-slate-400'}`}>${item.price} x {item.quantity}</p>
                                            </div>
                                            <div className="flex items-center gap-2">
                                                <p className={`text-sm font-black ${isDark ? 'text-white' : 'text-slate-900'}`}>${item.price * item.quantity}</p>
                                                <button className={`p-1 transition-colors ${isDark ? 'text-slate-600 hover:text-red-400' : 'text-slate-400 hover:text-red-500'}`}>
                                                    <Trash2 className="h-4 w-4" />
                                                </button>
                                            </div>
                                        </div>
                                    ))
                                )}
                            </div>

                            {/* Summary & Actions */}
                            <div className={`p-6 border-t transition-colors ${isDark ? 'bg-slate-900 border-white/10' : 'bg-white border-slate-100'}`}>
                                <div className="space-y-2 mb-6">
                                    <div className={`flex justify-between text-sm ${isDark ? 'text-slate-500' : 'text-slate-500'}`}>
                                        <span>Subtotal</span>
                                        <span className={isDark ? 'text-slate-300' : ''}>${data.total_amount}</span>
                                    </div>
                                    <div className={`flex justify-between text-xl font-black pt-2 border-t ${isDark ? 'text-white border-white/5' : 'text-slate-900 border-slate-50'}`}>
                                        <span>Total</span>
                                        <span className="text-indigo-500">${data.total_amount}</span>
                                    </div>
                                </div>

                                <PrimaryButton
                                    className={`w-full py-4 rounded-xl flex items-center justify-center gap-2 text-lg font-black transition-all shadow-lg ${
                                        isCreditSale 
                                        ? 'bg-red-600 hover:bg-red-700 shadow-red-500/20' 
                                        : 'bg-emerald-600 hover:bg-emerald-700 shadow-emerald-500/20 text-white'
                                    }`}
                                    onClick={handleCheckout}
                                    disabled={processing || (isCreditSale && !data.customer_id) || data.items.length === 0}
                                >
                                    <CheckCircle2 className="h-6 w-6" />
                                    {isCreditSale ? 'Confirm Baki' : 'Finish Sale (Pay)'}
                                </PrimaryButton>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
