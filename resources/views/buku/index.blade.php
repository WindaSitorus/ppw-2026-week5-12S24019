<x-layout>
    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        <h3 class="fw-bold text-purple">Katalog Buku Perpustakaan</h3>
        <a href="#" class="btn btn-purple">Tambah Buku Baru</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ISBN</th>
                        <th>Judul Buku</th>
                        <th>Kategori</th>
                        <th>Penulis</th>
                        <th>Tahun</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bukus as $buku)
                        <tr>
                            <td>{{ $buku->isbn }}</td>
                            <td>{{ $buku->judul }}</td>
                            <td><span class="badge bg-secondary">{{ $buku->kategori->nama ?? '-' }}</span></td>
                            <td>{{ $buku->penulis }}</td>
                            <td>{{ $buku->tahun_terbit }}</td>
                            <td><span class="badge bg-success">{{ $buku->stok }}</span></td>
                            <td>
                                <button class="btn btn-sm btn-warning text-dark border-0">Edit</button>
                                <button class="btn btn-sm btn-danger border-0">Hapus</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4 d-flex justify-content-end">
        {{ $bukus->links() }}
    </div>
</x-layout>