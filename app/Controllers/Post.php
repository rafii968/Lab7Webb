<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\ArtikelModel;

class Post extends ResourceController
{
    use ResponseTrait;

    // 1. GET /post (Mengambil semua data artikel)
    public function index()
    {
        $model = new ArtikelModel();
        $data = $model->findAll();
        return $this->respond($data, 200);
    }

    // 2. GET /post/(:num) (Mengambil satu data artikel berdasarkan ID)
    public function show($id = null)
    {
        $model = new ArtikelModel();
        $data = $model->find($id);
        
        if ($data) {
            return $this->respond($data, 200);
        } else {
            return $this->failNotFound('Data artikel tidak ditemukan untuk ID: ' . $id);
        }
    }

    // 3. POST /post (Menambah artikel baru)
    public function create()
    {
        $model = new ArtikelModel();
        
        // Mengambil data dari raw body JSON / Form-Data
        $data = [
            'judul'       => $this->request->getVar('judul'),
            'isi'         => $this->request->getVar('isi'),
            'slug'        => url_title($this->request->getVar('judul'), '-', true),
            'id_kategori' => $this->request->getVar('id_kategori') ?? 1,
            'status'      => $this->request->getVar('status') ?? 1,
        ];

        $model->insert($data);
        
        $response = [
            'status'   => 201,
            'error'    => null,
            'messages' => [
                'success' => 'Data artikel berhasil ditambahkan.'
            ]
        ];
        
        return $this->respondCreated($response);
    }

    // 4. PUT /post/(:num) (Mengubah/Update data artikel)
    public function update($id = null)
    {
        $model = new ArtikelModel();
        $cekId = $model->find($id);

        if (!$cekId) {
            return $this->failNotFound('Data artikel tidak ditemukan.');
        }

        // Mengambil inputan put/patch
        $input = $this->request->getRawInput();
        
        $data = [
            'judul' => $input['judul'] ?? $cekId['judul'],
            'isi'   => $input['isi'] ?? $cekId['isi'],
        ];

        // Jika judul berubah, update juga slug-nya
        if (isset($input['judul'])) {
            $data['slug'] = url_title($input['judul'], '-', true);
        }

        $model->update($id, $data);

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => [
                'success' => 'Data artikel dengan ID ' . $id . ' berhasil diperbarui.'
            ]
        ];

        return $this->respond($response);
    }

    // 5. DELETE /post/(:num) (Menghapus artikel)
    public function delete($id = null)
    {
        $model = new ArtikelModel();
        $data = $model->find($id);

        if ($data) {
            $model->delete($id);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => [
                    'success' => 'Data artikel berhasil dihapus.'
                ]
            ];
            return $this->respondDeleted($response);
        } else {
            return $this->failNotFound('Data artikel tidak ditemukan.');
        }
    }
}