<?php

use Muebles\Core\CommandBus;
use Muebles\Forms\ProductRegistrationForm;
use Muebles\Products\Product;
use Muebles\Products\ProductRepository;
use Muebles\Products\RegisterProductCommand;
use Muebles\Forms\EditProductForm;

class ProductsController extends \BaseController {

	use CommandBus;

	/**
	 * @var ProductRepository
	 */
	private $repository;

	/**
	 * @var ProductRegistrationForm
	 */
	private $productRegistrationForm;

	private $editProductForm;
	/**
	 * @param ProductRegistrationForm $productRegistrationForm
	 * @param ProductRepository $repository
	 */
	function __construct(ProductRegistrationForm $productRegistrationForm, ProductRepository $repository,EditProductForm $editProductForm)
	{
		$this->productRegistrationForm = $productRegistrationForm;
		$this->repository = $repository;
		$this->editProductForm = $editProductForm;
		$this->beforeFilter('admin', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$products = $this->repository->getAll();
		return View::make('products.index', compact('products'));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('products.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$formData = Input::all();
		$this->productRegistrationForm->validate($formData);
		extract($formData);
		$product = $this->execute(new RegisterProductCommand($codigo, $nombre, $descripcion, $medidas, $precio_lacado, $precio_lacado_puntos, $precio_pulimento, $precio_pulimento_puntos, $cantidad, $precio));
		Flash::success('El mueble ha sido registrado con éxito!');
		if($formData['do'] == 1) {
			$id = $product->id;
			return Redirect::route('photos.create', compact('id'));
		}
		return Redirect::route('products.index');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$product = Product::findOrFail($id);
		return View::make('products.view', compact('product'));
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$product = Product::find($id);
		//var_dump($product);
		return View::make('products.edit',compact('product'));
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$this->editProductForm->validate(Input::all());
		$product = Product::find($id);
		$product->codigo = Input::get('codigo');
		$product->nombre = Input::get('nombre');
		$product->descripcion = Input::get('descripcion');
		$product->medidas = Input::get('medidas');
		$product->precio_lacado = Input::get('precio_lacado');
		$product->precio_lacado_puntos = Input::get('precio_lacado_puntos');
		$product->precio_pulimento = Input::get('precio_pulimento');
		$product->precio_pulimento_puntos = Input::get('precio_pulimento_puntos');
		$product->cantidad = Input::get('cantidad');
		$product->precio = Input::get('precio');
		$product->save();
		Flash::message('Otro producto ha sido actualizado con éxito!');
		return Redirect::to('products');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$product = Product::find($id);
		$product->delete();
		Flash::message('producto borrado  con éxito!');
		return Redirect::to('products');
	}

	/**
	 * Get datatable data for datatables package
	 *
	 * @return mixed
	 */
	public function getDatatable()
	{
		$collection = Datatable::collection($this->repository->getAll());
			//->showColumns('codigo', 'nombre', 'modelo', 'medidas', 'lacado', 'precio_lacado', 'pulimento', 'precio_pulimento', 'cantidad', 'precio')

		$collection->addColumn('foto', function($model)
		{
			foreach ($model->photos as $photo) {
				$links = "<a href='" . route('products.show', $model->id) . "'>
						<img class='mini-photo' alt='" . $photo->filename . "' src='" . asset($photo->path . $photo->filename) . "'>
					</a>
					<br />";

				return $links;
			}
		});

		$collection->addColumn('codigo', function($model)
		{
			return strtoupper($model->codigo);
		});

		$collection->addColumn('nombre', function($model)
		{
			return ucfirst(strtolower($model->nombre));
		});

/*		$collection->addColumn('modelo', function($model)
		{
			return strtoupper($model->modelo);
		});*/

		$collection->addColumn('medidas', function($model)
		{
			return $model->medidas;
		});

		/*$collection->addColumn('lacado', function($model)
		{
			return ($model->lacado == 1) ? 'Si' : 'No';
		});

		$collection->addColumn('precio_lacado', function($model)
		{
			return number_format($model->precio_lacado, 2, ',', '.');
		});

		$collection->addColumn('pulimento', function($model)
		{
			return ($model->pulimento == 1) ? 'Si' : 'No';
		});

		$collection->addColumn('precio_pulimento', function($model)
		{
			return number_format($model->precio_pulimento, 2, ',', '.');
		});

		$collection->addColumn('cantidad', function($model)
		{
			return $model->cantidad;
		});

		$collection->addColumn('precio', function($model)
		{
			return number_format($model->precio, 2, ',', '.');
		});*/

		$collection->addColumn('ver', function($model)
		{
			$links = "<a href='" . route('products.show', $model->id) . "'>Ver</a>
					<br />";

			if(Auth::check() AND Auth::user()->rol == 'admin') {
				$links .= "<a href='" . route('products.edit', $model->id) . "'>Editar</a>
					<br />
					<a href='" . URL::to('borrarProduct/'.$model->id) . "'>Eliminar</a>";
			}

			return $links;
		});

		$collection->searchColumns('nombre', 'codigo');
		$collection->orderColumns('codigo','nombre');

		return $collection->make();
	}
}
