@extends('layouts.admin')

@section('title', 'Kategori Koleksi - Aksaraya')
@section('page-title', 'Kategori & Klasifikasi Buku')

@section('content')
<div class="space-y-6 mt-6" x-data="{ addModal: false, editModal: false, activeCat: { id: null, name: '', icon: 'book-open', color: 'blue', description: '' } }">
    
    <!-- Top Action Bar -->
    <div class="flex items-center justify-between bg-white p-4 rounded-3xl border border-slate-200/80 shadow-sm">
        <div>
            <h3 class="font-display font-bold text-slate-900 text-sm">Klasifikasi Topik Koleksi</h3>
            <p class="text-xs text-slate-500">Kelompokkan bahan pustaka berdasarkan tema keilmuan</p>
        </div>
        <button @click="addModal = true" class="px-5 py-2.5 bg-gradient-to-r from-brand-600 to-teal-700 hover:from-brand-700 hover:to-teal-800 text-white font-bold text-xs rounded-xl shadow-glow flex items-center gap-2 transition-all">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Tambah Kategori</span>
        </button>
    </div>

    <!-- Categories Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($categories as $category)
        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm flex flex-col justify-between hover:border-brand-400 hover:shadow-md transition-all">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-brand-50 text-brand-700 flex items-center justify-center font-bold shadow-inner">
                        <i data-lucide="{{ $category->icon ?? 'book-open' }}" class="w-6 h-6"></i>
                    </div>
                    <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-slate-100 text-slate-700 font-mono">
                        {{ $category->books_count }} Judul
                    </span>
                </div>

                <h4 class="font-display font-bold text-base text-slate-900">{{ $category->name }}</h4>
                <p class="text-[11px] text-slate-400 font-mono mt-0.5">/{{ $category->slug }}</p>
                <p class="text-xs text-slate-600 mt-2.5 leading-relaxed line-clamp-2">
                    {{ $category->description ?? 'Tidak ada keterangan khusus.' }}
                </p>
            </div>

            <div class="pt-4 border-t border-slate-100 mt-4 flex items-center justify-between">
                <a href="{{ route('catalog.index', ['kategori' => $category->slug]) }}" target="_blank" class="text-xs font-bold text-brand-700 hover:underline flex items-center gap-1">
                    <span>Lihat di Web</span>
                    <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                </a>

                <div class="flex items-center gap-1">
                    <button @click="activeCat = { id: {{ $category->id }}, name: '{{ addslashes($category->name) }}', icon: '{{ $category->icon }}', color: '{{ $category->color }}', description: '{{ addslashes($category->description) }}' }; editModal = true;" class="p-1.5 text-slate-400 hover:text-brand-700 rounded-lg transition-colors">
                        <i data-lucide="edit" class="w-4 h-4"></i>
                    </button>

                    @if($category->books_count === 0)
                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg transition-colors">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Add Category Modal -->
    <div x-show="addModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div @click="addModal = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
        <div class="relative bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl z-10 space-y-4 border border-slate-100">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="font-display font-bold text-slate-900 text-base">Tambah Kategori Baru</h3>
                <button @click="addModal = false" class="text-slate-400 hover:text-slate-600 p-1">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Kategori *</label>
                    <input type="text" name="name" required placeholder="Contoh: Sains & Teknologi" class="w-full text-xs p-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500 font-medium">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Icon Lucide</label>
                        <input type="text" name="icon" value="book-open" placeholder="atom, code, book" class="w-full text-xs p-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Warna Aksen</label>
                        <input type="text" name="color" value="blue" placeholder="blue, emerald, purple" class="w-full text-xs p-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Deskripsi Singkat</label>
                    <textarea name="description" rows="2" placeholder="Cakupan topik kategori..." class="w-full text-xs p-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500"></textarea>
                </div>

                <div class="flex justify-end gap-2.5 pt-2">
                    <button @click="addModal = false" type="button" class="px-4 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-xs">Simpan Kategori</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div x-show="editModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div @click="editModal = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
        <div class="relative bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl z-10 space-y-4 border border-slate-100">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="font-display font-bold text-slate-900 text-base">Edit Kategori</h3>
                <button @click="editModal = false" class="text-slate-400 hover:text-slate-600 p-1">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form method="POST" :action="'/admin/categories/' + activeCat.id" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Kategori *</label>
                    <input type="text" name="name" x-model="activeCat.name" required class="w-full text-xs p-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500 font-medium">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Icon Lucide</label>
                        <input type="text" name="icon" x-model="activeCat.icon" class="w-full text-xs p-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Warna Aksen</label>
                        <input type="text" name="color" x-model="activeCat.color" class="w-full text-xs p-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Deskripsi Singkat</label>
                    <textarea name="description" x-model="activeCat.description" rows="2" class="w-full text-xs p-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500"></textarea>
                </div>

                <div class="flex justify-end gap-2.5 pt-2">
                    <button @click="editModal = false" type="button" class="px-4 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-xs">Perbarui</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
