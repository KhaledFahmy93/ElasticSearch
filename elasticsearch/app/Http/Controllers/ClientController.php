<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Elasticsearch\ClientBuilder;
use Elastica\Client as ElasticaClient;
use Elastica ;
use Faker\Factory as Faker;
  

class ClientController extends Controller
{
    // Elasticsearch-php Client
    protected $elasticsearch ;

    // Elastica Client
    protected $elastica; 

    protected $elasticaIndex ;

    // set up our clients
    public function __construct(){
        $this->elasticsearch = ClientBuilder::create()->build();

         $elasticaConfig = [
             'host'  => 'localhost' , 
             'port'  => 9200 , 
             'index' => 'pets'
         ];

         $this->elastica = new ElasticaClient($elasticaConfig);

       

         $this->elasticaIndex = $this->elastica->getIndex('pets');
    }


    public function elasticsearchTest(){
        dump($this->elasticsearch) ;

        echo "\n\n Retrieve a document: \n";
        $params = [
             'index' => 'pets' ,
             'type'  =>  'dog' , 
             'id'    =>   '2'
        ];
         $resposnse  = $this->elasticsearch->get($params);
         dump($resposnse);
    }

    public function elasticaTest(){
        dump($this->elastica);

        dump($this->elasticaIndex);

        echo "\n\nGet types and mappings";

        $dogType = $this->elasticaIndex->getType('dog');

        dump($dogType->getMapping());

        echo "\n\n Get a document";
        $resposnse = $dogType->getDocument('1');
        dump($resposnse);
    }

    public function elasticsearchData(){
        $params = [
            'index' => 'pets',
            'type'  => 'bird',
            'body'   => [
                'bird' => [
                    '_source' =>[
                        'enabled' => true
                    ],
                    'properties' => [
                        'name'      =>    array('type' => 'text'),
                        'age'       =>    array('type' => 'long'),
                        'gender'    =>    array('type' => 'text'),
                        'color'     =>    array('type' => 'text'),
                        'braveBird' =>    array('type' => 'boolean'),
                        'hometown'  =>    array('type' => 'text'),
                        'about'     =>    array('type' => 'text'),
                        'registered'=>    array('type' => 'date'),
                    ]
                ]
            ]        
    ];

        // create a new type 
        //    $resposnse = $this->elasticsearch->indices()->putMapping($params);
        //    dump($resposnse);


           //Get a mapping
           $params = [
                'index'    =>  'pets',
                'type'     =>  'bird'
           ];

           $resposnse = $this->elasticsearch->indices()->getMapping($params);
           dump($resposnse);


           // index a document 
           $params = [
                'index' => 'pets',
                'type'  => 'bird',
                'id'    => '1',
                'body'  => [
                    'name' => 'Mohamed Salah',
                    'age'  => '25',
                    'color' => 'male',
                    'braveBird' => true ,
                    'hometown'  => 'Cairo',
                    'about'  => "Lorem ipsum dolor" ,
                    'registered'   => date('Y-m-d'),
                ]
           ];

           $resposnse = $this->elasticsearch->index($params);
           dump($resposnse);

           // Bulk index documents
           $faker = Faker::create();

           $params = [];

           for($i = 0 ; $i < 100 ; $i++){
               $params['body'][] = [
                   'index' => [
                       '_index' => 'pets',
                       '_type'  => 'birds'
                   ]
                ];
                $gender = $faker->randomElement(['male' , 'female']);
                $age = $faker->numberBetween(1 , 15); 

                $params['body'][] = [
                    'name'       =>  $faker->name($gender),
                    'age'        =>  $age ,
                    'gender'     =>  $gender ,
                    'color'      =>  $faker->safeColorName ,
                    'braveBird'  =>  $faker->boolean , 
                    'hometown'   =>  "{ $faker->city} , {$faker->state}",
                    'about'      =>  $faker->realText() , 
                    'registered' =>  $faker->dateTimeBetween("-{$age} years" ,"now")->format('Y-m-d')
                ];

           }

           $resposnse = $this->elasticsearch->bulk($params);
           dump($resposnse);
    }

