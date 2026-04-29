import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, usePage, useForm } from '@inertiajs/react';
import React, { useState, useMemo } from 'react';
import TextInput from '@/Components/TextInput';
import PrimaryButton from '@/Components/PrimaryButton';
import InputLabel from '@/Components/InputLabel';
import InputError from '@/Components/InputError';
import { ShoppingCart, User, Plus, Search, Trash2, Pill, CheckCircle2, UserPlus } from 'lucide-react';

/**
 * POS Component
 *
 * Features:
 * - Default: Walk-in (null customer) and Paid status.
 * - One-Click Pay: Work without customer selection for cash sales.
 * - Baki Toggle: Mandatory customer search/add when Credit is selected.
 * - Pharma UI: Medicine-specific attributes (Generic Name) visible in product list.
 */
export default function Index({ products = [], customers = [], enabledModules = [] }) {
    const { auth } = usePage().props;
    const isPharmaEnabled = enabledModules.includes('pharma');

    const [searchTerm, setSearchTerm] = useState('');
    const [isCreditSale, setIsCreditSale] = useState(false);

    // Inertia form state
    const { data, setData, post, processing, errors } = useForm({
        items: [],
        customer_id: null, // Requirement: Default Walk-in
        payment_method: 'Cash', // Requirement: Default Paid (Cash)
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
        post(route('pos.store'));
    };

    return (
        <AuthenticatedLayout
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Point of Sale</h2>}
        >
            <Head title="POS - Sutra SaaS" />

            <div className="py-6 h-[calc(100vh-120px)] overflow-hidden">
                <div className="max-w-[1600px] mx-auto sm:px-6 lg:px-8 h-full">
                    <div className="flex gap-6 h-full">

                        {/* 1. Product Grid Section */}
                        <div className="flex-1 bg-white rounded-2xl shadow-sm border border-gray-200 flex flex-col">
                            <div className="p-4 border-b border-gray-100 flex items-center gap-4">
                                <div className="relative flex-1">
                                    <Search className="absolute left-3 top-3 h-4 w-4 text-gray-400" />
                                    <TextInput
                                        className="pl-10 w-full bg-gray-50 border-none focus:ring-2 focus:ring-indigo-500"
                                        placeholder="Search products by name or generic..."
                                        value={searchTerm}
                                        onChange={(e) => setSearchTerm(e.target.value)}
                                    />
                                </div>
                                <div className="flex gap-2">
                                    <button className="p-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200">
                                        <Plus className="h-5 w-5" />
                                    </button>
                                </div>
                            </div>

                            <div className="flex-1 overflow-y-auto p-6 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50/50">
                                {filteredProducts.map(product => (
                                    <div
                                        key={product.id}
                                        onClick={() => addToCart(product)}
                                        className="bg-white p-4 rounded-xl border border-gray-200 hover:border-indigo-500 hover:shadow-md cursor-pointer transition-all group"
                                    >
                                        <div className="flex justify-between items-start mb-2">
                                            <h4 className="font-bold text-gray-800 line-clamp-1">{product.name}</h4>
                                            {isPharmaEnabled && (
                                                <Pill className="h-4 w-4 text-blue-500 opacity-50 group-hover:opacity-100" />
                                            )}
                                        </div>

                                        {/* Requirement: Pharma UI (Medicine Specific Fields) */}
                                        {isPharmaEnabled && product.attributes?.generic_name && (
                                            <p className="text-[10px] text-blue-600 font-medium uppercase tracking-tight bg-blue-50 px-2 py-0.5 rounded-full inline-block mb-2">
                                                {product.attributes.generic_name}
                                            </p>
                                        )}

                                        <div className="flex justify-between items-center mt-2">
                                            <span className="text-indigo-600 font-bold">${product.price}</span>
                                            <span className="text-xs text-gray-400">Stock: {product.stock}</span>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>

                        {/* 2. Cart & Checkout Section */}
                        <div className="w-[400px] bg-white rounded-2xl shadow-sm border border-gray-200 flex flex-col overflow-hidden">
                            <div className="p-4 border-b border-gray-100 bg-gray-50/50">
                                <div className="flex items-center justify-between mb-4">
                                    <h3 className="font-bold text-gray-800 flex items-center gap-2">
                                        <ShoppingCart className="h-5 w-5 text-indigo-500" />
                                        Cart Items
                                    </h3>
                                    <span className="bg-indigo-100 text-indigo-700 text-xs font-bold px-2 py-1 rounded-full">
                                        {data.items.length} Items
                                    </span>
                                </div>

                                {/* Requirement: Default Paid Status & Baki Toggle */}
                                <div className="flex p-1 bg-gray-200 rounded-xl mb-4">
                                    <button
                                        onClick={() => { setIsCreditSale(false); setData('payment_method', 'Cash'); }}
                                        className={`flex-1 py-2 text-xs font-bold rounded-lg transition-all ${!isCreditSale ? 'bg-white shadow-sm text-gray-800' : 'text-gray-500'}`}
                                    >
                                        Cash (Paid)
                                    </button>
                                    {isPharmaEnabled && (
                                        <button
                                            onClick={() => { setIsCreditSale(true); setData('payment_method', 'Credit'); }}
                                            className={`flex-1 py-2 text-xs font-bold rounded-lg transition-all ${isCreditSale ? 'bg-red-500 text-white shadow-sm' : 'text-gray-500'}`}
                                        >
                                            Baki (Credit)
                                        </button>
                                    )}
                                </div>

                                {/* Requirement: Mandatory Customer field for Baki */}
                                <div className="space-y-2">
                                    <div className="flex justify-between items-center">
                                        <InputLabel className="text-[10px] uppercase font-black text-gray-400" value={isCreditSale ? "Customer (Required)" : "Customer (Optional)"} />
                                        <button className="text-[10px] text-indigo-600 font-bold hover:underline flex items-center gap-0.5">
                                            <UserPlus className="h-3 w-3" /> Quick Add
                                        </button>
                                    </div>
                                    <select
                                        className="w-full rounded-xl border-gray-200 text-sm focus:ring-indigo-500"
                                        value={data.customer_id || ''}
                                        onChange={(e) => setData('customer_id', e.target.value || null)}
                                    >
                                        <option value="">Walk-in Customer</option>
                                        {customers.map(c => (
                                            <option key={c.id} value={c.id}>{c.name} ({c.phone})</option>
                                        ))}
                                    </select>
                                    {isCreditSale && !data.customer_id && (
                                        <p className="text-[10px] text-red-500 font-medium">* Please select a customer for credit sales.</p>
                                    )}
                                </div>
                            </div>

                            {/* Cart List */}
                            <div className="flex-1 overflow-y-auto p-4 space-y-3">
                                {data.items.length === 0 ? (
                                    <div className="h-full flex flex-col items-center justify-center text-gray-300 opacity-50">
                                        <ShoppingCart className="h-12 w-12 mb-2" />
                                        <p className="text-sm font-medium">Cart is empty</p>
                                    </div>
                                ) : (
                                    data.items.map(item => (
                                        <div key={item.id} className="flex justify-between items-center p-3 rounded-xl border border-gray-100 bg-gray-50/30">
                                            <div className="flex-1 min-w-0 pr-2">
                                                <p className="text-sm font-bold text-gray-800 truncate">{item.name}</p>
                                                <p className="text-[10px] text-gray-400">${item.price} x {item.quantity}</p>
                                            </div>
                                            <div className="flex items-center gap-2">
                                                <p className="text-sm font-black text-gray-900">${item.price * item.quantity}</p>
                                                <button className="p-1 text-gray-400 hover:text-red-500">
                                                    <Trash2 className="h-4 w-4" />
                                                </button>
                                            </div>
                                        </div>
                                    ))
                                )}
                            </div>

                            {/* Summary & Actions */}
                            <div className="p-6 border-t border-gray-100 bg-white">
                                <div className="space-y-2 mb-6">
                                    <div className="flex justify-between text-sm text-gray-500">
                                        <span>Subtotal</span>
                                        <span>${data.total_amount}</span>
                                    </div>
                                    <div className="flex justify-between text-xl font-black text-gray-900 pt-2 border-t border-gray-50">
                                        <span>Total</span>
                                        <span className="text-indigo-600">${data.total_amount}</span>
                                    </div>
                                </div>

                                {/* Requirement: One-Click Sale Button */}
                                <PrimaryButton
                                    className={`w-full py-4 rounded-xl flex items-center justify-center gap-2 text-lg font-black transition-all ${isCreditSale ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700'}`}
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
