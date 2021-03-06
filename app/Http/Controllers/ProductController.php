<?php

namespace App\Http\Controllers;
use App\Http\Requests;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(){
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();
        if($products->count()<1){
            return response()->json(['error'=>"product does not have data"],200);

        }
        return response()->json(['products'=>$products],200);
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\ProductRequest $request)
    {

        $data           =$this->handleRequest($request);
        $slugarray      =explode(" ", $request->title);
        $slug           =implode("-", $slugarray);
        $data['slug']   = $slug;
        if($request->submitbutton){
            $data['published_at']=1;
        }
        elseif($request->submitdraftbutton){
            $data['published_at']=0;
        }


        $product=Product::create($data);
        return response()->json($product);
    }
   

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product=Product::where('id',$id)->get();
        return response()->json(["product"=>$product],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function apiUpdate(Request $request){

        $product=Product::where('id',$request->segments()[3])->get();

        // $request->validate([
        //     'title' => 'required',
        //     'description'          =>'required',
        //     'price'                 =>'required',
        // ]);
        $data['title'] = $request->title;
        $data['description'] = $request->description;
        $data['price'] = $request->price;
        if($file = $request->file('image')){
        $filename = $request->file('image')->getClientOriginalName();
        $uploadPath         =public_path('uploads');
        $destinationPath    =$uploadPath;
        $successUploaded=$request->file('image')->move($destinationPath, $filename);
         $data['image'] = $filename;
         $newProduct = Product::where('id',$request->segments()[3])->update(['title' => $data['title'],'description'=>$data['description'],'price'=>$data['price'],'image'=>$data['image']]);
         return response()->json($newProduct);

        }else{
            $newProduct = Product::where('id',$request->segments()[3])->update(['title' => $data['title'],'description'=>$data['description'],'price'=>$data['price']]);
            return response()->json($newProduct);

        }
        



    }
    private function handleRequest($request){
        $data=$request->all();
        if( $request->hasFile('image')){

            $image              =$request->file('image');
            $filename           =$image->getClientOriginalName();
            $uploadPath         =public_path('uploads');
            $destinationPath    =$uploadPath;
            $successUploaded=$image->move($destinationPath, $filename);

            $data['image']=$filename;

        }
        return $data;
    }
    public function update(Requests\ProductRequest $request,$id)
    {

        // $product=Product::findOrFail($request->id);
        return response()->json($$id);

        $oldImage=$post->image;
        if($oldImage!=$post->image);
        $this->removeImage($oldImage);
        $data=$this->handleRequest($request);
        $post->update($data);
        // return respose()->json($product);
    }
    private function removeImage($image){
        $uploadPath         =public_path('uploads');
        $destinationPath    =$uploadPath;
        $imagePath     =$uploadPath .'/'.$image;
        $extention     =substr(strrchr($image,'.'),1);


        if($imagePath && file_exists(public_path('uploads').'/'.$image)) unlink($imagePath);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product=Product::where('id',$id)->delete();
        return response()->json(['msg'],200);
    }
}
