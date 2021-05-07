<?php


declare(strict_types=1);


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{

    public $timestamps = false;
    private static  array $parents = [];
    private static array $categories = [];
    private static  array $structure = [];
    protected $table = 'categories';
    protected $guarded = [];


    public static function setCategories(string $order = null):void
    {
        if ($order == null){

            self::$categories = self::select('*')->get()->toArray();

        } elseif ($order == 'asc'){

            self::$categories = self::select('*')
            ->orderBy('text','asc')
            ->get()
            ->toArray();

        } elseif ($order == 'desc'){

            self::$categories = self::select('*')
            ->orderBy('text','desc')
            ->get()
            ->toArray();
        }

    }
    // struktura parent_id => wiersze dzieci
    public static  function createStructure():array
    {


        foreach (self::$categories as $parent){

           if (!array_key_exists($parent['parent_id'], self::$structure)){

            foreach (self::$categories as $row){
                if ($parent['parent_id'] == $row['parent_id']){
                    self::$structure[$parent['parent_id']][] = $row;
                }
            }

           }
        }
        return self::$structure;


    }

    // struktura drzewiasta
    public static function buildTree(int $n = 0):string
    {
        if (isset(self::$structure[$n])){

            $html = "<ul>";

            foreach (self::$structure[$n] as $item){
                //   jeśli $item[$id] należy do tablicy rodziców
                   if (array_key_exists($item['id'],array_flip(self::$parents)) ){

                        $html .= "<li class='items'>
                        <button class='show'>+</button><button class='hide'>-</button><span class='text'>".htmlentities($item['text'])."</span>
                        <button class='addCat' data-idAddCategory='{$item['id']}'>add</button>
                        <button class='delete' data-idDel='{$item['id']}'>delete</button>
                        <button class='edit' data-idEdit='{$item['id']}'>edit</button>
                        <button class='move' data-idMove='{$item['id']}'>move</button>
                        <button class='moveTo' data-idMoveTo='{$item['id']}'>moveTo</button></li>";
                        $html .= self::buildTree((int)$item['id']);
                        $html .= "</li>";

                   }else{
                    // jeśli jest dzieckiem
                   $html .= "<li class='items'><span class='text'>".htmlentities($item['text'])."</span>
                   <button class='addCat' data-idAddCategory='{$item['id']}'>add</button>
                   <button class='delete' data-idDel='{$item['id']}'>delete</button>
                   <button class='edit' data-idEdit='{$item['id']}'>edit</button>
                   <button class='move' data-idMove='{$item['id']}'>move</button>
                   <button class='moveTo' data-idMoveTo='{$item['id']}'>moveTo</button></li>";
                   $html .= self::buildTree((int)$item['id']);

                   $html .= "</li>";
                   }

            }
            $html .= "</ul>";

            return $html;
     }

     return "";

    }


    public static  function setParents():void
    {

        $parents = [];
        foreach (self::$categories as $parent) {
            $parents[] = $parent['parent_id'];
        }

        self::$parents = $parents;


    }

    public static function deleteCategory(int $id):void
    {
        // jeśli element jest liściem to usuń
        if (empty(self::getParentChildren()[$id])){

            self::find($id)->delete();

      } else {
            // jeśli jest węzłem to pobierz dzieci  i rodzica w postaci tablicy
            $elementsToDelete = self::pathToDelete($id);
            //  wykonaj rekurencyjnie metode deleteCategory
            foreach ($elementsToDelete as $key => $value){
                        self::deleteCategory($value);
            }
        }
    }


    private static  function pathToDelete(int $id):array
    {

        $categories = self::select('*')->get()->toArray();
        $children = [];
        // dla rodzica pobierz dzieci
        foreach ($categories as $row){

             if ($id == $row['parent_id']){
                $children[] = $row['id'];
             }
        }
        if($id != '1'){
            // scal id rodzica i dzieci w jedną tablicę jako ścieżke do usunięcia
            $idArray = [$id];
            $mergeArray = array_merge($idArray,$children);

        } else {

                    $mergeArray = $children;

                }

        return array_reverse($mergeArray);

    }
    // id rodzica => [id dzieci]
    private static function getParentChildren():array
    {
        $parentChildren = [];
        $categories = self::select('*')->get()->toArray();

        foreach ($categories as $id){
                    $children = [];
                    foreach ($categories as $row){
                            if ($id['id'] == $row['parent_id']){
                                        $children[] = $row['id'];
                            }
                }
                    $parentChildren[$id['id']] = $children;
          }

        return $parentChildren;

    }



}
