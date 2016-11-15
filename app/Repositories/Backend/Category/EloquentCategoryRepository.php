<?php

namespace App\Repositories\Backend\Category;

use App\Models\Category;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Events\Backend\Category\CategoryCreated;
use App\Events\Backend\Category\CategoryDeleted;
use App\Events\Backend\Category\CategoryUpdated;
use Spatie\PdfToImage\Pdf;
use App\Components\SimpleImage;

/**
 * Class EloquentCategoryRepository
 * @package App\Repositories\Backend\Category
 */
class EloquentCategoryRepository implements CategoryRepositoryContract
{
    
	/**
     * @return mixed
     */
    public function getCount() {
        return Category::count();
    }

    /**
     * @return mixed
     */
    public function getForDataTable() {
        $categoryList = Category::select(['id', 'name', 'thumbnail', 'user_id', 'created_at', 'updated_at', 'deleted_at'])->whereNull('deleted_at')
            ->get();
        
        foreach ($categoryList as $key=>$singleObject) {
            
            if(isset($singleObject->thumbnail) && !empty($singleObject->thumbnail)) {
                $singleObject->thumbnail = asset('/') . config('app.general_upload_folder') . '/' .  config('category.upload_path') . '/' . $singleObject->thumbnail;
            }
            $categoryList[$key] = $singleObject;
        }
        
        return $categoryList;
    }        

    /**
     * @param  $request
     * @throws GeneralException
     * @return bool
     */
    public function create($request)
    {        
        
        $category = $this->createCategoryStub($request, new Category);

		DB::transaction(function() use ($category) {
			if ($category->save()) {

				event(new CategoryCreated($category));
				return true;
			}

        	throw new GeneralException(trans('exceptions.backend.category.create_error'));
		});
    }

    /**
     * @param  Category $category
     * @param  $request
     * @return mixed
     */
    public function update(Category $category, $request) {
        $category = $this->createCategoryStub($request, $category);
        DB::transaction(function() use ($category) {
			if ($category->update()) {

				event(new CategoryUpdated($category));
				return true;
			}

        	throw new GeneralException(trans('exceptions.backend.category.update_error'));
		});
    }

    /**
     * @param  Category $category
     * @return mixed
     */
    public function destroy(Category $category) {    
        DB::transaction(function() use ($category) {
            if(count($category->product_catalogue())) {
               $category->product_catalogue()->delete();
            }
            if ($category->delete()) {
                event(new CategoryDeleted($category));                
                return true;
            }
			throw new GeneralException(trans('exceptions.backend.category.delete_error'));
		});        

    }
    
    /**
     * @param  $input
     * @return mixed
     */
    private function createCategoryStub($request, $category)
    {
        $input = $request->all();
        
        $category->name              = $input['name'];        
        $category->user_id           = auth()->id();
        
        if ($request->hasFile('image')) {
            $image = $request->file('image');                        
                    
            $uploadFolder = public_path() . DIRECTORY_SEPARATOR . config('app.general_upload_folder');
            
            if (!is_dir($uploadFolder)) {
                mkdir($uploadFolder, 0775, true);
            }
            
            $destinationPath = $uploadFolder . DIRECTORY_SEPARATOR . config('category.upload_path');
            if (!is_dir($destinationPath)) {
                mkdir($destinationPath, 0775, true);
            }                       
            
            $extension = $image->getClientOriginalExtension();

            $imageName = 'category_' . uniqid() . '_' . time() . '.' . $extension;
            
            $image->move($destinationPath, $imageName);
            
            
            $thumbnailName = 'category_thumbnail_' . uniqid() . '_' . time() . '.' . $extension;
            $imagePath = $destinationPath . DIRECTORY_SEPARATOR . $imageName;
            $thumbnailPath = $destinationPath . DIRECTORY_SEPARATOR . $thumbnailName;
            
            list($width, $height) = getimagesize($imagePath);
            if ($width >= $height) {
                exec('convert "' . $imagePath . '[0]" -colorspace RGB -resize x250 "' . $thumbnailPath . '"', $output, $return_var);
                //exec('C:\ImageMagick-6.9.3-7-vc11-x86\bin\convert "' . $imagePath . '[0]" -colorspace RGB -resize x250 "' . $thumbnailPath . '"', $output, $return_var);
            } else {
                exec('convert "' . $imagePath . '[0]" -colorspace RGB -resize 250x "' . $thumbnailPath . '"', $output, $return_var);
                //exec('C:\ImageMagick-6.9.3-7-vc11-x86\bin\convert "' . $imagePath . '[0]" -colorspace RGB -resize 250x "' . $thumbnailPath . '"', $output, $return_var);
            }            
            
            $category->image = $imageName;
            $category->thumbnail = $thumbnailName;
        }        
        
        return $category;
    }
}