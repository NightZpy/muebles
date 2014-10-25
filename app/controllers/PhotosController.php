<?php

use Muebles\Photos\Photo;
use Muebles\Products\Product;

class PhotosController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($productId)
	{
		$nombre = Product::findOrFail($productId)->nombre;
		return View::make('photos.create', compact('productId', 'nombre'));
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$file = Input::file('file');
		$productId = Input::get('product_id');
		$photo = new Photo();

		try {
			$photo->process($file, $productId);
		} catch(Exception $exception){
			// Something went wrong. Log it.
			Log::error($exception);
			// Return error
			return Response::json($exception->getMessage(), 400);
		}

		// If it now has an id, it should have been successful.
		if ( $photo->id ) {
			return Response::json(array('status' => 'success', 'file' => $photo->toArray()), 200);
		} else {
			return Response::json('Error', 400);
		}
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
