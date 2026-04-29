import AdminLayout from '@/Layouts/AdminLayout';
import { Head, Link, useForm } from '@inertiajs/react';
import { ArrowLeft, Send } from 'lucide-react';

export default function Create() {
    const { data, setData, post, processing, errors } = useForm({
        title: '',
        body: '',
        publish_now: true,
    });

    const submit = (e) => {
        e.preventDefault();
        post(route('admin.announcements.store'));
    };

    return (
        <AdminLayout header="New Announcement">
            <Head title="New Announcement" />

            <div className="mb-8 flex items-center justify-between gap-4">
                <div>
                    <h1 className="text-2xl font-bold text-slate-900">Create Announcement</h1>
                    <p className="text-slate-500 text-sm mt-1">Post updates visible across the platform.</p>
                </div>
                <Link
                    href={route('admin.announcements.index')}
                    className="inline-flex items-center gap-2 text-slate-600 hover:text-slate-900 font-bold text-sm"
                >
                    <ArrowLeft className="w-4 h-4" />
                    Back
                </Link>
            </div>

            <div className="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <form onSubmit={submit} className="p-8 space-y-6 max-w-3xl">
                    <div className="grid grid-cols-1 gap-1">
                        <label className="text-sm font-bold text-slate-700">Title</label>
                        <input
                            type="text"
                            value={data.title}
                            onChange={(e) => setData('title', e.target.value)}
                            className="mt-1 block w-full border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Scheduled maintenance, new feature release…"
                        />
                        {errors.title && <div className="text-sm text-red-600 mt-1">{errors.title}</div>}
                    </div>

                    <div className="grid grid-cols-1 gap-1">
                        <label className="text-sm font-bold text-slate-700">Message</label>
                        <textarea
                            value={data.body}
                            onChange={(e) => setData('body', e.target.value)}
                            rows={8}
                            className="mt-1 block w-full border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Write the announcement details…"
                        />
                        {errors.body && <div className="text-sm text-red-600 mt-1">{errors.body}</div>}
                    </div>

                    <label className="flex items-center gap-3 p-4 rounded-2xl border border-slate-200 bg-slate-50">
                        <input
                            type="checkbox"
                            checked={!!data.publish_now}
                            onChange={(e) => setData('publish_now', e.target.checked)}
                            className="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                        />
                        <div>
                            <p className="text-sm font-bold text-slate-900">Publish immediately</p>
                            <p className="text-xs text-slate-500">Uncheck to save as draft.</p>
                        </div>
                    </label>

                    <div className="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                        <Link
                            href={route('admin.announcements.index')}
                            className="px-5 py-2.5 rounded-xl text-sm font-bold border border-slate-200 text-slate-600 hover:bg-slate-50"
                        >
                            Cancel
                        </Link>
                        <button
                            type="submit"
                            disabled={processing}
                            className="inline-flex items-center gap-2 bg-indigo-600 text-white px-6 py-2.5 rounded-xl font-bold text-sm shadow-xl shadow-indigo-100 hover:bg-indigo-700 transition-all disabled:opacity-60"
                        >
                            <Send className="w-4 h-4" />
                            Create
                        </button>
                    </div>
                </form>
            </div>
        </AdminLayout>
    );
}
