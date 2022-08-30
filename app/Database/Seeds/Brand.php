<?php

namespace App\Database\Seeds;

use App\Models\Brand as BrandModel;
use CodeIgniter\Database\Seeder;
use Faker\Factory;
use ReflectionException;

class Brand extends Seeder
{
    /**
     * @throws ReflectionException
     */
    public function run()
    {
        $brand = new BrandModel();
        $brand->truncate();
        $faker = Factory::create();

        for ($i = 1; $i <= 100; $i++) {
            $brand->save(
                [
                    'name' => 'Thương hiệu ' . $i,
                    'slug' => $faker->slug(),
                    'description' => $faker->text(160),
                    'image' => $faker->imageUrl(200, 200),
                    'status' => rand(0, 1)
                ]
            );
        }
    }
}
