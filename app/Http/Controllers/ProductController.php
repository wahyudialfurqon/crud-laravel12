<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class ProductController extends Controller
{
    public function index() : View {
        $products = Product::latest()->paginate(10);
        return view ('products.index', compact('products'));
    }

    public function create() : View {
        return view('products.create');
    }

    public function store(Request $request): RedirectResponse{

        //kode validasi imputan

        $request -> validate([
            'image' => 'required|image|mimes:jpeg, jpg, png|max:2048',
            'title' => 'required|min:5',
            'description' => 'required|min:10',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
        ]);

        //Uploads Image
        $image = $request->file('image');
        $image ->storeAs('products', $image->hashName());

        //kirim data input ke tabel DB
        Product::create([
            'image'=> $image->hashName(),
            'title'=> $request->title,
            'description'=> $request->description,
            'price'=> $request->price,
            'stock'=> $request->stock,

        ]);
        return redirect()-> route('products.index')->with(['success' => 'Data berhasil disimpan']);
    }

    public function show(string $id): View{
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }

    public function edit(string $id): View{
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, $id): RedirectResponse{

        //kode validasi imputan

        $request -> validate([
            'image' => 'required|image|mimes:jpeg, jpg, png|max:2048',
            'title' => 'required|min:5',
            'description' => 'required|min:10',
            'price' => 'required|numeric',
            'stock' => 'required|numeric'
        ]);

        $product = Product::findOrFail($id);
        if($request->hasFile('image')){
            Storage::delete('products/' . $product->image);

            //Uploads Image
            $image = $request->file('image');
            $image ->storeAs('products', $image->hashName());

            $product->update([
                'image'=> $image->hashName(),
                'title'=> $request->title,
                'description'=> $request->description,
                'price'=> $request->price,
                'stock'=> $request->stock,

            ]);
        }else{
            $product->update([
                'title'=> $request->title,
                'description'=> $request->description,
                'price'=> $request->price,
                'stock'=> $request->stock,

            ]);
        }

        return redirect()-> route('products.index')->with(['success' => 'Data berhasil disimpan']);
    }

    public function destroy($id): RedirectResponse{
        $product = Product::findOrFail($id);
        Storage::delete('products/' . $product->image);
        $product->delete();

        return redirect()-> route('products.index')->with(['success' => 'Data berhasil dihapus']);
    }

}
