<?php

namespace Database\Seeders;

use App\Models\Species;
use Illuminate\Database\Seeder;

class SpeciesSeeder extends Seeder
{
    public function run(): void
    {
        $species = [
            // LEGENDARY - extremely rare, iconic Slovenian wildlife
            [
                'common_name'     => 'Brown Bear',
                'scientific_name' => 'Ursus arctos',
                'category'        => 'mammal',
                'rarity'          => 'legendary',
                'description'     => 'The European brown bear is the largest predator in Slovenia. The Dinaric Alps host one of the densest bear populations in Europe.',
                'habitat'         => 'Dense forests of the Dinaric Alps and Kočevje region',
            ],
            [
                'common_name'     => 'Gray Wolf',
                'scientific_name' => 'Canis lupus',
                'category'        => 'mammal',
                'rarity'          => 'legendary',
                'description'     => 'The gray wolf was nearly extinct in Slovenia but has made a strong comeback. Pack territories span across the Dinaric mountain range.',
                'habitat'         => 'Forests and mountains of southern Slovenia',
            ],
            [
                'common_name'     => 'Eurasian Lynx',
                'scientific_name' => 'Lynx lynx',
                'category'        => 'mammal',
                'rarity'          => 'legendary',
                'description'     => 'Reintroduced to Slovenia in 1973, the Eurasian lynx is a secretive and solitary apex predator. Spotting one in the wild is extraordinarily rare.',
                'habitat'         => 'Dense mountain forests',
            ],

            // EPIC - very rare or endemic species
            [
                'common_name'     => 'Olm (Proteus)',
                'scientific_name' => 'Proteus anguinus',
                'category'        => 'amphibian',
                'rarity'          => 'epic',
                'description'     => 'The olm is a blind cave salamander endemic to the Dinaric karst. It can live over 100 years and is completely adapted to underground life.',
                'habitat'         => 'Underground cave systems of the Karst region',
            ],
            [
                'common_name'     => 'Marble Trout',
                'scientific_name' => 'Salmo marmoratus',
                'category'        => 'fish',
                'rarity'          => 'epic',
                'description'     => 'The marble trout is endemic to the Soča river system in western Slovenia. Its spectacular marbled pattern makes it one of the most beautiful freshwater fish in Europe.',
                'habitat'         => 'Cold, clear mountain rivers (Soča, Idrijca)',
            ],
            [
                'common_name'     => 'Carniolian Primrose',
                'scientific_name' => 'Primula carniolica',
                'category'        => 'plant',
                'rarity'          => 'epic',
                'description'     => 'A flower endemic to Slovenia, found only in the Julian Alps and Karavanke mountains. It is a national symbol of Slovenian natural heritage.',
                'habitat'         => 'Rocky limestone slopes of the Julian Alps',
            ],

            // RARE - hard to find but present
            [
                'common_name'     => 'Chamois',
                'scientific_name' => 'Rupicapra rupicapra',
                'category'        => 'mammal',
                'rarity'          => 'rare',
                'description'     => 'The chamois is an agile mountain goat-antelope found on the rocky slopes of the Slovenian Alps. They are excellent climbers able to leap 2 metres vertically.',
                'habitat'         => 'Alpine rocky terrain above the treeline',
            ],
            [
                'common_name'     => 'Golden Eagle',
                'scientific_name' => 'Aquila chrysaetos',
                'category'        => 'bird',
                'rarity'          => 'rare',
                'description'     => 'The golden eagle is one of the most powerful raptors in Europe. In Slovenia they nest in the Alps and hunt across large open territories.',
                'habitat'         => 'Mountain ranges and open alpine terrain',
            ],
            [
                'common_name'     => 'Huchen',
                'scientific_name' => 'Hucho hucho',
                'category'        => 'fish',
                'rarity'          => 'rare',
                'description'     => 'The Danube salmon or huchen is one of the largest freshwater fish in Europe. It is an apex predator of Slovenian rivers and is strictly protected.',
                'habitat'         => 'Large, fast-flowing rivers (Sava, Drava)',
            ],
            [
                'common_name'     => 'Stag Beetle',
                'scientific_name' => 'Lucanus cervus',
                'category'        => 'insect',
                'rarity'          => 'rare',
                'description'     => 'Europe\'s largest beetle, named for the male\'s enormous mandibles resembling stag antlers. Larvae develop inside rotting oak wood for several years.',
                'habitat'         => 'Old-growth broadleaf forests, especially oak',
            ],
            [
                'common_name'     => 'Apollo Butterfly',
                'scientific_name' => 'Parnassius apollo',
                'category'        => 'insect',
                'rarity'          => 'rare',
                'description'     => 'A large, striking butterfly with white wings and red eye-spots. It inhabits alpine meadows and is considered a flagship species for mountain biodiversity.',
                'habitat'         => 'Alpine and subalpine meadows',
            ],
            [
                'common_name'     => 'Edelweiss',
                'scientific_name' => 'Leontopodium nivale',
                'category'        => 'plant',
                'rarity'          => 'rare',
                'description'     => 'The iconic alpine flower, symbol of mountain purity. Edelweiss grows on rocky limestone slopes at high altitude and is strictly protected.',
                'habitat'         => 'Rocky alpine and subalpine limestone slopes',
            ],

            // UNCOMMON - present but requires some effort to find
            [
                'common_name'     => 'Red Deer',
                'scientific_name' => 'Cervus elaphus',
                'category'        => 'mammal',
                'rarity'          => 'uncommon',
                'description'     => 'The red deer is the largest deer species in Slovenia. Males grow impressive antlers and their roar during the autumn rut echoes through the forests.',
                'habitat'         => 'Mixed forests and forest edges across Slovenia',
            ],
            [
                'common_name'     => 'White Stork',
                'scientific_name' => 'Ciconia ciconia',
                'category'        => 'bird',
                'rarity'          => 'uncommon',
                'description'     => 'The white stork is a beloved migratory bird that nests on rooftops and chimneys in Slovenian villages. It arrives in spring and departs for Africa in autumn.',
                'habitat'         => 'Wetlands, meadows, and rural villages',
            ],
            [
                'common_name'     => 'Common Kingfisher',
                'scientific_name' => 'Alcedo atthis',
                'category'        => 'bird',
                'rarity'          => 'uncommon',
                'description'     => 'A jewel-coloured bird that dives at lightning speed to catch fish. The flash of blue and orange along a riverbank is one of Slovenia\'s most dazzling wildlife sights.',
                'habitat'         => 'Clear rivers, streams, and lake shores',
            ],
            [
                'common_name'     => 'Barn Owl',
                'scientific_name' => 'Tyto alba',
                'category'        => 'bird',
                'rarity'          => 'uncommon',
                'description'     => 'The ghostly barn owl hunts silently at night with extraordinary hearing. It nests in old barns, church towers, and hollow trees across Slovenia.',
                'habitat'         => 'Farmland, meadows, and old buildings',
            ],
            [
                'common_name'     => 'Fire Salamander',
                'scientific_name' => 'Salamandra salamandra',
                'category'        => 'amphibian',
                'rarity'          => 'uncommon',
                'description'     => 'With its bold black and yellow warning pattern, the fire salamander is one of the most recognisable amphibians in Slovenia. Active after rain in forested areas.',
                'habitat'         => 'Moist deciduous forests near streams',
            ],
            [
                'common_name'     => 'European Tree Frog',
                'scientific_name' => 'Hyla arborea',
                'category'        => 'amphibian',
                'rarity'          => 'uncommon',
                'description'     => 'A tiny bright-green frog with sticky toe pads that allow it to climb plants and glass. Its loud chorus rings out from ponds on warm spring nights.',
                'habitat'         => 'Ponds, wetlands, and shrubby areas',
            ],
            [
                'common_name'     => 'European Adder',
                'scientific_name' => 'Vipera berus',
                'category'        => 'reptile',
                'rarity'          => 'uncommon',
                'description'     => 'Slovenia\'s only venomous snake, identified by its distinctive zigzag pattern along the back. It is shy and will flee when approached.',
                'habitat'         => 'Forest edges, moorland, and rocky slopes',
            ],

            // COMMON - easy to find
            [
                'common_name'     => 'Roe Deer',
                'scientific_name' => 'Capreolus capreolus',
                'category'        => 'mammal',
                'rarity'          => 'common',
                'description'     => 'The roe deer is the most common deer in Slovenia, found in virtually every forest and meadow. Males grow small, pointed antlers each year.',
                'habitat'         => 'Forests, meadows, and agricultural land',
            ],
            [
                'common_name'     => 'Wild Boar',
                'scientific_name' => 'Sus scrofa',
                'category'        => 'mammal',
                'rarity'          => 'common',
                'description'     => 'The wild boar is abundant throughout Slovenian forests. They are intelligent and adaptable, and their rooting behaviour shapes the forest floor ecosystem.',
                'habitat'         => 'Forests and forest edges throughout Slovenia',
            ],
            [
                'common_name'     => 'Red Fox',
                'scientific_name' => 'Vulpes vulpes',
                'category'        => 'mammal',
                'rarity'          => 'common',
                'description'     => 'The red fox is highly adaptable and found from mountain forests to city suburbs. They are intelligent hunters and scavengers.',
                'habitat'         => 'Forests, farmland, and urban edges',
            ],
            [
                'common_name'     => 'European Robin',
                'scientific_name' => 'Erithacus rubecula',
                'category'        => 'bird',
                'rarity'          => 'common',
                'description'     => 'The robin is one of the most familiar garden birds in Slovenia, recognised by its bright orange-red breast. It sings year-round, even on winter mornings.',
                'habitat'         => 'Gardens, parks, and woodland edges',
            ],
            [
                'common_name'     => 'Common Wall Lizard',
                'scientific_name' => 'Podarcis muralis',
                'category'        => 'reptile',
                'rarity'          => 'common',
                'description'     => 'The common wall lizard is a sun-loving reptile seen basking on stone walls, rocks, and ruins across Slovenia. It can shed and regrow its tail.',
                'habitat'         => 'Sunny rocky walls, ruins, and dry slopes',
            ],
            [
                'common_name'     => 'Porcini Mushroom',
                'scientific_name' => 'Boletus edulis',
                'category'        => 'fungi',
                'rarity'          => 'common',
                'description'     => 'The king of mushrooms, prized across Slovenia for its rich flavour. Mushroom picking is a beloved national tradition in Slovenian forests.',
                'habitat'         => 'Broadleaf and coniferous forests, especially spruce',
            ],
            [
                'common_name'     => 'Fly Agaric',
                'scientific_name' => 'Amanita muscaria',
                'category'        => 'fungi',
                'rarity'          => 'uncommon',
                'description'     => 'The iconic red-and-white spotted toadstool of fairy tales. Toxic but fascinating, it forms symbiotic relationships with birch and pine tree roots.',
                'habitat'         => 'Birch and pine forests',
            ],
        ];

        foreach ($species as $data) {
            Species::updateOrCreate(
                ['scientific_name' => $data['scientific_name']],
                $data
            );
        }

        $this->command->info('Seeded ' . count($species) . ' Slovenian species.');
    }
}
