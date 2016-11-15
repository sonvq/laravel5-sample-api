<?php

namespace App\Repositories\Backend\ProductCatalogue;

use App\Models\ProductCatalogue;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Events\Backend\ProductCatalogue\ProductCatalogueCreated;
use App\Events\Backend\ProductCatalogue\ProductCatalogueDeleted;
use App\Events\Backend\ProductCatalogue\ProductCatalogueUpdated;
use Spatie\PdfToImage\Pdf;
use Carbon\Carbon as Carbon;
use App\Components\SimpleImage;
use App\Models\Category;

/**
 * Class EloquentProductCatalogueRepository
 * @package App\Repositories\Backend\ProductCatalogue
 */
class EloquentProductCatalogueRepository implements ProductCatalogueRepositoryContract
{
    
	/**
     * @return mixed
     */
    public function getCount() {
        return ProductCatalogue::count();
    }

    /**
     * @return mixed
     */
    public function getForDataTable() {

        $productCatalogue = ProductCatalogue::select(['id', 'thumbnail', 'name', 'category_id', 'created_at', 'updated_at', 'deleted_at', 'user_id', 'description', 'status'])->whereNull('deleted_at')
            ->get();
        
        $arrayCategory = [];
        $categoryList = Category::select('id', 'name')->get();
        if (count($categoryList) > 0) {
            foreach ($categoryList as $singleCategory) {
                $arrayCategory[$singleCategory->id] = $singleCategory->name;
            }
        }        
        
        foreach ($productCatalogue as $key=>$singleObject) {
            $singleObject->category_name = '';
                    
            if(isset($singleObject->thumbnail) && !empty($singleObject->thumbnail)) {
                $singleObject->thumbnail = asset('/') . config('app.general_upload_folder') . '/' .  config('product-catalogue.upload_path') . '/' . $singleObject->thumbnail;
            }
            
            if(isset($singleObject->category_id) && !empty($singleObject->category_id)) {
                if (isset($arrayCategory[$singleObject->category_id])) {
                    $singleObject->category_name = $arrayCategory[$singleObject->category_id];
                }
            }
            
            $productCatalogue[$key] = $singleObject;
        }
        
        return $productCatalogue;
        
    }        

    /**
     * @param  $request
     * @throws GeneralException
     * @return bool
     */
    public function create($request)
    {        
        $productCatalogue = $this->createProductCatalogueStub($request, new ProductCatalogue);

		DB::transaction(function() use ($productCatalogue) {
            if (!empty($productCatalogue->category_id)) {
                $category = Category::where('id', $productCatalogue->category_id)->first();
                if ($category) {
                    $category->updated_at = Carbon::now();    
                    $category->save();
                }                
            }
            
            if ($productCatalogue->save()) {
                event(new ProductCatalogueCreated($productCatalogue));
                return true;
            }

        	throw new GeneralException(trans('exceptions.backend.product-catalogue.create_error'));
		});
    }

    /**
     * @param  ProductCatalogue $productCatalogue
     * @param  $request
     * @return mixed
     */
    public function update(ProductCatalogue $productCatalogue, $request) {
        $productCatalogue = $this->createProductCatalogueStub($request, $productCatalogue);
        DB::transaction(function() use ($productCatalogue) {
            if (!empty($productCatalogue->category_id)) {
                $category = Category::where('id', $productCatalogue->category_id)->first();
                if ($category) {
                    $category->updated_at = Carbon::now();    
                    $category->save();
                }                
            }
            
			if ($productCatalogue->update()) {
				event(new ProductCatalogueUpdated($productCatalogue));
				return true;
			}

        	throw new GeneralException(trans('exceptions.backend.product-catalogue.update_error'));
		});
    }

    /**
     * @param  ProductCatalogue $productCatalogue
     * @return mixed
     */
    public function destroy(ProductCatalogue $productCatalogue) {
        DB::transaction(function() use ($productCatalogue) {        
            if (!empty($productCatalogue->category_id)) {
                $category = Category::where('id', $productCatalogue->category_id)->first();
                if ($category) {
                    $category->updated_at = Carbon::now();    
                    $category->save();
                }                
            }
            
            if ($productCatalogue->delete()) {
                event(new ProductCatalogueDeleted($productCatalogue));
                return true;
            }
            
            throw new GeneralException(trans('exceptions.backend.product-catalogue.delete_error'));
		});
                
    }
    
    /**
     * @param  $input
     * @return mixed
     */
    private function createProductCatalogueStub($request, $productCatalogue)
    {
        $input = $request->all();

        $productCatalogue->name              = $input['name'];
        $productCatalogue->description       = $input['description'];
        $productCatalogue->category_id       = $input['category_id'];
        $productCatalogue->status            = isset($input['status']) ? $input['status'] : $productCatalogue->getDefaultStatusProductCatalogue();
        $productCatalogue->user_id           = auth()->id();
        
        if ($request->hasFile('image')) {
            $image = $request->file('image');                        
                    
            list($width, $height) = getimagesize($image);
            
            $uploadFolder = public_path() . DIRECTORY_SEPARATOR . config('app.general_upload_folder');
            
            if (!is_dir($uploadFolder)) {
                mkdir($uploadFolder, 0775, true);
            }
            
            $destinationPath = $uploadFolder . DIRECTORY_SEPARATOR . config('product-catalogue.upload_path');
            if (!is_dir($destinationPath)) {
                mkdir($destinationPath, 0775, true);
            }                       
            
            $extension = $image->getClientOriginalExtension();

            $imageName = 'product_catalogue_' . uniqid() . '_' . time() . '.' . $extension;                       
            
            $thumbnailName = 'product_catalogue_thumbnail_' . uniqid() . '_' . time() . '.' . $extension;
            $imagePath = $destinationPath . DIRECTORY_SEPARATOR . $imageName;
            $thumbnailPath = $destinationPath . DIRECTORY_SEPARATOR . $thumbnailName;
                                                
            exec('convert "' . $image . '[0]" -colorspace RGB -resize 776x450 "' . $imagePath . '"', $output, $return_var);     
            
            sleep(1);
            
            if ($width >= $height) {
                exec('convert "' . $imagePath . '[0]" -colorspace RGB -resize x250 "' . $thumbnailPath . '"', $output, $return_var);
                //exec('C:\ImageMagick-6.9.3-7-vc11-x86\bin\convert "' . $imagePath . '[0]" -colorspace RGB -resize x250 "' . $thumbnailPath . '"', $output, $return_var);
            } else {
                exec('convert "' . $imagePath . '[0]" -colorspace RGB -resize 250x "' . $thumbnailPath . '"', $output, $return_var);
                //exec('C:\ImageMagick-6.9.3-7-vc11-x86\bin\convert "' . $imagePath . '[0]" -colorspace RGB -resize 250x "' . $thumbnailPath . '"', $output, $return_var);
            }   
            
            $productCatalogue->image = $imageName;
            $productCatalogue->thumbnail = $thumbnailName;
        }      
        
        return $productCatalogue;
    }
}