<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventType;
use App\Models\GiftItem;
use App\Models\GiftRegistry;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\SupplierProfile;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // ── Event Types ─────────────────────────────────
        $eventTypes = [
            ['name' => 'Casamento', 'slug' => 'casamento', 'icon' => '', 'description' => 'O dia mais especial da sua vida', 'sort_order' => 1],
            ['name' => '15 Anos', 'slug' => '15-anos', 'icon' => '', 'description' => 'A festa que marca uma nova fase', 'sort_order' => 2],
            ['name' => 'Aniversário', 'slug' => 'aniversario', 'icon' => '', 'description' => 'Celebre mais um ano de vida', 'sort_order' => 3],
            ['name' => 'Formatura', 'slug' => 'formatura', 'icon' => '', 'description' => 'Comemore sua conquista acadêmica', 'sort_order' => 4],
            ['name' => 'Confraternização', 'slug' => 'confraternizacao', 'icon' => '', 'description' => 'Reunião especial com quem importa', 'sort_order' => 5],
            ['name' => 'Conferência', 'slug' => 'conferencia', 'icon' => '', 'description' => 'Eventos corporativos e palestras', 'sort_order' => 6],
        ];

        $typeModels = [];
        foreach ($eventTypes as $et) {
            $typeModels[$et['slug']] = EventType::create($et);
        }

        // ── Service Categories ──────────────────────────
        $categories = [
            // ['name' => 'Espaços', 'slug' => 'espacos', 'icon' => '💒', 'sort_order' => 1],
            ['name' => 'Fotografia', 'slug' => 'fotografia', 'icon' => '📸', 'sort_order' => 2],
            ['name' => 'Buffet', 'slug' => 'buffet', 'icon' => '🎂', 'sort_order' => 3],
            ['name' => 'Decoração', 'slug' => 'decoracao', 'icon' => '💐', 'sort_order' => 4],
            ['name' => 'Música', 'slug' => 'musica', 'icon' => '🎵', 'sort_order' => 5],
            ['name' => 'Vestidos', 'slug' => 'vestidos', 'icon' => '👗', 'sort_order' => 6],
        ];

        $catModels = [];
        foreach ($categories as $cat) {
            $catModels[$cat['slug']] = ServiceCategory::create($cat);
        }

        // ── Users ───────────────────────────────────────
        $client = User::create([
            'name' => 'Maria Silva',
            'email' => 'maria@celebra.com',
            'password' => Hash::make('password'),
            'role' => 'client',
        ]);

        $supplier = User::create([
            'name' => 'João Fotógrafo',
            'email' => 'joao@celebra.com',
            'password' => Hash::make('password'),
            'role' => 'supplier',
        ]);

        $supplierProfile = SupplierProfile::create([
            'user_id' => $supplier->id,
            'company_name' => 'Studio Momento Perfeito',
            'description' => 'Fotografia e vídeo profissional para eventos com mais de 10 anos de experiência.',
            'phone' => '(81) 99999-0001',
            'city' => 'Recife',
            'state' => 'PE',
            'service_category_id' => $catModels['fotografia']->id,
            'verified' => true,
        ]);

        // ── Venues ──────────────────────────────────────
        $venues = [
            [
                'name' => 'Jardim Imperial',
                'slug' => 'jardim-imperial',
                'description' => 'Um espaço encantador com jardins exuberantes e uma vista deslumbrante. Ideal para cerimônias ao ar livre com até 300 convidados.',
                'city' => 'São Paulo',
                'state' => 'SP',
                'address' => 'Rua das Flores, 1200 - Jardins',
                'type' => 'outdoor',
                'price' => 25000,
                'rating' => 4.8,
                'capacity' => 300,
                'active' => true,
            ],
            [
                'name' => 'Villa Toscana',
                'slug' => 'villa-toscana',
                'description' => 'Inspirado nas vilas italianas, oferece ambientes sofisticados com arquitetura clássica e gastronomia premiada.',
                'city' => 'São Paulo',
                'state' => 'SP',
                'address' => 'Av. Europa, 450 - Morumbi',
                'type' => 'both',
                'price' => 45000,
                'rating' => 4.9,
                'capacity' => 500,
                'active' => true,
            ],
            [
                'name' => 'Espaço Luz',
                'slug' => 'espaco-luz',
                'description' => 'Espaço moderno e minimalista no coração da cidade, perfeito para casamentos contemporâneos.',
                'city' => 'Rio de Janeiro',
                'state' => 'RJ',
                'address' => 'Rua Visconde de Pirajá, 580 - Ipanema',
                'type' => 'indoor',
                'price' => 35000,
                'rating' => 4.7,
                'capacity' => 200,
                'active' => true,
            ],
            [
                'name' => 'Fazenda Santa Clara',
                'slug' => 'fazenda-santa-clara',
                'description' => 'Fazenda histórica com capela do século XVIII, rodeada por mata atlântica e lagos naturais.',
                'city' => 'Campinas',
                'state' => 'SP',
                'address' => 'Estrada Municipal, km 12 - Sousas',
                'type' => 'outdoor',
                'price' => 30000,
                'rating' => 4.6,
                'capacity' => 400,
                'active' => true,
            ],
            [
                'name' => 'Palácio Cristal',
                'slug' => 'palacio-cristal',
                'description' => 'Salão de festas com pé-direito duplo, lustres de cristal e pista de dança iluminada.',
                'city' => 'Belo Horizonte',
                'state' => 'MG',
                'address' => 'Av. Afonso Pena, 3000 - Centro',
                'type' => 'indoor',
                'price' => 28000,
                'rating' => 4.5,
                'capacity' => 350,
                'active' => true,
            ],
            [
                'name' => 'Praia dos Sonhos',
                'slug' => 'praia-dos-sonhos',
                'description' => 'Cerimônias à beira-mar com pôr do sol inesquecível. Decoração tropical e buffet com frutos do mar.',
                'city' => 'Florianópolis',
                'state' => 'SC',
                'address' => 'Praia de Jurerê Internacional',
                'type' => 'outdoor',
                'price' => 40000,
                'rating' => 4.9,
                'capacity' => 250,
                'active' => true,
            ],
            [
                'name' => 'Casa Vintage',
                'slug' => 'casa-vintage',
                'description' => 'Casarão restaurado com decoração vintage, jardim secreto e iluminação romântica.',
                'city' => 'Curitiba',
                'state' => 'PR',
                'address' => 'Rua XV de Novembro, 800 - Centro Histórico',
                'type' => 'both',
                'price' => 22000,
                'rating' => 4.4,
                'capacity' => 150,
                'active' => true,
            ],
            [
                'name' => 'Terraço Panorâmico',
                'slug' => 'terraco-panoramico',
                'description' => 'No 30º andar, com vista 360° da cidade. Ambientação com jardim suspenso e lounge.',
                'city' => 'São Paulo',
                'state' => 'SP',
                'address' => 'Av. Paulista, 1500 - Bela Vista',
                'type' => 'both',
                'price' => 55000,
                'rating' => 4.8,
                'capacity' => 180,
                'active' => true,
            ],
            [
                'name' => 'Recanto Verde',
                'slug' => 'recanto-verde',
                'description' => 'Cercado por natureza, com lago artificial, deck de madeira e estufa climatizada.',
                'city' => 'Porto Alegre',
                'state' => 'RS',
                'address' => 'Estrada do Arroio, km 5 - Belém Novo',
                'type' => 'outdoor',
                'price' => 20000,
                'rating' => 4.3,
                'capacity' => 280,
                'active' => true,
            ],
        ];

        foreach ($venues as $venue) {
            $v = Venue::create($venue);
            // Attach random event types to each venue
            $v->eventTypes()->attach(
                collect($typeModels)->random(rand(2, 4))->pluck('id')
            );
        }

        // ── Services ────────────────────────────────────
        $services = [
            ['name' => 'Studio Momento Perfeito', 'slug' => 'studio-momento-perfeito', 'description' => 'Fotografia e vídeo profissional para eventos.', 'price' => 3500, 'rating' => 4.9, 'active' => true, 'category' => 'fotografia'],
            ['name' => 'Lens Art Fotografia', 'slug' => 'lens-art-fotografia', 'description' => 'Registros artísticos e ensaios pré-evento.', 'price' => 5000, 'rating' => 4.7, 'active' => true, 'category' => 'fotografia'],
            ['name' => 'Buffet Sabor & Arte', 'slug' => 'buffet-sabor-arte', 'description' => 'Gastronomia refinada para eventos de todos os tamanhos.', 'price' => 12000, 'rating' => 4.8, 'active' => true, 'category' => 'buffet'],
            ['name' => 'Gourmet Fest', 'slug' => 'gourmet-fest', 'description' => 'Cardápios personalizados e estação de drinks.', 'price' => 15000, 'rating' => 4.6, 'active' => true, 'category' => 'buffet'],
            ['name' => 'Flora Bella Decoração', 'slug' => 'flora-bella-decoracao', 'description' => 'Decoração floral e cenográfica para celebrações.', 'price' => 8000, 'rating' => 4.8, 'active' => true, 'category' => 'decoracao'],
            ['name' => 'DJ Pulse', 'slug' => 'dj-pulse', 'description' => 'DJ profissional com iluminação e som de alta qualidade.', 'price' => 4000, 'rating' => 4.5, 'active' => true, 'category' => 'musica'],
            ['name' => 'Orquestra Sinfonia', 'slug' => 'orquestra-sinfonia', 'description' => 'Música ao vivo clássica e contemporânea.', 'price' => 7000, 'rating' => 4.9, 'active' => true, 'category' => 'musica'],
            ['name' => 'Atelier Encanto', 'slug' => 'atelier-encanto', 'description' => 'Vestidos sob medida para noivas e debutantes.', 'price' => 6000, 'rating' => 4.7, 'active' => true, 'category' => 'vestidos'],
        ];

        foreach ($services as $svc) {
            $catSlug = $svc['category'];
            unset($svc['category']);
            $svc['service_category_id'] = $catModels[$catSlug]->id;
            // Link photography services to the demo supplier profile
            if ($catSlug === 'fotografia') {
                $svc['supplier_profile_id'] = $supplierProfile->id;
            }
            Service::create($svc);
        }

        // ── Demo Event + Gift Registry ──────────────────
        $event = Event::create([
            'user_id' => $client->id,
            'event_type_id' => $typeModels['15-anos']->id,
            'title' => 'Festa de 15 Anos da Maria',
            'slug' => 'festa-15-anos-maria',
            'event_date' => now()->addMonths(3),
            'description' => 'A festa mais esperada do ano! Celebração dos 15 anos com tema princesa.',
            'guest_count' => 200,
            'budget' => 50000,
            'gift_registry_enabled' => true,
            'public' => true,
        ]);

        $registry = GiftRegistry::create([
            'event_id' => $event->id,
            'title' => 'Lista de Presentes — 15 Anos da Maria',
            'slug' => 'festa-15-anos-maria',
            'description' => 'Ajude a Maria a realizar o sonho dos 15 anos!',
            'active' => true,
        ]);

        $gifts = [
            ['gift_registry_id' => $registry->id, 'name' => 'Jogo de Cama Queen', 'description' => 'Jogo de cama 400 fios, branco.', 'price' => 350, 'quantity_desired' => 1, 'quantity_received' => 0],
            ['gift_registry_id' => $registry->id, 'name' => 'Air Fryer', 'description' => 'Fritadeira elétrica 4L.', 'price' => 450, 'quantity_desired' => 1, 'quantity_received' => 1],
            ['gift_registry_id' => $registry->id, 'name' => 'Kit Perfume', 'description' => 'Kit com perfume e hidratante.', 'price' => 280, 'quantity_desired' => 2, 'quantity_received' => 1],
            ['gift_registry_id' => $registry->id, 'name' => 'Fone Bluetooth', 'description' => 'Fone de ouvido com cancelamento de ruído.', 'price' => 600, 'quantity_desired' => 1, 'quantity_received' => 0],
            ['gift_registry_id' => $registry->id, 'name' => 'Câmera Instax', 'description' => 'Câmera instantânea com filme.', 'price' => 500, 'quantity_desired' => 1, 'quantity_received' => 0],
        ];

        foreach ($gifts as $gift) {
            GiftItem::create($gift);
        }
    }
}