    public function elasticaData(){
        $catType = $this->elasticaIndex->getType('cat');

        $mapping = new Elastica\Type\Mapping($catType , [
            'name'        =>    array('type' => 'text'),
            'age'         =>    array('type' => 'long'),
            'gender'      =>    array('type' => 'text'),
            'color'       =>    array('type' => 'text'),
            'prettyKitty' =>    array('type' => 'boolean'),
            'hometown'    =>    array('type' => 'text'),
            'about'       =>    array('type' => 'text'),
            'registered'  =>    array('type' => 'date'),
        ]);


        // $resposnse = $mapping->send();
        // dump($resposnse);

        // Index a document
        $catDocument = new Elastica\Document();
        $catDocument->setData([
            'name'       =>   'Stevene Gerrared', 
            'age'        =>   '4' ,
            'gender'     =>   'male',
            'color'      =>   'Orange',
            'prettyKitty'=>   true ,
            'hometown'   => 'USA',
            'about'      => 'this is an Aweasome Elastic Search',
            'registered' => date('Y-m-d'),
        ]);

        $resposnse = $catType->addDocument($catDocument);
        dump($resposnse);

        // Bulk index documents
        $faker = Faker::create();

        $documents = [] ;

        for($i = 0 ; $i < 50 ; $i++ ){
            $gender = $faker->randomElement(['male' , 'female']);
            $age = $faker->numberBetween(1 , 15);

            $docements[] = (new Elastica\Document())->setData([
                'name'        => $faker->name($gender),
                'age'         => $age , 
                'gender'      => $gender , 
                'color'       => $faker->safeColorName ,
                'prettyKitty' => $faker->boolean , 
                'hometown'    => "{$faker->city} , {$faker->state}",
                'about'       => $faker->realText() ,
                'registered'  => $faker->dateTimeBetween("-{$age} years" ,"now")->format('Y-m-d')
            ]);
        }

        $resposnse = $catType->addDocuments($docements);
        dump($resposnse);   

    }
    public  function elasticsearchQueries(){
        // Run our query on the name field 
        $params = [
            'index'  => 'pets'  , 
            'type'   => 'bird' ,
            'body'  => [
                'query' => [
                    'match' => [
                        'name' => 'MD'
                    ]
                ]
            ]
        ];

        $resposnse  = $this->elasticsearch->search($params);
        dump($resposnse);

        $params = [
            'index'  => 'pets'  , 
            'type'   => 'bird' ,
            'size'   => 15 ,
            'body'  => [
                'query' => [
                    'match' => [
                        'name' => 'Alice'
                    ]
                ]
            ]
        ];

        $resposnse  = $this->elasticsearch->search($params);
        dump($resposnse);

        // Run a boolean query
        $params = [
            'index' => 'pets' , 
            'type'  => 'bird' , 
            'body'  => [
                'query' => [
                    'bool' => [
                        'must' => [
                            'match' => ['about'  => 'Alice' ]
                        ],
                        'should' => [
                            'term' => ['braveBird' => true],
                            'term' => ['gender' => 'male']
                        ],
                        'filter' => [
                            'range' => [
                                'registered' => [
                                    'gte' => '2015-01-01'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
        $resposnse = $this->elasticsearch->search($params);
        dump($resposnse);

    }

    public function elasticQueries(){
        // Get the cat Type
        $catType = $this->elasticaIndex->getType('cat');

        //Run Our query on the name field
        $query = new Elastica\Query;
        
        $match = new Elastica\Query\Match('name' , 'MD');
        $query->setQuery($match) ;

        $resposnse = $catType->search($query);
        dump($resposnse);

        //Run our Query on the about Field
        $query = new Elastica\Query;
        $match = new Elastica\Query\Match;
        $match->setField('about' , 'Alice');
        
        $query->setQuery($match);
        $query->setSize(15);

        $resposnse = $catType->search($query);
        dump($resposnse);


        // Run a Boolean Query
        $query = new Elastica\Query ; 
        
        $bool  = new Elastica\Query\BoolQuery ;

        $mustMatch = new Elastica\Query\Match('about' , 'Alice');
        $shouldOne = new Elastica\Query\Term(['prettyKitty' => true]);
        $shouldTwo = new Elastica\Query\Term(['gender' => 'female']);

        $filterRange = new Elastica\Query\Range('registered' , ['gte' => '2015-01-01']);

        $bool->addMust($mustMatch);
        $bool->addShould($shouldOne);
        $bool->addShould($shouldTwo );
        $bool->addFilter($filterRange);

        $query->setQuery($bool) ;

        $resposnse = $catType->search($query);
        dump($resposnse);
    }

    public function elasticaAdvanced(){  

    //Get the cat type
    $catType = $this->elasticaIndex->getType('cat');    

    // Aggregations , how pets are each age 
    $query = new Elastica\Query;

    $termsAgg = new Elastica\Aggregation\Terms('CatAges');
    $termsAgg->setField('age');

    $query->addAggregation($termsAgg);
    $query->setSize(0);

    $resposnse = $catType->search($query);
    dump($resposnse);

    // Aggregations , with a Query
    $query = new Elastica\Query  ;

    $bool = new Elastica\Query\BoolQuery;
    $mustMatch = new Elastica\Query\Match('about' , 'Alice');
    $filterRange  = new Elastica\Query\Range('registered' , ['gte' => '2015-01-01' ]);

    $bool->addMust($mustMatch);
    $bool->addFilter($filterRange);

    $dateHistogramAgg = new Elastica\Aggregation\DateHistogram('CatRegistrations' , 'registered' , 'year');

    $query->addAggregation($dateHistogramAgg);
    $query->setQuery($bool);

    $resposnse = $catType->search($query)->getAggregation('CatRegistrations');
    dump($resposnse);

    // Programatically Build a query with a few different search types
    $shoulds = [
        ['field' => 'about' , 'value' => 'alice'],
        ['field' => 'about' , 'value' => 'Queen'],
        ['field' => 'about' , 'value' => true]
    ];
    $musts = [
        ['field' => 'gender' , 'value' => 'female'],
        ['field' => 'color'  , 'value' => 'alive'],
    ];

    $qb = new Elastica\QueryBuilder ;
    $query = new Elastica\Query;
    $bool = $qb->query()->bool();

    foreach($shoulds as $should ){
        $bool->addShould($qb->query()->match($should['field'] , $should['value']));
    }

    foreach($musts as $must){
        $bool->addMust($qb->query()->term([$must['field'] => $must['value']]));
    }

    $query->setQuery($bool);

    $resposnse = $catType->search($query);
    dump($resposnse);
        
    }








}
