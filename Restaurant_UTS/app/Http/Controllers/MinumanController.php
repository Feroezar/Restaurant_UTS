<?php

namespace App\Http\Controllers;

use App\Models\minuman;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MinumanController extends Controller
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
        $minuman = minuman::latest()->paginate($pagination);

        //render view with posts
        return view('minum.index', compact('minuman'))->with('i', ($request->input('page', 1) - 1)*$pagination);
    }
     /**
     * create
     *
     * @return View
     */
    public function create(): View
    {
        return view('minum.create');
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
        minuman::create([
            'img'     => $img->hashName(),
            'nama'     => $request->nama,
            'harga'   => $request->harga
        ]);

        //redirect to index
        return redirect()->route('minuman.index')->with(['success' => 'Data Berhasil Disimpan!']);
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
        $minuman = minuman::findOrFail($id);

        //render view with post
        return view('minum.show', compact('minuman'));
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
        $minuman = minuman::findOrFail($id);

        //render view with post
        return view('minum.edit', compact('minuman'));
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
        $minuman = minuman::findOrFail($id);

        //check if image is uploaded
        if ($request->hasFile('img')) {

            //upload new image
            $img = $request->file('img');
            $img->storeAs('public/posts', $img->hashName());

            //delete old image
            Storage::delete('public/posts/'.$minuman->img);

            //update minuman with new image
            $minuman->update([
                'img'     => $img->hashName(),
                'nama'     => $request->nama,
                'harga'   => $request->harga
            ]);

        } else {

            //update minuman without image
            $minuman->update([
                'nama'     => $request->nama,
                'harga'   => $request->harga
            ]);
        }

        //redirect to index
        return redirect()->route('minuman.index')->with(['success' => 'Data Berhasil Diubah!']);
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
        $minuman = minuman::findOrFail($id);

        //delete image
        Storage::delete('public/posts/'. $minuman->img);

        //delete post
        $minuman->delete();

        //redirect to index
        return redirect()->route('minuman.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }
}
