<?php

namespace App\Controllers;

use App\Models\ArtikelModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Artikel extends BaseController
{
    // --- HALAMAN USER & STATIS (MODUL 3) ---

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
            'artikel' => $model->findAll(),
        ];
        return view('artikel/index', $data);
    }

    public function view($slug)
    {
        $model = new ArtikelModel();
        $artikel = $model->where(['slug' => $slug])->first();

        if (!$artikel) {
            throw PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title'   => $artikel['judul'],
            'artikel' => $artikel,
        ];
        return view('artikel/detail', $data);
    }

    // --- HALAMAN ADMIN (CRUD - MODUL 2 & 3) ---

    public function admin_index()
    {
        $model = new ArtikelModel();
        $data = [
            'title'   => 'Unit Pengelola Artikel',
            'artikel' => $model->findAll(),
        ];
        return view('artikel/admin_index', $data);
    }

    public function add()
    {
        // Validasi input
        $validation = \Config\Services::validation();
        $validation->setRules(['judul' => 'required']);
        $isDataValid = $validation->withRequest($this->request)->run();

        // Jika data valid, simpan ke database
        if ($isDataValid) {
            $artikel = new ArtikelModel();
            $artikel->insert([
                'judul' => $this->request->getPost('judul'),
                'isi'   => $this->request->getPost('isi'),
                'slug'  => url_title($this->request->getPost('judul'), '-', true),
                'status'=> 1
            ]);
            return redirect()->to('/admin/artikel');
        }

        return view('artikel/form_add', ['title' => 'Tambah Artikel Baru']);
    }

    public function edit($id)
    {
        $artikel = new ArtikelModel();
        
        // Validasi input
        $validation = \Config\Services::validation();
        $validation->setRules(['judul' => 'required']);
        $isDataValid = $validation->withRequest($this->request)->run();

        if ($isDataValid) {
            $artikel->update($id, [
                'judul' => $this->request->getPost('judul'),
                'isi'   => $this->request->getPost('isi'),
            ]);
            return redirect()->to('/admin/artikel');
        }

        // Ambil data lama untuk ditampilkan di form
        $data = [
            'title' => 'Edit Artikel',
            'data'  => $artikel->where('id', $id)->first()
        ];
        return view('artikel/form_edit', $data);
    }

    public function delete($id)
    {
        $artikel = new ArtikelModel();
        $artikel->delete($id);
        return redirect()->to('/admin/artikel');
    }
}