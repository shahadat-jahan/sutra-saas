import AdminLayout from '@/Layouts/AdminLayout';
import { Head, Link } from '@inertiajs/react';
import { Megaphone, Plus } from 'lucide-react';

export default function Index({ announcements }) {
    const items = announcements?.data ?? [];
    const links = announcements?.links ?? [];
    const prev = links.find((l) => l.label === '&laquo; Previous');
    const next = links.find((l) => l.label === 'Next &raquo;');

    return (
        <AdminLayout header="Announcements">
            <Head title="Platform Announcements" />

            <div className="flex items-center justify-between mb-8">
                <div>
                    <h1 className="text-2xl font-bold text-slate-900">Platform Announcements</h1>
                    <p className="text-slate-500 text-sm mt-1">Create and manage platform-wide updates.</p>
                </div>

                <Link
                    href={route('admin.announcements.create')}
                    className="inline-flex items-center gap-2 bg-indigo-600 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-xl shadow-indigo-100 hover:bg-indigo-700 transition-all"
                >
                    <Plus className="w-4 h-4" />
                    New Announcement
                </Link>
            </div>

            <div className="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                {items.length ? (
                    <div className="divide-y divide-slate-100">
                        {items.map((a) => (
                            <div key={a.uuid} className="p-6 hover:bg-slate-50/50 transition-colors">
                                <div className="flex items-start justify-between gap-6">
                                    <div>
                                        <p className="font-bold text-slate-900">{a.title}</p>
                                        <p className="text-xs text-slate-500 mt-1">
                                            Published: {a.published_at ? new Date(a.published_at).toLocaleString() : 'Draft'}
                                        </p>
                                    </div>
                                    <span className={`text-xs font-bold px-3 py-1 rounded-full border ${
                                        a.published_at
                                            ? 'bg-emerald-50 text-emerald-700 border-emerald-100'
                                            : 'bg-slate-50 text-slate-600 border-slate-200'
                                    }`}>
                                        {a.published_at ? 'Published' : 'Draft'}
                                    </span>
                                </div>
                            </div>
                        ))}
                    </div>
                ) : (
                    <div className="p-12 text-center">
                        <div className="flex flex-col items-center">
                            <div className="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                <Megaphone className="w-8 h-8 text-slate-300" />
                            </div>
                            <p className="text-slate-900 font-bold">No announcements yet</p>
                            <p className="text-slate-500 text-sm mt-1">Create your first platform announcement.</p>
                        </div>
                    </div>
                )}
            </div>

            <div className="mt-6 flex items-center justify-between text-sm">
                <p className="text-slate-500">
                    Page {announcements?.current_page ?? 1} of {announcements?.last_page ?? 1}
                </p>
                <div className="flex items-center gap-2">
                    <Link
                        href={prev?.url ?? '#'}
                        disabled={!prev?.url}
                        className={`px-4 py-2 rounded-xl font-bold border ${
                            prev?.url
                                ? 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50'
                                : 'bg-slate-50 border-slate-200 text-slate-400 cursor-not-allowed pointer-events-none'
                        }`}
                    >
                        Previous
                    </Link>
                    <Link
                        href={next?.url ?? '#'}
                        disabled={!next?.url}
                        className={`px-4 py-2 rounded-xl font-bold border ${
                            next?.url
                                ? 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50'
                                : 'bg-slate-50 border-slate-200 text-slate-400 cursor-not-allowed pointer-events-none'
                        }`}
                    >
                        Next
                    </Link>
                </div>
            </div>
        </AdminLayout>
    );
}
