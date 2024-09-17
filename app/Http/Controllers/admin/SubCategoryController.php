<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class SubCategoryController extends Controller
{

    public function index(Request $request){
        $subCategories = SubCategory::select('sub_categories.*','categories.name as categoryName')
        ->latest('sub_categories.id')
        ->leftJoin('categories', 'categories.id', 'sub_categories.category_id');

        if(!empty($request->get('keyword'))){
            $subCategories = $subCategories->where('sub_categories.name', 'like', '%'.$request->get('keyword').'%');
            $subCategories = $subCategories->orWhere('categories.name', 'like', '%'.$request->get('keyword').'%');

        }
        $subCategories = $subCategories->paginate(10);

        return view('admin.sub_category.list', compact('subCategories'));

    }
    public function create(){
        $subCategories = Category::orderBy('name', 'ASC')->get();
        $data['categories']=$subCategories;
        return view('admin.sub_category.create', $data);
    }



    public function store(Request $request){
     $validator=Validator::make($request->all(),[
        'name'=>'required',
        'slug'=>'required|unique:sub_categories',
        'category' => 'required',
        'status'=>'required'
     ]);
     //dd($request->all());
     if($validator->passes()){

            $subCategory=new SubCategory();
            $subCategory->name = $request->name;
            $subCategory->slug = $request->slug;
            $subCategory->status = $request->status;
            $subCategory->showHome = $request->showHome;
            $subCategory->category_id = $request->category;
            // $subCategory->name = $request->category;
            $subCategory->save();

            session()->flash('success','Sub Category created successfully.');


            return response([
                'status'=>True,
                'message'=> 'Sub Category created successfully.'
                
            ]);

     } else{
        return response([
            'status'=>false,
            'errors'=>$validator->errors()
        ]);
     }
    }


    public function edit($id, Request $request){

        $subCategory = SubCategory::find($id);
        if(empty($subCategory)){
            session()->flash('error','Record not found');
            return redirect()->route('sub-categories.index');

            
        }


        $subCategories = Category::orderBy('name', 'ASC')->get();
        $data['categories']=$subCategories;
        $data['subCategory']=$subCategory;
        return view('admin.sub_category.edit', $data);
        
    }

    public function update($id, Request $request){

        $subCategory = SubCategory::find($id);
        if(empty($subCategory)){
            session()->flash('error','Record not found');

            return response([
                'status'=>false,
                'notFound'=>true
            ]);
            

            
        }

        $validator=Validator::make($request->all(),[
            'name'=>'required',
            'slug'=>'required|unique:sub_categories,slug, '.$subCategory->id.',id',
            'category' => 'required',
            'status'=>'required'
         ]);
         
         if($validator->passes()){
    
                
                $subCategory->name = $request->name;
                $subCategory->slug = $request->slug;
                $subCategory->status = $request->status;
                $subCategory->showHome = $request->showHome;
                $subCategory->category_id = $request->category;
                $subCategory->save();
    
                session()->flash('success','Sub Category updated successfully.');
    
    
                return response([
                    'status'=>True,
                    'message'=> 'Sub Category updated successfully.'
                    
                ]);
    
         } else{
            return response([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);
         }

    }

    public function destroy($id, Request $request){

        $subCategory = SubCategory::find($id);
        if(empty($subCategory)){
            session()->flash('error','Record not found');

            return response([
                'status'=>false,
                'notFound'=>true
            ]);
         }
         $subCategory->delete();

         session()->flash('success','Sub Category deleted successfully.');

         return response([
            'status'=>True,
            'message'=> 'Sub Category deleted successfully.'
            
        ]);

    }

}
