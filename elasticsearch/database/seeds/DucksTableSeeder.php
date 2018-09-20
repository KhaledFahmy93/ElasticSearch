<?php
use App\Duck;
use Illuminate\Database\Seeder;

class DucksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
     $faker = Faker\Factory::create();

     //Create 100 sample ducks
     $gender = $faker->randomElement(['male' , 'female']);
     $age =  $faker->numberBetween( 1 , 25 );
     foreach( range(1 ,100) as $index){
         Duck::create([
            'name'       =>   $faker->name($gender),
            'age'        =>   $age ,
            'gender'     =>   $gender , 
            'color'      =>   $faker->safeColorName ,
            'hometown'   =>   "{$faker->city} , {$faker->state}",
            'fuckyDuck'  =>   $faker->boolean ,
            'about'      =>   $faker->realText(),
            'registered'  =>   $faker->dateTimeBetweeN("-{$age} years" , 'now')->format('Y-m-d')
         ]);
       }
    }
}
