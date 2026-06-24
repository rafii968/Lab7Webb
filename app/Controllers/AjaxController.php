<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ArtikelModel;

class AjaxController extends Controller
{
    public function index()
    {
        // Menambahkan data title agar tidak error saat dipanggil oleh template/header
        $data = [
            'title' => 'Halaman AJAX Artikel'
        ];

        return view('ajax/index', $data);
    }

    public function getData()
    {
        $model = new ArtikelModel();
        // Menggunakan method bawaan Model CI4 untuk mengambil semua data artikel
        $data = $model->findAll(); 
        
        // Kirim data dalam format JSON ke browser
        return $this->response->setJSON($data);
    }

    public function add()
    {
        $model = new ArtikelModel();
        
        // Ambil input dari request AJAX POST
        $judul = $this->request->getPost('judul');
        $isi   = $this->request->getPost('isi');

        // Lakukan insert data ke database
        $model->insert([
            'judul'       => $judul,
            'isi'         => $isi,
            'slug'        => url_title($judul, '-', true),
            'id_kategori' => 1, // Default sementara agar tidak null constraint error
            'status'      => 1, // Set default aktif
        ]);

        $data = [
            'status' => 'OK'
        ];

        // Kirim response balik dalam bentuk JSON ke view
        return $this->response->setJSON($data);
    }

    public function delete($id)
    {
        $model = new ArtikelModel();
        $model->delete($id);

        $data = [
            'status' => 'OK'
        ];

        // Kirim respon sukses dalam format JSON
        return $this->response->setJSON($data);
    }
}