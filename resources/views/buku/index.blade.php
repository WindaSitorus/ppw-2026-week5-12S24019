<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPUS-Del | Dasbor Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .card-stat { border: none; border-radius: 12px; transition: transform 0.2s; }
        .card-stat:hover { transform: translateY(-5px); }
        .table-container { background: #fff; border-radius: 12px; padding: 24px; box-shadow: 0 2px 10px rgba(0,0,0,0.03); }
        .badge-kategori { font-weight: 500; letter-spacing: 0.3px; }
    </style>
</head>
<body>
    <div class="container py-5">
        <h2 class="mb-4 fw-bold text-dark">Dasbor Perpustakaan</h2>

        <!-- Notifikasi Sukses/Error dari Session -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Kartu Analitik Statistik Perpustakaan -->
        <div class="row mb-4 g-3">
            <div class="col-md-3">
                <div class="card card-stat bg-primary text-white h-100 shadow-sm">
                    <div class="card-body">
                        <h6 class="card-title opacity-75">Total Judul Buku</h6>
                        <h3 class="fw-bold mb-0">{{ $stats['total_buku'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stat bg-info text-white h-100 shadow-sm">
                    <div class="card-body">
                        <h6 class="card-title opacity-75">Total Stok Tersedia</h6>
                        <h3 class="fw-bold mb-0">{{ $stats['total_stok'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stat bg-warning text-dark h-100 shadow-sm">
                    <div class="card-body">
                        <h6 class="card-title opacity-75">Sedang Dipinjam</h6>
                        <h3 class="fw-bold mb-0">{{ $stats['sedang_dipinjam'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stat bg-success text-white h-100 shadow-sm">
                    <div class="card-body">
                        <h6 class="card-title opacity-75">Total Dikembalikan</h6>
                        <h3 class="fw-bold mb-0">{{ $stats['total_dikembalikan'] }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel dan Form Pencarian -->
        <div class="table-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-semibold mb-0 text-secondary">Katalog Buku</h5>
                <form action="{{ route('buku.index') }}" method="GET" class="d-flex shadow-sm rounded">
                    <input type="text" name="search" class="form-control border-0 bg-light me-2" placeholder="Cari judul/penulis..." value="{{ request('search') }}" style="width: 250px;">
                    <button type="submit" class="btn btn-dark px-4">Cari</button>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light text-secondary">
                        <tr>
                            <th>Judul Buku</th>
                            <th>Penulis</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                            <th>Popularitas</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bukus as $buku)
                            <tr>
                                <td class="fw-medium text-dark">{{ $buku->judul }}</td>
                                <td class="text-muted">{{ $buku->penulis }}</td>
                                <td><span class="badge bg-secondary bg-opacity-10 text-secondary badge-kategori px-2 py-1">{{ $buku->kategori->nama_kategori ?? 'Umum' }}</span></td>
                                <td>
                                    @if($buku->stok > 0)
                                        <span class="text-success fw-bold">{{ $buku->stok }}</span>
                                    @else
                                        <span class="badge bg-danger">Habis</span>
                                    @endif
                                </td>
                                <td><span class="text-muted">{{ $buku->peminjamans_count }}x dipinjam</span></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-primary px-3 rounded-pill" data-bs-toggle="modal" data-bs-target="#pinjamModal{{ $buku->id }}" {{ $buku->stok < 1 ? 'disabled' : '' }}>
                                        Pinjam
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal Peminjaman Buku -->
                            <div class="modal fade" id="pinjamModal{{ $buku->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-header bg-light border-0">
                                            <h5 class="modal-title fw-bold text-dark">Pinjam: {{ $buku->judul }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('buku.pinjam', $buku->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-body p-4">
                                                <div class="mb-3">
                                                    <label class="form-label text-muted fw-medium">NIM Peminjam</label>
                                                    <input type="text" name="nim_peminjam" class="form-control bg-light border-0" required placeholder="Contoh: 12S24019">
                                                </div>
                                                <div class="mb-4">
                                                    <label class="form-label text-muted fw-medium">Nama Peminjam</label>
                                                    <input type="text" name="nama_peminjam" class="form-control bg-light border-0" required placeholder="Masukkan nama lengkap">
                                                </div>
                                            </div>
                                            <div class="modal-footer bg-white border-0">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary px-4">Konfirmasi Pinjam</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">Data buku tidak ditemukan atau pencarian tidak cocok.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-end mt-4">
                {{ $bukus->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>