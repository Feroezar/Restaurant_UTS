<?php

namespace App\Http\Controllers;

use App\Models\makanan;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MakananController extends Controller
{
    /**
     * index
     *
     * @return View
     */
    public function index(Request $request): View
    {
        //get posts
        $pagination = 5;
        $makanan = makanan::latest()->paginate($pagination);

        //render view with posts
        return view('Makan.index', compact('makanan'))->with('i', ($request->input('page', 1) - 1)*$pagination);
    }
     /**
     * create
     *
     * @return View
     */
    public function create(): View
    {
        return view('Makan.create');
    }

    /**
     * store
     *
     * @param  mixed $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        //validate form
        $this->validate($request, [
            'img'     => 'required|image|mimes:jpeg,jpg,png|max:5048',
            'nama'     => 'required|min:5',
            'harga'   => 'required'
        ]);

        //upload image
        $img = $request->file('img');
        $img->storeAs('public/posts', $img->hashName());

        //create post
        makanan::create([
            'img'     => $img->hashName(),
            'nama'     => $request->nama,
            'harga'   => $request->harga
        ]);

        //redirect to index
        return redirect()->route('makanan.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }
     /**
     * show
     *
     * @param  mixed $id
     * @return View
     */
    public function show(string $id): View
    {
        //get post by ID
        $makanan = makanan::findOrFail($id);

        //render view with post
        return view('makan.show', compact('makanan'));
    }
      /**
     * edit
     *
     * @param  mixed $id
     * @return View
     */
    public function edit(string $id): View
    {
        //get post by ID
        $makanan = makanan::findOrFail($id);

        //render view with post
        return view('makan.edit', compact('makanan'));
    }
    
    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id): RedirectResponse
    {
        //validate form
        $this->validate($request, [
            'img'     => 'image|mimes:jpeg,jpg,png|max:5048',
            'nama'     => 'required|min:5',
            'harga'   => 'required'
        ]);

        //get post by ID
        $makanan = makanan::findOrFail($id);

        //check if image is uploaded
        if ($request->hasFile('img')) {

            //upload new image
            $img = $request->file('img');
            $img->storeAs('public/posts', $img->hashName());

            //delete old image
            Storage::delete('public/posts/'.$makanan->img);

            //update makanan with new image
            $makanan->update([
                'img'     => $img->hashName(),
                'nama'     => $request->nama,
                'harga'   => $request->harga
            ]);

        } else {

            //update makanan without image
            $makanan->update([
                'nama'     => $request->nama,
                'harga'   => $request->harga
            ]);
        }

        //redirect to index
        return redirect()->route('makanan.index')->with(['success' => 'Data Berhasil Diubah!']);
    }
     /**
     * destroy
     *
     * @param  mixed $post
     * @return void
     */
    public function destroy($id): RedirectResponse
    {
        //get post by ID
        $makanan = makanan::findOrFail($id);

        //delete image
        Storage::delete('public/posts/'. $makanan->img);

        //delete post
        $makanan->delete();

        //redirect to index
        return redirect()->route('makanan.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }
}
