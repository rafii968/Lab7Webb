<?php



namespace App\Controllers;



use App\Models\ArtikelModel;

use App\Models\KategoriModel;

use CodeIgniter\Exceptions\PageNotFoundException;



class Artikel extends BaseController

{

    public function home()

    {

        return view('home', [

            'title'   => 'Halaman Home',

            'content' => 'Selamat datang di halaman utama portal berita kami.'

        ]);

    }



    public function about()

    {

        return view('about', [

            'title'   => 'Tentang Kami',

            'content' => 'Ini adalah halaman profil aplikasi yang dibangun dengan CodeIgniter 4.'

        ]);

    }



    public function contact()

    {

        return view('contact', [

            'title'   => 'Kontak Kami',

            'content' => 'Silakan hubungi kami melalui email: support@portalberita.com'

        ]);

    }



    public function index()

    {

        $model = new ArtikelModel();



        $data = [

            'title'   => 'Daftar Artikel',

            'artikel' => $model->getArtikelDenganKategori(),

        ];



        return view('artikel/index', $data);

    }



    public function view($slug)

    {

        $model = new ArtikelModel();



        $artikel = $model->where('slug', $slug)->first();



        if (empty($artikel)) {

            throw PageNotFoundException::forPageNotFound();

        }



        return view('artikel/detail', [

            'title'   => $artikel['judul'],

            'artikel' => $artikel,

        ]);

    }


public function admin_index()
    {
        $title = 'Daftar Artikel (Admin)';
        $model = new ArtikelModel();

        // Ambil parameter search, filter kategori, dan halaman
        $q           = $this->request->getVar('q') ?? '';
        $kategori_id = $this->request->getVar('kategori_id') ?? '';
        $page        = $this->request->getVar('page') ?? 1;

        // Bangun Query Builder untuk mengambil data artikel & nama kategori
        $builder = $model->table('artikel')
                         ->select('artikel.*, kategori.nama_kategori')
                         ->join('kategori', 'kategori.id_kategori = artikel.id_kategori', 'left');

        // Filter Pencarian Judul
        if ($q != '') {
            $builder->like('artikel.judul', $q);
        }

        // Filter Berdasarkan Kategori
        if ($kategori_id != '') {
            $builder->where('artikel.id_kategori', $kategori_id);
        }

        // Terapkan Pagination CodeIgniter 4
        $artikel = $builder->paginate(10, 'default', $page);
        $pager   = $model->pager;

        $data = [
            'title'       => $title,
            'q'           => $q,
            'kategori_id' => $kategori_id,
            'artikel'     => $artikel,
            'pager'       => $pager ? $pager->links('default', 'bootstrap_full') : '' // render pagination format dasar
        ];

        // JIKA REQUEST ADALAH AJAX: Kirim data dalam format JSON
        if ($this->request->isAJAX()) {
            // Kita juga memformat links pagination agar bisa dibaca JavaScript dengan mudah
            $data['pagination_links'] = $model->pager->links('default', 'bootstrap_full');
            return $this->response->setJSON($data);
        } 
        
        // JIKA REQUEST BIASA (Bukan AJAX): Tampilkan view seperti semula
        $kategoriModel = new KategoriModel();
        $data['kategori'] = $kategoriModel->findAll();
        
        return view('artikel/admin_index', $data);
    }

    public function add()

    {

        $kategoriModel = new KategoriModel();



        if ($this->request->getMethod() == 'post') {



            $judul       = $this->request->getPost('judul');

            $isi         = $this->request->getPost('isi');

            $id_kategori = $this->request->getPost('id_kategori');



            // Upload gambar

            $fileGambar = $this->request->getFile('gambar');



            $namaGambar = null;



            if ($fileGambar && $fileGambar->isValid() && !$fileGambar->hasMoved()) {



                $namaGambar = $fileGambar->getRandomName();



                $fileGambar->move(

                    ROOTPATH . 'public/gambar',

                    $namaGambar

                );

            }



            $model = new ArtikelModel();



            $model->insert([

                'judul'       => $judul,

                'isi'         => $isi,

                'slug'        => url_title($judul, '-', true),

                'gambar'      => $namaGambar,

                'id_kategori' => $id_kategori,

                'status'      => 1,

            ]);



            return redirect()->to('/admin/artikel');

        }



        return view('artikel/form_add', [

            'title'    => 'Tambah Artikel',

            'kategori' => $kategoriModel->findAll(),

        ]);

    }



    public function edit($id)

    {

        $model         = new ArtikelModel();

        $kategoriModel = new KategoriModel();



        if (

            $this->request->getMethod() == 'post' &&

            $this->validate([

                'judul'       => 'required',

                'id_kategori' => 'required'

            ])

        ) {

            $model->update($id, [

                'judul'       => $this->request->getPost('judul'),

                'isi'         => $this->request->getPost('isi'),

                'id_kategori' => $this->request->getPost('id_kategori'),

            ]);



            return redirect()->to('/admin/artikel');

        }



        return view('artikel/form_edit', [

            'title'    => 'Edit Artikel',

            'artikel'  => $model->find($id),

            'kategori' => $kategoriModel->findAll(),

        ]);

    }



    public function delete($id)

    {

        $model = new ArtikelModel();



        $model->delete($id);



        return redirect()->to('/admin/artikel');

    }

}