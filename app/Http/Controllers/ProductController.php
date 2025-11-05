<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Product\Type;
use App\Models\Product\Status;

/**
 * Отвечает за продукты
 */
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('status')->get();
        return view('product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $types = Type::all();
        $statuses = Status::all();
        return view('product.create', compact('types', 'statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'img' => 'required|string',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cost' => 'required|numeric',
            'type_id' => 'required|integer',
            'status_id' => 'required|integer',
            'limit' => 'required|integer',
        ]);
        Product::create($request->all());
        return redirect()->route('product.create')->with('success', 'Продукт создан!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $products = Product::where('id', $id)->where('status_id', 1)->with('status', 'type')->firstOrFail();
        if ($products->isEmpty()) {
            abort(404);
        }
        return view('product.index', compact('products'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::where('id', $id)->first();
        $types = Type::all();
        $statuses = Status::all();
        return view('product.edit', compact('product', 'types', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'img' => 'required|string',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cost' => 'required|numeric',
            'type_id' => 'required|integer',
            'status_id' => 'required|integer',
            'limit' => 'required|integer',
        ]);
        $product = Product::findOrFail($id);
        $product->update($request->all());
        return redirect()->route('product.page.edit', $id)->with('success', 'Продукт обновлен!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete($id);
        return redirect()->route('admin.index')->with('success', 'Продукт удален!');
    }
}
