<?php

namespace App\Database\Seeds;

use App\Models\Category as CategoryModel;
use CodeIgniter\Database\Seeder;
use Faker\Factory;
use ReflectionException;

class Category extends Seeder
{
    /**
     * @throws ReflectionException
     */
    public function run()
    {
        $category = new CategoryModel();
        $category->truncate();
        $faker = Factory::create();

        for ($i = 1; $i <= 12; $i++) {
            $category->save(
                [
                    'name' => 'Danh Mục ' . $i,
                    'slug' => $faker->slug(),
                    'description' => $faker->text(160),
                    'image' => $faker->imageUrl(200, 200),
                    'parent_id' => 0,
                    'status' => rand(0, 1),
                    'featured' => rand(0, 1),
                    'meta_title' => $faker->text(60),
                    'meta_keyword' => $faker->text(160),
                    'meta_description' => $faker->text(160)
                ]
            );

            $getID = $category->getInsertID();

            for ($j = 1; $j <= 19; $j++) {
                $category->save(
                    [
                        'name' => 'Danh Mục ' . $i . '.' . $j,
                        'slug' => $faker->slug(),
                        'description' => $faker->text(160),
                        'image' => $faker->imageUrl(200, 200),
                        'parent_id' => $getID,
                        'status' => rand(0, 1),
                        'featured' => rand(0, 1),
                        'meta_title' => $faker->text(60),
                        'meta_keyword' => $faker->text(160),
                        'meta_description' => $faker->text(160)
                    ]
                );
            }
        }
    }
}
