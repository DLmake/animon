<?php

namespace App\Console\Commands;

use App\Models\Species;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ImportINaturalistSpecies extends Command
{
    protected $signature = 'animon:import-species
                            {--limit=300 : How many species to import}
                            {--iconic-taxa= : Filter by group, e.g. Aves,Mammalia,Reptilia}';

    protected $description = 'Import species observed in Slovenia from iNaturalist';

    // Maps iNaturalist iconic_taxon_name → our category enum
    private array $categoryMap = [
        'Aves'           => 'bird',
        'Mammalia'       => 'mammal',
        'Reptilia'       => 'reptile',
        'Amphibia'       => 'amphibian',
        'Actinopterygii' => 'fish',
        'Insecta'        => 'insect',
        'Arachnida'      => 'arachnid',
        'Plantae'        => 'plant',
        'Fungi'          => 'fungi',
    ];

    // Categories we actually want (skip Protozoa, Chromista, Animalia, etc.)
    private array $allowedIconicTaxa = [
        'Aves', 'Mammalia', 'Reptilia', 'Amphibia',
        'Actinopterygii', 'Insecta', 'Arachnida', 'Plantae', 'Fungi',
    ];

    public function handle(): int
    {
        // Step 1: Find Slovenia's place_id on iNaturalist
        $this->info('Looking up Slovenia on iNaturalist...');
        $placeId = $this->getSloveniaPlaceId();

        if (!$placeId) {
            $this->error('Could not find Slovenia on iNaturalist.');
            return 1;
        }

        $this->info("Found Slovenia → place_id: {$placeId}");

        // Step 2: Decide which iconic taxa to query
        $iconicTaxa = $this->option('iconic-taxa')
            ? explode(',', $this->option('iconic-taxa'))
            : $this->allowedIconicTaxa;

        $limit      = (int) $this->option('limit');
        $perPage    = 200; // iNaturalist max per page
        $created    = 0;
        $updated    = 0;
        $total      = 0;

        // Step 3: Fetch species per iconic taxa group so we get good coverage
        // (querying all at once buries rare groups under common insects/plants)
        $this->info("Importing up to {$limit} species across " . count($iconicTaxa) . " groups...");
        $bar = $this->output->createProgressBar($limit);
        $bar->start();

        $perGroup = (int) ceil($limit / count($iconicTaxa));

        foreach ($iconicTaxa as $taxonGroup) {
            $fetched = 0;
            $page    = 1;

            while ($fetched < $perGroup) {
                $toGet    = min($perPage, $perGroup - $fetched);
                $response = Http::timeout(20)->get('https://api.inaturalist.org/v1/observations/species_counts', [
                    'place_id'       => $placeId,
                    'iconic_taxa'    => $taxonGroup,
                    'per_page'       => $toGet,
                    'page'           => $page,
                    'order'          => 'desc',
                    'order_by'       => 'observations_count',
                    'verifiable'     => 'true',  // only research-grade / needs ID
                ]);

                if (!$response->successful()) {
                    $this->newLine();
                    $this->warn("API error for {$taxonGroup}: " . $response->status());
                    break;
                }

                $results = $response->json('results', []);
                if (empty($results)) break;

                foreach ($results as $result) {
                    $taxon = $result['taxon'];

                    // Skip if not in our allowed categories
                    $iconicName = $taxon['iconic_taxon_name'] ?? '';
                    if (!isset($this->categoryMap[$iconicName])) continue;

                    // Skip if no common name (usually means it's an obscure subspecies)
                    if (empty($taxon['preferred_common_name'])) continue;

                    $observationCount = $result['count'];
                    $rarity           = $this->countToRarity($observationCount);

                    $data = [
                        'common_name'     => ucfirst($taxon['preferred_common_name']),
                        'scientific_name' => $taxon['name'],
                        'category'        => $this->categoryMap[$iconicName],
                        'rarity'          => $rarity,
                        'ai_provider_id'  => (string) $taxon['id'],
                        'refrence_image'  => $taxon['default_photo']['medium_url'] ?? null,
                    ];

                    $existing = Species::where('scientific_name', $taxon['name'])
                        ->orWhere('ai_provider_id', (string) $taxon['id'])
                        ->first();

                    if ($existing) {
                        // Keep manually-curated rarity; update everything else
                        unset($data['rarity']);
                        $existing->update($data);
                        $updated++;
                    } else {
                        Species::create($data);
                        $created++;
                    }

                    $total++;
                    $bar->advance();
                }

                $fetched += count($results);
                $page++;

                // Be polite to the iNaturalist API
                usleep(300_000); // 0.3s between pages

                if ($fetched >= $perGroup || count($results) < $toGet) break;
            }
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Done! Created: {$created}  |  Updated: {$updated}  |  Total: {$total}");

        return 0;
    }

    private function getSloveniaPlaceId(): ?int
    {
        $response = Http::timeout(10)->get('https://api.inaturalist.org/v1/places/autocomplete', [
            'q' => 'Slovenia',
        ]);

        if (!$response->successful()) return null;

        foreach ($response->json('results', []) as $place) {
            // place_type 12 = country
            if (($place['place_type'] ?? 0) === 12 && str_contains($place['name'], 'Slovenia')) {
                return $place['id'];
            }
        }

        // Fallback: just take the first result named Slovenia
        foreach ($response->json('results', []) as $place) {
            if (str_contains($place['name'], 'Slovenia')) {
                return $place['id'];
            }
        }

        return null;
    }

    /**
     * Convert iNaturalist observation count to a rarity tier.
     * Thresholds are tuned for Slovenia — a mid-sized European country.
     *
     *   legendary  →  < 25 obs   (genuinely hard to find)
     *   epic       →  25–149     (specialist knows where to look)
     *   rare       →  150–749    (regular field trip find)
     *   uncommon   →  750–4999   (see it most seasons)
     *   common     →  5000+      (in your garden)
     */
    private function countToRarity(int $count): string
    {
        return match(true) {
            $count < 25    => 'legendary',
            $count < 150   => 'epic',
            $count < 750   => 'rare',
            $count < 5000  => 'uncommon',
            default        => 'common',
        };
    }
}
